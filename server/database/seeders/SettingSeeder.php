<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Seed default settings including maintenance mode.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'maintenance_allowed_ips',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('settings')->insert($settings);

        // Translatable maintenance messages
        $maintenanceSettingId = DB::table('settings')->where('key', 'maintenance_mode')->value('id');

        $translations = [
            [
                'setting_id' => $maintenanceSettingId,
                'locale' => 'en',
                'value' => 'Site is under maintenance. Please check back later.',
            ],
            [
                'setting_id' => $maintenanceSettingId,
                'locale' => 'am',
                'value' => 'ጣቢያው በጥገና ላይ ነው። እባክዎ ቆይተው ይመልከቱ።',
            ],
            [
                'setting_id' => $maintenanceSettingId,
                'locale' => 'om',
                'value' => 'Marsariitiin suphaa irra jira. Maaloo booda deebi\'aa ilaalaa.',
            ],
        ];

        DB::table('setting_translations')->insert($translations);
    }
}
