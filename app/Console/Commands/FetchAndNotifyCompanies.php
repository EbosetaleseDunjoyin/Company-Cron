<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Keyword;
use App\Mail\NewCompanyMail;
use Illuminate\Console\Command;
use App\Models\ProcessedCompany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Client\RequestException;


class FetchAndNotifyCompanies extends Command
{
    protected $signature = 'fetch:companies';
    protected $description = 'Fetch companies and send emails about active and recently created ones';
    protected $username , $password, $email;

    public function __construct(){

        parent::__construct();
        $this->username = env('COMPANY_API_KEY');
        $this->password = ''; 
        $this->email = env('RECEIVER_EMAIL'); 
    }
    public function handle()
    {
        // Fetch all active keywords in chunks to prevent memory overload
        try{

            Keyword::where('is_active', 1)  // Assuming 1 means 'active'
                ->chunk(100, function ($keywords) {
                    foreach ($keywords as $keyword) {
                        // Perform the search for each active keyword
                        $this->searchAndProcessCompanies($keyword->name);
                        $this->info('Keyword: '. $keyword->name);
                    }
                });
                $this->info('Search and email sending process completed for all active keywords.');
        }catch (\Exception $e){
            $this->error('Handle error: '. $e->getMessage() );
        }

    }

    // Function to search and process companies based on keyword
    public function searchAndProcessCompanies($keyword)
    {
        try{
            $url = 'https://api.company-information.service.gov.uk/search/companies';
            

            $startIndex = 0;
            $itemsPerPage = 50;
            $hasMoreResults = true;
            $totalResults = null;

            while ($hasMoreResults) {
                // Fetch companies using basic auth and pagination
                $response = Http::withBasicAuth($this->username, $this->password)
                    ->retry(3, 100)  
                    ->timeout(30)
                    ->get($url, [
                        'q' => $keyword,         
                        'items_per_page' => $itemsPerPage,  
                        'start_index' => $startIndex        
                    ]);

                // Log::info("Response: ".$response);


                if ($response->failed()) {
                    $this->error("Failed to fetch companies for keyword: {$keyword}");
                    return;
                }
                if($totalResults === null){
                    $totalResults = $response->json()['total_results'];
                    Log::info("Total Results: ".$totalResults);
                }
                $companies = $response->json()['items'];


                if (empty($companies)) {
                    $hasMoreResults = false;
                    break;
                }

                Log::info("Companies: ". count($companies));
                // Log::info($companies);

            
                $filteredCompanies = array_filter($companies, function ($company) {
                    return $company['company_status'] === 'active' &&
                        Carbon::parse($company['date_of_creation'])->gt(Carbon::now()->subDays(2)); // Adjust time range as needed
                });

                
                foreach ($filteredCompanies as $company) {
                    if (!ProcessedCompany::where('company_number', $company['company_number'])->exists()) {
                    
                        $this->fetchDirectorsAndSendEmail($company);
                        ProcessedCompany::create(['company_number' => $company['company_number']]);
                    }
                }

                $remainingItems = $totalResults - $startIndex;

                Log::info("StartIndex: {$startIndex}");
                if ($remainingItems <= $itemsPerPage) {
                    
                    $startIndex += $remainingItems;
                    $hasMoreResults = false;
                    Log::info("Remaining items: {$remainingItems}");
                } else {
                    // If more items are available, proceed to the next page
                    $startIndex += $itemsPerPage;
                }

                // $hasMoreResults = false;
            }
        } catch (RequestException $e) {
            $this->error("RequestEnded ". $e->getMessage());
        }
    }

    // Function to fetch directors and send email
    public function fetchDirectorsAndSendEmail($company)
    {
        $url = 'https://api.company-information.service.gov.uk/company/' . $company['company_number'] . '/officers';
       

        // Fetch the directors for the company
        $response = Http::withBasicAuth($this->username, $this->password)
            ->retry(3, 100)  // Retry up to 3 times with a 100ms delay between attempts
            ->timeout(30)
        ->get($url, [
            'register_type' => 'directors',
        ]);

        // Handle failed response
        if ($response->failed()) {
            $this->error("Failed to fetch directors for company: {$company['title']}");
            return;
        }

        $directors = $response->json()['items'];

        // Prepare email content
        $directorsList = collect($directors)->map(function ($director) {
            return $director['name'];
        })->join(', ');

        // Send email (adjust recipient and email logic)
        Mail::to($this->email)->send(new NewCompanyMail($company, $directors));

        $this->info("Email sent for company: {$company['title']} with " . count($directors) . " directors: {$directorsList}");
    }
}
