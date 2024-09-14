<?php

namespace Database\Seeders;

use App\Models\Keyword;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        //
        Keyword::firstOrCreate([
            "name" => "Bidco",
            "is_active" => 1
        ]);
    }
}
