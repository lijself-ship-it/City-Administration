<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Seed the languages table with Amharic, English, and Oromifa.
     */
    public function run(): void
    {
        $languages = [
            [
                'code' => 'am',
                'name' => 'አማርኛ',
                'direction' => 'ltr',
                'is_default' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'direction' => 'ltr',
                'is_default' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'om',
                'name' => 'Afaan Oromoo',
                'direction' => 'ltr',
                'is_default' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('languages')->insert($languages);
    }
}
