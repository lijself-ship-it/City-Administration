<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RefreshKombolchaSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'site_infos', 'site_info_translations',
            'emergency_contacts', 'emergency_contact_translations',
            'hero_sliders', 'hero_slider_translations',
            'menus', 'menu_translations', 'menu_items', 'menu_item_translations',
            'government_members', 'government_member_translations',
            'government_departments', 'government_department_translations',
            'services', 'service_translations', 'service_steps', 'service_step_translations',
            'news', 'news_translations',
            'events', 'event_translations',
            'vacancies', 'vacancy_translations',
            'tenders', 'tender_translations',
            'explore_categories', 'explore_category_translations',
            'explore_items', 'explore_item_translations',
            'programs', 'program_translations',
            'resource_filters', 'resource_filter_translations', 'resources', 'resource_translations', 'resource_resource_filter',
            'polls', 'poll_translations', 'poll_options', 'poll_option_translations',
            'forum_categories', 'forum_category_translations', 'forum_topics', 'forum_topic_translations',
            'social_media_links',
            'pages', 'page_translations',
            'contacts', 'contact_translations',
            'searchables'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();

        $this->call(CityContentSeeder::class);
    }
}
