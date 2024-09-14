<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $keywords = Keyword::paginate(20);
        return view("dashboard", compact("keywords"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            "name"=> "required|string|unique:keywords",
        ]);

        try{

            $keyword = Keyword::create([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Keyword added successfully.');

        }catch(\Exception $e){
            Log::error("Keyword failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating keyword.');
        }



    }

    /**
     * Display the specified resource.
     */
    public function show(Keyword $keyword)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keyword $keyword)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Keyword $keyword)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function status(Keyword $keyword)
    {
        try {
            $action = $keyword->is_active == 1 ? 0 : 1;

            $keyword->update(['is_active' => $action]);

            return redirect()->back()->with('success', "{$keyword->name} status changed");

        } catch (\Exception $e) {
            Log::error("Keyword failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred updating keyword.');
        }
        


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keyword $keyword)
    {
        //
        try {
            // $keyword->status = $keyword->status == 1 ? 1 : 0;
            if($keyword->is_active){
                return redirect()->back()->with('error', "{$keyword->name} can't be deleted, deactive please.");
            }
            $keyword->delete();
            return redirect()->back()->with('success', "Keyword deleted successfully");

        } catch (\Exception $e) {
            Log::error("Keyword failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred deleting keyword.');
        }

    }
}
