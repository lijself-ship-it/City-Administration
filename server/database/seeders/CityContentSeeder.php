<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CityContentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('users')->value('id') ?? 1;

        // 1. Site Info (Logo, site name, hotline)
        $siteInfos = [
            ['key' => 'site_name', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'site_tagline', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'logo', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'city_population', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'city_elevation', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'hotline_primary', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($siteInfos as $info) {
            $id = DB::table('site_infos')->insertGetId($info);
            if ($info['key'] === 'site_name') {
                DB::table('site_info_translations')->insert([
                    ['site_info_id' => $id, 'locale' => 'en', 'value' => 'Kombolcha City Administration'],
                    ['site_info_id' => $id, 'locale' => 'am', 'value' => 'የኮምቦልቻ ከተማ አስተዳደር'],
                    ['site_info_id' => $id, 'locale' => 'om', 'value' => 'Bulchiinsa Magaalaa Kombolchaa'],
                ]);
            } elseif ($info['key'] === 'site_tagline') {
                DB::table('site_info_translations')->insert([
                    ['site_info_id' => $id, 'locale' => 'en', 'value' => 'Northern Ethiopia Premier Industrial, Logistics & Commercial Hub'],
                    ['site_info_id' => $id, 'locale' => 'am', 'value' => 'የሰሜን ኢትዮጵያ የኢንዱስትሪ፣ የሎጂስቲክስና የንግድ ማዕከል'],
                    ['site_info_id' => $id, 'locale' => 'om', 'value' => 'Giddugala Warshaa, Loojistiksii fi Daldalaa Kaaba Itoophiyaa'],
                ]);
            } elseif ($info['key'] === 'logo') {
                DB::table('site_info_translations')->insert([
                    ['site_info_id' => $id, 'locale' => 'en', 'value' => '/assets/images/kombolcha-emblem.svg'],
                    ['site_info_id' => $id, 'locale' => 'am', 'value' => '/assets/images/kombolcha-emblem.svg'],
                    ['site_info_id' => $id, 'locale' => 'om', 'value' => '/assets/images/kombolcha-emblem.svg'],
                ]);
            } elseif ($info['key'] === 'city_population') {
                DB::table('site_info_translations')->insert([
                    ['site_info_id' => $id, 'locale' => 'en', 'value' => '230,000+'],
                    ['site_info_id' => $id, 'locale' => 'am', 'value' => '230,000+'],
                    ['site_info_id' => $id, 'locale' => 'om', 'value' => '230,000+'],
                ]);
            } elseif ($info['key'] === 'city_elevation') {
                DB::table('site_info_translations')->insert([
                    ['site_info_id' => $id, 'locale' => 'en', 'value' => '1,842 m (6,043 ft)'],
                    ['site_info_id' => $id, 'locale' => 'am', 'value' => '1,842 ሜትር'],
                    ['site_info_id' => $id, 'locale' => 'om', 'value' => 'Meetira 1,842'],
                ]);
            } elseif ($info['key'] === 'hotline_primary') {
                DB::table('site_info_translations')->insert([
                    ['site_info_id' => $id, 'locale' => 'en', 'value' => '+251 33 551 0122 / 991'],
                    ['site_info_id' => $id, 'locale' => 'am', 'value' => '+251 33 551 0122 / 991'],
                    ['site_info_id' => $id, 'locale' => 'om', 'value' => '+251 33 551 0122 / 991'],
                ]);
            }
        }

        // 2. Emergency Contacts
        $emergencies = [
            ['phone' => '033 551 0122 / 991', 'email' => 'police@kombolchacity.gov.et', 'icon' => 'shield', 'sort_order' => 1, 'is_active' => true,
             'en' => ['title' => 'Kombolcha City Police Department', 'description' => '24/7 Rapid response crime reporting & urban security hotline'],
             'am' => ['title' => 'የኮምቦልቻ ከተማ ፖሊስ መምሪያ', 'description' => 'የ24/7 ፈጣን ምላሽ፣ የፀጥታ ጥበቃና ድንገተኛ የወንጀል ጥሪ'],
             'om' => ['title' => 'Waajjira Poolisii Magaalaa Kombolchaa', 'description' => 'Tajaajila nageenyaa fi deebii hatattamaa sa\'aatii 24']
            ],
            ['phone' => '033 551 0124 / 939', 'email' => 'rescue@kombolchacity.gov.et', 'icon' => 'flame', 'sort_order' => 2, 'is_active' => true,
             'en' => ['title' => 'Kombolcha Fire & Disaster Risk Prevention', 'description' => 'Emergency industrial fire extinguishing and disaster rescue brigade'],
             'am' => ['title' => 'የእሳትና ድንገተኛ አደጋ መከላከል መምሪያ', 'description' => 'የኢንዱስትሪና የመኖሪያ አካባቢ የእሳት አደጋ መከላከያና አድን'],
             'om' => ['title' => 'Balaan Ibiddsaa fi Balaa Tasaa Ittisuu', 'description' => 'Balaa ibiddaa warshaalee fi qulqullina naannoo eeguuf']
            ],
            ['phone' => '033 551 0210 / 907', 'email' => 'hospital@kombolchacity.gov.et', 'icon' => 'ambulance', 'sort_order' => 3, 'is_active' => true,
             'en' => ['title' => 'Kombolcha General Hospital & Ambulance', 'description' => 'Immediate trauma triage, 24/7 emergency dispatch, and intensive care'],
             'am' => ['title' => 'የኮምቦልቻ አጠቃላይ ሆስፒታልና አምቡላንስ', 'description' => 'የድንገተኛ ህክምና እርዳታ፣ የፅኑ ህሙማን ማዕከልና የአምቡላንስ አገልግሎት'],
             'om' => ['title' => 'Hospitaala Waliigalaa Kombolchaa fi Ambulaansii', 'description' => 'Tajaajila yaalaa fi ambulaansii ariifachiisaa']
            ],
            ['phone' => '033 551 0450 / 988', 'email' => 'water@kombolchacity.gov.et', 'icon' => 'droplet', 'sort_order' => 4, 'is_active' => true,
             'en' => ['title' => 'Kombolcha Water & Sewerage Enterprise', 'description' => 'Main line burst repairs, industrial water supply, and drainage emergencies'],
             'am' => ['title' => 'የኮምቦልቻ ውሃና ፍሳሽ አገልግሎት ድርጅት', 'description' => 'የውሃ ቧንቧ መስመር ጥገና፣ የኢንዱስትሪ ውሃ አቅርቦትና የፍሳሽ ጥሪ'],
             'om' => ['title' => 'Dhaabbata Bishaan fi Dhangala\'oo Kombolchaa', 'description' => 'Rakkoo bishaanii fi ujummoo dhangala\'aa hatattamaan furuu']
            ],
        ];

        foreach ($emergencies as $em) {
            $id = DB::table('emergency_contacts')->insertGetId([
                'phone' => $em['phone'],
                'email' => $em['email'],
                'icon' => $em['icon'],
                'sort_order' => $em['sort_order'],
                'is_active' => $em['is_active'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('emergency_contact_translations')->insert([
                ['emergency_contact_id' => $id, 'locale' => 'en', 'title' => $em['en']['title'], 'description' => $em['en']['description']],
                ['emergency_contact_id' => $id, 'locale' => 'am', 'title' => $em['am']['title'], 'description' => $em['am']['description']],
                ['emergency_contact_id' => $id, 'locale' => 'om', 'title' => $em['om']['title'], 'description' => $em['om']['description']],
            ]);
        }

        // 3. Hero Sliders
        $sliders = [
            [
                'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1800&q=80',
                'link_url' => '/explore',
                'sort_order' => 1,
                'is_active' => true,
                'en' => ['title' => 'Welcome to Kombolcha: Industrial & Logistics Gateway', 'subtitle' => 'Home to premier industrial parks, modern dry port logistics, and thriving international manufacturing.', 'button_text' => 'Explore Kombolcha Hub'],
                'am' => ['title' => 'እንኳን ወደ ኮምቦልቻ በደህና መጡ፡ የኢንዱስትሪና የሎጂስቲክስ መዲና', 'subtitle' => 'የዘመናዊ ኢንዱስትሪ ፓርክ፣ የደረቅ ወደብ ሎጂስቲክስና የቀዳሚ ጨርቃጨርቅ ፋብሪካዎች ማዕከል!', 'button_text' => 'ኮምቦልቻን ይወቁ'],
                'om' => ['title' => 'Baga Gara Magaalaa Kombolchaatti Dhuftan', 'subtitle' => 'Handhuura paarkii warshaa, buufata gogaa fi daldala kaaba biyyattii.', 'button_text' => 'Magaalaa Daawwadhaa']
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186156f?auto=format&fit=crop&w=1800&q=80',
                'link_url' => '/services',
                'sort_order' => 2,
                'is_active' => true,
                'en' => ['title' => 'Digitized Municipal Services for Citizens & Investors', 'subtitle' => 'Apply for trade licensing, land development permits, and track administrative requests online.', 'button_text' => 'Access Citizen Services'],
                'am' => ['title' => 'ለነዋሪዎችና ለባለሀብቶች የተዘጋጀ የተቀላጠፈ ዲጂታል አገልግሎት', 'subtitle' => 'የንግድ ፈቃድ ማመልከት፣ የግንባታ ፈቃድ ማግኘትና የመስተዳድር አገልግሎቶችን በኦንላይን ያከናውኑ።', 'button_text' => 'አገልግሎቶችን ያግኙ'],
                'om' => ['title' => 'Tajaajila Dijitaalaa Lammiilee fi Abbootii Qabeenyaaf', 'subtitle' => 'Hayyama daldalaa, ijaarsa fi tajaajiloota bulchiinsaa salphaatti argadhaa.', 'button_text' => 'Tajaajiloota']
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1800&q=80',
                'link_url' => '/programs',
                'sort_order' => 3,
                'is_active' => true,
                'en' => ['title' => 'Urban Revitalization & Green Corridor Projects', 'subtitle' => 'Modernizing city boulevards, solar streetlights, drainage canals, and public community parks.', 'button_text' => 'View City Projects'],
                'am' => ['title' => 'የኮምቦልቻ ከተማ ኮሪደር ልማትና ዘመናዊ የመሰረተ ልማት ፕሮጀክቶች', 'subtitle' => 'የአስፋልት መንገዶች፣ የሶላር መብራቶች፣ የወንዝ ዳርቻ ልማትና የመናፈሻ ግንባታዎች።', 'button_text' => 'ፕሮጀክቶችን ይመልከቱ'],
                'om' => ['title' => 'Haaromsa Magaalaa fi Misooma Koriidarii', 'subtitle' => 'Ijaarsa daandiiwwanii, ibsaa aduu fi paarkiiwwan uummataa ammayyaa.', 'button_text' => 'Pirojektoota']
            ]
        ];

        foreach ($sliders as $sl) {
            $id = DB::table('hero_sliders')->insertGetId([
                'image' => $sl['image'],
                'link_url' => $sl['link_url'],
                'sort_order' => $sl['sort_order'],
                'is_active' => $sl['is_active'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('hero_slider_translations')->insert([
                ['hero_slider_id' => $id, 'locale' => 'en', 'title' => $sl['en']['title'], 'subtitle' => $sl['en']['subtitle'], 'button_text' => $sl['en']['button_text']],
                ['hero_slider_id' => $id, 'locale' => 'am', 'title' => $sl['am']['title'], 'subtitle' => $sl['am']['subtitle'], 'button_text' => $sl['am']['button_text']],
                ['hero_slider_id' => $id, 'locale' => 'om', 'title' => $sl['om']['title'], 'subtitle' => $sl['om']['subtitle'], 'button_text' => $sl['om']['button_text']],
            ]);
        }

        // 4. Menus (Mega Menu, Footer Menu)
        $menus = [
            ['location' => 'mega_menu', 'is_active' => true, 'en' => 'Mega Menu', 'am' => 'ዋና ማውጫ', 'om' => 'Baafata Guddaa'],
            ['location' => 'footer_menu', 'is_active' => true, 'en' => 'Footer Menu', 'am' => 'የግርጌ ማውጫ', 'om' => 'Baafata Jala'],
        ];

        $menuIds = [];
        foreach ($menus as $m) {
            $id = DB::table('menus')->insertGetId([
                'location' => $m['location'],
                'is_active' => $m['is_active'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $menuIds[$m['location']] = $id;

            DB::table('menu_translations')->insert([
                ['menu_id' => $id, 'locale' => 'en', 'title' => $m['en']],
                ['menu_id' => $id, 'locale' => 'am', 'title' => $m['am']],
                ['menu_id' => $id, 'locale' => 'om', 'title' => $m['om']],
            ]);
        }

        // Mega Menu Items (Top Level and Sub items)
        $megaNav = [
            [
                'url' => '/about', 'icon' => 'info', 'sort_order' => 1,
                'en' => 'About Kombolcha', 'am' => 'ስለ ኮምቦልቻ', 'om' => 'Waa\'ee Kombolchaa',
                'children' => [
                    ['url' => '/about', 'en' => 'City Overview & Profile', 'am' => 'የከተማው አጠቃላይ ገጽታ', 'om' => 'Ilaalcha Magaalaa'],
                    ['url' => '/about/history', 'en' => 'Industrial History & Heritage', 'am' => 'የኢንዱስትሪ ታሪክና ቅርስ', 'om' => 'Seenaa Warshaa'],
                    ['url' => '/about/economy', 'en' => 'Trade & Strategic Location', 'am' => 'ንግድና ስትራቴጂካዊ መገኛ', 'om' => 'Daldala fi Iddoo'],
                ]
            ],
            [
                'url' => '/government', 'icon' => 'landmark', 'sort_order' => 2,
                'en' => 'City Administration', 'am' => 'አስተዳደር', 'om' => 'Bulchiinsa',
                'children' => [
                    ['url' => '/government#mayor', 'en' => 'Mayor & Executive Cabinet', 'am' => 'ከንቲባ እና ካቢኔ', 'om' => 'Kantiibaa fi Kaabinee'],
                    ['url' => '/government#council', 'en' => 'Kombolcha City Council', 'am' => 'የከተማው ምክር ቤት', 'om' => 'Mana Maree Magaalaa'],
                    ['url' => '/government#structure', 'en' => 'Organizational Structure', 'am' => 'የአስተዳደሩ መዋቅር', 'om' => 'Caasaa Bulchiinsaa'],
                    ['url' => '/government#sub-cities', 'en' => 'Kebeles & Municipal Branches', 'am' => 'ቀበሌዎችና የማዘጋጃ ቤት ቅርንጫፎች', 'om' => 'Gandaalee fi Waajjiraalee'],
                ]
            ],
            [
                'url' => '/services', 'icon' => 'layers', 'sort_order' => 3,
                'en' => 'Citizen Services', 'am' => 'አገልግሎቶች', 'om' => 'Tajaajila',
                'children' => [
                    ['url' => '/services?type=step', 'en' => 'Step-by-Step E-Services', 'am' => 'ደረጃ በደረጃ አገልግሎቶች', 'om' => 'Tajaajila Sadarkaan'],
                    ['url' => '/services?category=permits', 'en' => 'Building & Land Permits', 'am' => 'የህንፃና የመሬት ፈቃድ', 'om' => 'Hayyama Ijaarsaa'],
                    ['url' => '/services?category=vital', 'en' => 'Vital Events Registration', 'am' => 'የልደትና ወሳኝ ኩነት ምዝገባ', 'om' => 'Galmee Ragaalee'],
                    ['url' => '/services?category=trade', 'en' => 'Trade & Investment Licensing', 'am' => 'የንግድና ኢንቨስትመንት ፈቃድ', 'om' => 'Hayyama Daldalaa'],
                ]
            ],
            [
                'url' => '/media', 'icon' => 'newspaper', 'sort_order' => 4,
                'en' => 'Media & Notices', 'am' => 'ሚዲያና ማስታወቂያ', 'om' => 'Miidiyaa fi Oduu',
                'children' => [
                    ['url' => '/media?tab=news', 'en' => 'Official City News', 'am' => 'የከተማው ይፋዊ ዜናዎች', 'om' => 'Oduu Magaalaa'],
                    ['url' => '/media?tab=events', 'en' => 'Events & Town Halls', 'am' => 'መድረኮችና ክንውኖች', 'om' => 'Qophiiwwan'],
                    ['url' => '/media?tab=vacancies', 'en' => 'Civil Service Vacancies', 'am' => 'የስራ ማስታወቂያዎች', 'om' => 'Beeksisa Hojii'],
                    ['url' => '/media?tab=tenders', 'en' => 'Public Bids & Tenders', 'am' => 'የጨረታ ማስታወቂያዎች', 'om' => 'Caalbaasii'],
                ]
            ],
            [
                'url' => '/explore', 'icon' => 'compass', 'sort_order' => 5,
                'en' => 'Explore Kombolcha', 'am' => 'ኮምቦልቻን ይጎብኙ', 'om' => 'Kombolcha Daawwadhaa',
                'children' => [
                    ['url' => '/explore', 'en' => 'Industrial Park & Dry Port', 'am' => 'ኢንዱስትሪ ፓርክና ደረቅ ወደብ', 'om' => 'Paarkii Warshaa fi Buufata'],
                    ['url' => '/gallery', 'en' => 'Photo Gallery & Landmarks', 'am' => 'የፎቶ ማህደርና መስህቦች', 'om' => 'Galmee Suuraa'],
                    ['url' => '/programs', 'en' => 'Flagship City Projects', 'am' => 'ዋና ዋና የከተማ ፕሮጀክቶች', 'om' => 'Pirojektoota'],
                ]
            ],
            [
                'url' => '/resources', 'icon' => 'file-text', 'sort_order' => 6,
                'en' => 'Transparency & Laws', 'am' => 'ግልጽነትና ሰነዶች', 'om' => 'Iftoomina',
                'children' => [
                    ['url' => '/resources', 'en' => 'Directives & Regulations', 'am' => 'መመሪያዎችና ደንቦች', 'om' => 'Qajeelfamoota'],
                    ['url' => '/forum', 'en' => 'Public Forum (የውይይት መድረክ)', 'am' => 'የነዋሪዎች ውይይት መድረክ', 'om' => 'Maree Lammiilee'],
                    ['url' => '/contact', 'en' => 'Contact & Feedback', 'am' => 'አድራሻና አስተያየት', 'om' => 'Quunnamtii'],
                ]
            ]
        ];

        foreach ($megaNav as $topItem) {
            $parentId = DB::table('menu_items')->insertGetId([
                'menu_id' => $menuIds['mega_menu'],
                'parent_id' => null,
                'url' => $topItem['url'],
                'target' => '_self',
                'icon' => $topItem['icon'],
                'sort_order' => $topItem['sort_order'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('menu_item_translations')->insert([
                ['menu_item_id' => $parentId, 'locale' => 'en', 'label' => $topItem['en']],
                ['menu_item_id' => $parentId, 'locale' => 'am', 'label' => $topItem['am']],
                ['menu_item_id' => $parentId, 'locale' => 'om', 'label' => $topItem['om']],
            ]);

            if (isset($topItem['children'])) {
                foreach ($topItem['children'] as $idx => $child) {
                    $cId = DB::table('menu_items')->insertGetId([
                        'menu_id' => $menuIds['mega_menu'],
                        'parent_id' => $parentId,
                        'url' => $child['url'],
                        'target' => '_self',
                        'icon' => null,
                        'sort_order' => $idx + 1,
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('menu_item_translations')->insert([
                        ['menu_item_id' => $cId, 'locale' => 'en', 'label' => $child['en']],
                        ['menu_item_id' => $cId, 'locale' => 'am', 'label' => $child['am']],
                        ['menu_item_id' => $cId, 'locale' => 'om', 'label' => $child['om']],
                    ]);
                }
            }
        }

        // 5. Government Members (Mayor, Cabinet, Council)
        $govMembers = [
            [
                'type' => 'mayor',
                'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80',
                'phone' => '+251 33 551 0022',
                'email' => 'mayor@kombolchacity.gov.et',
                'sort_order' => 1,
                'is_active' => true,
                'en' => ['name' => 'Hon. Mayor of Kombolcha', 'title' => 'Mayor of Kombolcha City Administration', 'bio' => 'Dedicated to transforming Kombolcha into Eastern Africa premier manufacturing hub, accelerating corridor infrastructure, and providing accessible citizen services.'],
                'am' => ['name' => 'ክቡር የኮምቦልቻ ከተማ ከንቲባ', 'title' => 'የኮምቦልቻ ከተማ አስተዳደር ከንቲባ', 'bio' => 'ኮምቦልቻን የቀጣናው መሪ የኢንዱስትሪና የንግድ ማዕከል ለማድረግ፣ የኮሪደር ልማትን ለማፋጠንና የተቀላጠፈ አገልግሎት ለህዝቡ ለመስጠት በትጋት እየሰሩ ይገኛሉ።'],
                'om' => ['name' => 'Kantiibaa Kabajamaa Magaalaa Kombolchaa', 'title' => 'Kantiibaa Bulchiinsa Magaalaa Kombolchaa', 'bio' => 'Magaalaa Kombolchaa wiirtuu warshaa fi daldalaa gochuuf, tajaajila qulqulluu lammiilee hundaaf kennuuf kutannoodhaan hojjetu.']
            ],
            [
                'type' => 'cabinet',
                'photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=600&q=80',
                'phone' => '+251 33 551 0045',
                'email' => 'industry@kombolchacity.gov.et',
                'sort_order' => 2,
                'is_active' => true,
                'en' => ['name' => 'Ato Daniel Mequanint', 'title' => 'Head of Trade, Industry & Investment Bureau', 'bio' => 'Fostering industrial park operations, export facilitation, and private sector investments in textiles, steel, and food processing.'],
                'am' => ['name' => 'አቶ ዳንኤል መኳንንት', 'title' => 'የንግድ፣ ኢንዱስትሪና ኢንቨስትመንት መምሪያ ኃላፊ', 'bio' => 'የኢንዱስትሪ ፓርክ ስራዎችን፣ የወጪ ንግድ አሰራርንና በጨርቃጨርቅና በብረታብረት ዘርፍ የግል ባለሀብቶችን ተሳትፎ በማስተባበር ላይ ይገኛሉ።'],
                'om' => ['name' => 'Obbo Daanie\'eel Maqwaannint', 'title' => 'Hogganaa Biiroo Daldalaa, Warshaa fi Invastimantii', 'bio' => 'Sochii paarkii warshaa, ergaa alaa fi invastimantii daldalaa qindeessuu irratti hojjetu.']
            ],
            [
                'type' => 'cabinet',
                'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80',
                'phone' => '+251 33 551 0055',
                'email' => 'infrastructure@kombolchacity.gov.et',
                'sort_order' => 3,
                'is_active' => true,
                'en' => ['name' => 'W/ro Selamawit Tadesse', 'title' => 'Head of Urban Infrastructure & Transport Office', 'bio' => 'Overseeing highway connectivity to the dry port, airport road networks, and modern urban drainage corridors.'],
                'am' => ['name' => 'ወ/ሮ ሰላማዊት ታደሰ', 'title' => 'የከተማ መሰረተ ልማትና ትራንስፖርት መምሪያ ኃላፊ', 'bio' => 'የደረቅ ወደብና የኤርፖርት መንገድ አውታሮችን፣ የከተማ አስፋልት መንገዶችንና የፍሳሽ ቦዮችን በበላይነት ያስተባብራሉ።'],
                'om' => ['name' => 'Aaddee Salaamaawiit Taaddasaa', 'title' => 'Hoggantuu Bu\'uuraalee Misoomaa fi Geejjibaa', 'bio' => 'Daandii buufata gogaa, xiyyaaraa fi bu\'uuraalee misooma magaalaa to\'atu.']
            ],
            [
                'type' => 'council',
                'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=600&q=80',
                'phone' => '+251 33 551 0011',
                'email' => 'council@kombolchacity.gov.et',
                'sort_order' => 4,
                'is_active' => true,
                'en' => ['name' => 'Hon. Council Speaker', 'title' => 'Speaker of Kombolcha City Council', 'bio' => 'Guiding citizen representation across all kebeles, approving municipal ordinances, and conducting oversight on executive delivery.'],
                'am' => ['name' => 'ክቡር የከተማው ምክር ቤት አፈ-ጉባኤ', 'title' => 'የኮምቦልቻ ከተማ ምክር ቤት አፈ-ጉባኤ', 'bio' => 'የነዋሪዎችን ውክልና በማረጋገጥ፣ የከተማውን ደንቦችና በጀቶች በማፅደቅና የአስፈፃሚውን ስራ በመከታተል ይመራሉ።'],
                'om' => ['name' => 'Af-yaa\'ii Mana Maree Magaalaa', 'title' => 'Af-yaa\'ii Mana Maree Kombolchaa', 'bio' => 'Hirmaannaa uummataa mirkaneessuu, seera baasuu fi to\'annoo bulchiinsaa hooggana.']
            ]
        ];

        foreach ($govMembers as $m) {
            $id = DB::table('government_members')->insertGetId([
                'type' => $m['type'],
                'photo' => $m['photo'],
                'phone' => $m['phone'],
                'email' => $m['email'],
                'social_links' => json_encode(['twitter' => 'https://x.com', 'facebook' => 'https://facebook.com']),
                'sort_order' => $m['sort_order'],
                'is_active' => $m['is_active'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('government_member_translations')->insert([
                ['government_member_id' => $id, 'locale' => 'en', 'name' => $m['en']['name'], 'title' => $m['en']['title'], 'bio' => $m['en']['bio']],
                ['government_member_id' => $id, 'locale' => 'am', 'name' => $m['am']['name'], 'title' => $m['am']['title'], 'bio' => $m['am']['bio']],
                ['government_member_id' => $id, 'locale' => 'om', 'name' => $m['om']['name'], 'title' => $m['om']['title'], 'bio' => $m['om']['bio']],
            ]);
        }

        // 6. Government Departments & Sub-Cities (Kebeles)
        $departments = [
            ['type' => 'structure', 'parent_id' => null, 'phone' => '+251 33 551 0022', 'email' => 'info@kombolchacity.gov.et', 'sort_order' => 1,
             'en' => ['name' => 'Executive Office of the Mayor', 'description' => 'Central administration, strategic planning, cabinet secretariat, and public relations', 'address' => 'Kombolcha City Hall, Main Avenue'],
             'am' => ['name' => 'የከንቲባ ጽህፈት ቤት', 'description' => 'የከተማው ማዕከላዊ አስተዳደር፣ ስትራቴጂክ እቅድና የህዝብ ግንኙነት ማስተባበሪያ', 'address' => 'የኮምቦልቻ ማዘጋጃ ቤት፣ ዋናው አቬኑ'],
             'om' => ['name' => 'Waajjira Kantiibaa', 'description' => 'Wiirtuu qindeessa imaammataa fi hoggansa olaanaa', 'address' => 'Galma Magaalaa Kombolchaa']
            ],
            ['type' => 'sub_city', 'parent_id' => null, 'phone' => '+251 33 551 0201', 'email' => 'kebele01@kombolchacity.gov.et', 'sort_order' => 2,
             'en' => ['name' => 'Kebele 01 Administration (Teklehaimanot / Qera)', 'description' => 'Heart of the historic market zone, commercial stalls, and residential woredas', 'address' => 'Near Teklehaimanot Church'],
             'am' => ['name' => 'ቀበሌ 01 አስተዳደር (ተክለሃይማኖት / ቄራ)', 'description' => 'የንግድ እንቅስቃሴዎችና የመኖሪያ ሰፈር ያቀፈ ታሪካዊ ቀበሌ', 'address' => 'ከተክለሃይማኖት ቤተክርስቲያን አጠገብ'],
             'om' => ['name' => 'Bulchiinsa Ganda 01', 'description' => 'Handhuura daldalaa fi bakka jireenya uummataa', 'address' => 'Teklehaimanot']
            ],
            ['type' => 'sub_city', 'parent_id' => null, 'phone' => '+251 33 551 0202', 'email' => 'kebele02@kombolchacity.gov.et', 'sort_order' => 3,
             'en' => ['name' => 'Kebele 02 Administration (Millennium / Worka)', 'description' => 'Fast-growing residential, educational, and healthcare expansion corridor', 'address' => 'Millennium Boulevard'],
             'am' => ['name' => 'ቀበሌ 02 አስተዳደር (ሚሌኒየም / ወርቃ)', 'description' => 'በፍጥነት በማደግ ላይ ያለ የመኖሪያ፣ የትምህርትና የጤና ተቋማት ቀበሌ', 'address' => 'ሚሌኒየም ቦሌቫርድ'],
             'om' => ['name' => 'Bulchiinsa Ganda 02', 'description' => 'Magaalaa babal\'ataa fi wiirtuu tajaajila uummataa', 'address' => 'Millennium']
            ],
            ['type' => 'sub_city', 'parent_id' => null, 'phone' => '+251 33 551 0203', 'email' => 'kebele03@kombolchacity.gov.et', 'sort_order' => 4,
             'en' => ['name' => 'Kebele 03 Administration (Piassa / Central)', 'description' => 'Central civic district containing municipal offices, financial services, and hospitality', 'address' => 'Piassa Commercial Square'],
             'am' => ['name' => 'ቀበሌ 03 አስተዳደር (ፒያሳ / ማዕከላዊ)', 'description' => 'የማዘጋጃ ቤቱን፣ የፋይናንስ ተቋማትንና የሆቴል አገልግሎቶችን የያዘ ማዕከላዊ ቀበሌ', 'address' => 'ፒያሳ የንግድ አደባባይ'],
             'om' => ['name' => 'Bulchiinsa Ganda 03 (Piassa)', 'description' => 'Wiirtuu bulchiinsaa fi tajaajila faayinaansii', 'address' => 'Piassa Square']
            ],
            ['type' => 'sub_city', 'parent_id' => null, 'phone' => '+251 33 551 0205', 'email' => 'kebele05@kombolchacity.gov.et', 'sort_order' => 5,
             'en' => ['name' => 'Kebele 05 Administration (Mitikollo / Industrial Park Zone)', 'description' => 'Hosts the Kombolcha Industrial Park, dry port freight terminal, and airport area', 'address' => 'Industrial Park Corridor'],
             'am' => ['name' => 'ቀበሌ 05 አስተዳደር (ሚቲቆሎ / የኢንዱስትሪ ፓርክ ዞን)', 'description' => 'የኮምቦልቻ ኢንዱስትሪ ፓርክን፣ የደረቅ ወደብ ጭነት ተርሚናልንና የኤርፖርት አካባቢን ያቀፈ', 'address' => 'የኢንዱስትሪ ፓርክ ኮሪደር'],
             'om' => ['name' => 'Bulchiinsa Ganda 05 (Mitikollo)', 'description' => 'Paarkii warshaa, buufata gogaa fi xiyyaaraa kan qabate', 'address' => 'Corridor Warshaa']
            ],
        ];

        foreach ($departments as $d) {
            $id = DB::table('government_departments')->insertGetId([
                'type' => $d['type'],
                'parent_id' => $d['parent_id'],
                'phone' => $d['phone'],
                'email' => $d['email'],
                'sort_order' => $d['sort_order'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('government_department_translations')->insert([
                ['government_department_id' => $id, 'locale' => 'en', 'name' => $d['en']['name'], 'description' => $d['en']['description'], 'address' => $d['en']['address']],
                ['government_department_id' => $id, 'locale' => 'am', 'name' => $d['am']['name'], 'description' => $d['am']['description'], 'address' => $d['am']['address']],
                ['government_department_id' => $id, 'locale' => 'om', 'name' => $d['om']['name'], 'description' => $d['om']['description'], 'address' => $d['om']['address']],
            ]);
        }

        // 7. Services (Both Text Service and Step Service)
        $services = [
            [
                'slug' => 'building-construction-permit',
                'type' => 'step',
                'icon' => 'home',
                'featured_image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186156f?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
                'is_published' => true,
                'en' => ['title' => 'Building & Construction Permit Application', 'description' => 'Submit architectural designs, site verification, and engineering safety plans digitally to the Kombolcha Urban Development Bureau.'],
                'am' => ['title' => 'የህንፃና ግንባታ ፈቃድ ማመልከቻ', 'description' => 'የዲዛይን ሰነዶችንና የመሬት ይዞታ ማረጋገጫዎችን በዲጂታል ስርዓቱ በኩል ለኮምቦልቻ ከተማ ልማት መምሪያ በማስገባት ፈቃድ የማግኘት ሂደት።'],
                'om' => ['title' => 'Iyyata Hayyama Ijaarsa Gamoo', 'description' => 'Ragaaleen ijaarsaa fi qorannoon lafaa sirna dijitaalaatiin galfamu qaba.'],
                'steps' => [
                    ['step_number' => 1, 'icon' => 'file-text',
                     'en' => ['title' => 'Land Title & Deed Verification', 'description' => 'Upload digital certificate of land tenure, cadastral map, and property tax receipt.'],
                     'am' => ['title' => 'የመሬት ይዞታ ማረጋገጫና ካርታ ማቅረብ', 'description' => 'የመሬት ይዞታ ማረጋገጫ ደብተር፣ የካዳስተር ካርታና የግብር ክፍያ ደረሰኝ ማቅረብ።'],
                     'om' => ['title' => 'Ragaa Mirga Qabiyyee Mirkaneessuu', 'description' => 'Waraqaa ragaa lafaa fi heeyyama kaffaltii gibiraa dhiyeessuu.']
                    ],
                    ['step_number' => 2, 'icon' => 'check-circle',
                     'en' => ['title' => 'Engineering & Safety Review', 'description' => 'City engineers review structural stability, fire emergency exits, and environmental runoff.'],
                     'am' => ['title' => 'የምህንድስና እና ደህንነት ግምገማ', 'description' => 'የህንጻው ደህንነት፣ የአደጋ ጊዜ መውጫዎችና የፍሳሽ ቦይ አቀማመጥ በባለሙያዎች ይገመገማል።'],
                     'om' => ['title' => 'Qorannoo Diizaayinii fi Injinerummaa', 'description' => 'Nageenyummaa ijaarsaa fi seera eegumsa naannoo qorachuu.']
                    ],
                    ['step_number' => 3, 'icon' => 'credit-card',
                     'en' => ['title' => 'Municipal Fee Payment', 'description' => 'Settlement of engineering clearance fee via Telebirr or CBE Birr with instant e-receipt.'],
                     'am' => ['title' => 'የከተማ ፈቃድ ክፍያ መፈጸም', 'description' => 'የፈቃድ ክፍያውን በቴሌብር ወይም በኢትዮጵያ ንግድ ባንክ በቀጥታ በመክፈል ደረሰኝ ማግኘት።'],
                     'om' => ['title' => 'Kaffaltii Tajaajilaa Raawwachuu', 'description' => 'Herrega kaffaltii uumame Telebirr ykn baankiidhaan kaffaluu.']
                    ],
                    ['step_number' => 4, 'icon' => 'download',
                     'en' => ['title' => 'Digital Permit Issuance', 'description' => 'Download official QR-stamped construction permit for on-site display.'],
                     'am' => ['title' => 'የዲጂታል ፈቃድ ማውረድ', 'description' => 'በQR-ኮድ የተረጋገጠውን ይፋዊ የግንባታ ፈቃድ በቀጥታ ማውረድ።'],
                     'om' => ['title' => 'Waraqaa Hayyamaa Fudhachuu', 'description' => 'Waraqaa heeyyamaa mallattoo QR qabu buufachuun ijaarsa jalqabuu.']
                    ],
                ]
            ],
            [
                'slug' => 'commercial-trade-license-renewal',
                'type' => 'step',
                'icon' => 'briefcase',
                'featured_image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
                'is_published' => true,
                'en' => ['title' => 'Commercial Trade & Industrial License Renewal', 'description' => 'Instant annual business renewal for manufacturing enterprises, retail shops, and transport logistics services in Kombolcha.'],
                'am' => ['title' => 'የንግድና የኢንዱስትሪ ፈቃድ እድሳት', 'description' => 'በኮምቦልቻ ለሚገኙ የአምራች ኢንዱስትሪዎች፣ ለጅምላና ችርቻሮ ነጋዴዎችና ለትራንስፖርት አገልግሎቶች አመታዊ የፈቃድ እድሳት።'],
                'om' => ['title' => 'Hayyama Daldalaa fi Warshaa Haaromsuu', 'description' => 'Heeyyama daldalaa waggaa haareffachuu fi ragaa kaffaltii gibiraa mirkaneessuu.']
            ],
            [
                'slug' => 'vital-events-birth-registration',
                'type' => 'text',
                'icon' => 'user-check',
                'featured_image' => 'https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
                'is_published' => true,
                'en' => ['title' => 'Vital Events & Civil Registration', 'description' => 'Official registration of births, marriages, and legal events within Kombolcha City Administration. Certificates are processed within 24 to 48 hours.'],
                'am' => ['title' => 'የልደትና ወሳኝ ኩነት ምዝገባ', 'description' => 'የልጆች ይፋዊ የልደት ምዝገባ፣ የጋብቻና የነዋሪነት ማስረጃ በ24 እስከ 48 ሰዓታት ውስጥ ህጋዊ ሰርተፊኬት የማግኘት አገልግሎት።'],
                'om' => ['title' => 'Galmee Dhalootaa fi Waraqaa Ragaa', 'description' => 'Galmee dhaloota daa\'immanii fi waraqaa ragaa seeraa sa\'aatii 48 keessatti argachuuf.']
            ]
        ];

        foreach ($services as $srv) {
            $srvId = DB::table('services')->insertGetId([
                'slug' => $srv['slug'],
                'type' => $srv['type'],
                'icon' => $srv['icon'],
                'featured_image' => $srv['featured_image'],
                'sort_order' => $srv['sort_order'],
                'is_published' => $srv['is_published'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('service_translations')->insert([
                ['service_id' => $srvId, 'locale' => 'en', 'title' => $srv['en']['title'], 'description' => $srv['en']['description']],
                ['service_id' => $srvId, 'locale' => 'am', 'title' => $srv['am']['title'], 'description' => $srv['am']['description']],
                ['service_id' => $srvId, 'locale' => 'om', 'title' => $srv['om']['title'], 'description' => $srv['om']['description']],
            ]);

            if (isset($srv['steps'])) {
                foreach ($srv['steps'] as $st) {
                    $stId = DB::table('service_steps')->insertGetId([
                        'service_id' => $srvId,
                        'step_number' => $st['step_number'],
                        'icon' => $st['icon'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('service_step_translations')->insert([
                        ['service_step_id' => $stId, 'locale' => 'en', 'title' => $st['en']['title'], 'description' => $st['en']['description']],
                        ['service_step_id' => $stId, 'locale' => 'am', 'title' => $st['am']['title'], 'description' => $st['am']['description']],
                        ['service_step_id' => $stId, 'locale' => 'om', 'title' => $st['om']['title'], 'description' => $st['om']['description']],
                    ]);
                }
            }
        }

        // 8. News Articles (Kombolcha specific)
        $newsItems = [
            [
                'slug' => 'kombolcha-industrial-park-expansion-new-investors',
                'featured_image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'published_at' => $now->subDays(1),
                'en' => ['title' => 'Kombolcha Industrial Park Welcomes 6 New Export Textile & Garment Manufacturers', 'excerpt' => 'Over 4,500 new industrial jobs created as state-of-the-art production sheds reach full operational capacity.', 'content' => 'The Kombolcha City Administration in collaboration with the Industrial Parks Development Corporation (IPDC) inaugurated new production lines creating employment opportunities and boosting foreign currency earnings.'],
                'am' => ['title' => 'የኮምቦልቻ ኢንዱስትሪ ፓርክ 6 አዳዲስ የጨርቃጨርቅ አምራች ኩባንያዎችን ተቀበለ', 'excerpt' => 'በፓርኩ ሙሉ አቅም ስራ መጀመር ምክንያት ከ4,500 በላይ ለሚሆኑ ወጣቶች አዲስ የስራ እድል ተፈጥሯል።', 'content' => 'የኮምቦልቻ ከተማ አስተዳደር ከኢንዱስትሪ ፓርኮች ልማት ኮርፖሬሽን ጋር በመተባበር የወጪ ንግድ ምርቶችን የሚያመርቱ አዳዲስ የማምረቻ ሼዶችን አስመርቋል።'],
                'om' => ['title' => 'Paarkiin Warshaa Kombolchaa Dhaabbilee Haaraa 6 Simatee Hojiitti Seene', 'excerpt' => 'Carraan hojii namoota 4,500 ol ta\'aniif uumameera.', 'content' => 'Bulchiinsi Magaalaa Kombolchaa warshaalee oomisha alaa dhiyeessan cimsuudhaan hojjetaa jira.']
            ],
            [
                'slug' => 'kombolcha-dry-port-capacity-upgrade-customs-modernization',
                'featured_image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'published_at' => $now->subDays(3),
                'en' => ['title' => 'Kombolcha Dry Port Terminal Expands Handling Capacity with Automated Crane Systems', 'excerpt' => 'Container dwell time reduced from 7 days to under 48 hours with single-window electronic cargo customs clearing.', 'content' => 'The modernization of the Kombolcha Dry Port reinforces the city pivotal standing as the northern multi-modal logistics backbone connecting ports and domestic manufacturing hubs.'],
                'am' => ['title' => 'የኮምቦልቻ ደረቅ ወደብ ተርሚናል በዘመናዊ የክሬን ስርዓት የመስተንግዶ አቅሙን አሳደገ', 'excerpt' => 'የኮንቴነሮች የጉምሩክ ክሊራንስ ቆይታ ከ7 ቀናት ወደ 48 ሰዓታት ዝቅ እንዲል ተደርጓል።', 'content' => 'ይህ የደረቅ ወደብ ማሻሻያ ኮምቦልቻ ለሰሜን ኢትዮጵያ ንግድና ኢንዱስትሪ ያላትን ስትራቴጂካዊ የሎጂስቲክስ ማዕከልነት ያጠናክራል።'],
                'om' => ['title' => 'Buufanni Gogaa Kombolchaa Dandeettii Tajaajila Isaa Guddisera', 'excerpt' => 'Yeroon tajaajila koonteenaraa guyyoota 7 irraa gara sa\'aatii 48tti gad bu\'eera.', 'content' => 'Magaalaan Kombolchaa handhuura loojistiksii kaaba biyyattii ta\'uun tajaajila saffisaa kennaa jirti.']
            ],
            [
                'slug' => 'kombolcha-city-boulevard-solar-lighting-and-green-corridor',
                'featured_image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'published_at' => $now->subDays(5),
                'en' => ['title' => 'Kombolcha City Corridor: 8.5 km Modernized Boulevard with Solar Lighting Completed', 'excerpt' => 'New pedestrian walkaways, cycling corridors, and 12,000 shade trees beautify the central highway.', 'content' => 'The municipal urban corridor program provides safe pedestrian crossings, continuous solar illumination, and refreshed public greenery across Kebele 01 to Kebele 03.'],
                'am' => ['title' => 'የኮምቦልቻ ከተማ ኮሪደር፡ 8.5 ኪሎ ሜትር የሶላር መብራትና የእግረኛ መንገድ ተጠናቀቀ', 'excerpt' => 'ውብ የእግረኛ መንገዶች፣ የብስክሌት መስመሮችና 12,000 ጥላ ዛፎች በዋናው አውራ ጎዳና ዳርቻ ተተክለዋል።', 'content' => 'ይህ የኮሪደር ልማት ለነዋሪዎች ደህንነቱ የተጠበቀ የእግር ጉዞ ምቾትና የሌሊት የመንገድ ውበት ፈጥሯል።'],
                'om' => ['title' => 'Koriidariin Magaalaa Kombolchaa Kiiloo Meetira 8.5 Xumurame', 'excerpt' => 'Daandiin miilaa fi ibsaan aduu sarara magaalichaa irratti diriireera.', 'content' => 'Hojiin kun nageenya lammiilee fi miidhagina magaalichaa mirkaneessuuf gargaara.']
            ]
        ];

        foreach ($newsItems as $n) {
            $nId = DB::table('news')->insertGetId([
                'slug' => $n['slug'],
                'featured_image' => $n['featured_image'],
                'is_featured' => $n['is_featured'],
                'is_published' => true,
                'views_count' => rand(250, 3100),
                'published_at' => $n['published_at'],
                'author_id' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('news_translations')->insert([
                ['news_id' => $nId, 'locale' => 'en', 'title' => $n['en']['title'], 'excerpt' => $n['en']['excerpt'], 'content' => $n['en']['content']],
                ['news_id' => $nId, 'locale' => 'am', 'title' => $n['am']['title'], 'excerpt' => $n['am']['excerpt'], 'content' => $n['am']['content']],
                ['news_id' => $nId, 'locale' => 'om', 'title' => $n['om']['title'], 'excerpt' => $n['om']['excerpt'], 'content' => $n['om']['content']],
            ]);
        }

        // 9. Events
        $events = [
            [
                'slug' => 'kombolcha-annual-industrial-and-trade-expo-2026',
                'featured_image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
                'start_date' => $now->addDays(14),
                'end_date' => $now->addDays(18),
                'is_published' => true,
                'en' => ['title' => 'Kombolcha Regional Industrial & Trade Expo 2026', 'description' => 'Showcasing textiles, garments, leather, construction products, and agro-industries with 150+ manufacturers.', 'venue' => 'Kombolcha Industrial Park Exhibition Grounds'],
                'am' => ['title' => 'የኮምቦልቻ ዓመታዊ የኢንዱስትሪና ንግድ ኤክስፖ 2026', 'description' => 'የጨርቃጨርቅ፣ የብረታብረት፣ የቆዳና የግብርና ምርቶችን የሚያስተዋውቁ ከ150 በላይ አምራቾች የሚሳተፉበት አውደ-ርዕይ።', 'venue' => 'የኮምቦልቻ ኢንዱስትሪ ፓርክ ኤግዚቢሽን ማዕከል'],
                'om' => ['title' => 'Agarsiisa Daldalaa fi Warshaa Magaalaa Kombolchaa', 'description' => 'Oomishaalee warshaa fi daldalaa hawwatoo dhiyeessuu fi walitti hidhamiinsa uumuu.', 'venue' => 'Galma Paarkii Warshaa Kombolchaa']
            ],
            [
                'slug' => 'kombolcha-town-hall-citizen-participatory-budget-meeting',
                'featured_image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80',
                'start_date' => $now->addDays(22),
                'end_date' => $now->addDays(23),
                'is_published' => true,
                'en' => ['title' => 'Kombolcha Citizen Town Hall & Participatory Budget Forum', 'description' => 'Public consultation between the Mayor, Kebele representatives, and residents regarding neighborhood infrastructure investments.', 'venue' => 'Kombolcha Municipal Hall'],
                'am' => ['title' => 'የከተማ ከንቲባና የነዋሪዎች የጋራ የልማት ውይይት መድረክ', 'description' => 'ከንቲባውና የካቢኔ አባላት ከሁሉም ቀበሌ ነዋሪዎች ጋር በመሰረተ ልማትና በበጀት አጠቃቀም ዙሪያ የሚያደርጉት ግልጽ ውይይት።', 'venue' => 'የኮምቦልቻ ማዘጋጃ ቤት አዳራሽ'],
                'om' => ['title' => 'Yaa\'ii Marii Kantiibaa fi Uummata Magaalaa Kombolchaa', 'description' => 'Dhimmoota misoomaa fi baajata naannoo irratti mari\'achuuf.', 'venue' => 'Galma Bulchiinsa Magaalaa']
            ]
        ];

        foreach ($events as $ev) {
            $evId = DB::table('events')->insertGetId([
                'slug' => $ev['slug'],
                'featured_image' => $ev['featured_image'],
                'start_date' => $ev['start_date'],
                'end_date' => $ev['end_date'],
                'is_published' => $ev['is_published'],
                'author_id' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('event_translations')->insert([
                ['event_id' => $evId, 'locale' => 'en', 'title' => $ev['en']['title'], 'description' => $ev['en']['description'], 'venue' => $ev['en']['venue']],
                ['event_id' => $evId, 'locale' => 'am', 'title' => $ev['am']['title'], 'description' => $ev['am']['description'], 'venue' => $ev['am']['venue']],
                ['event_id' => $evId, 'locale' => 'om', 'title' => $ev['om']['title'], 'description' => $ev['om']['description'], 'venue' => $ev['om']['venue']],
            ]);
        }

        // 10. Vacancies
        $vacancies = [
            [
                'slug' => 'senior-civil-engineer-urban-drainage',
                'deadline' => $now->addDays(14)->toDateString(),
                'salary_range' => 'ETB 28,000 - 42,000 / month',
                'is_published' => true,
                'en' => ['title' => 'Senior Civil Engineer - Urban Drainage & Flood Mitigation', 'description' => 'Design, supervise, and verify hydraulic drainage channels, culverts, and asphalt paving across Kombolcha.', 'requirements' => 'BSc/MSc in Civil Engineering or Water Resources, 4+ years municipal infrastructure experience.', 'department' => 'Kombolcha Urban Development & Infrastructure Office', 'location' => 'City Hall, Piassa'],
                'am' => ['title' => 'ሲኒየር ሲቪል መሀንዲስ - የከተማ ፍሳሽና የጎርፍ መከላከያ', 'description' => 'የኮምቦልቻ ከተማ የፍሳሽ ቦዮችን፣ ድልድዮችንና የመንገድ አስፋልት ስራዎችን ዲዛይንና ቁጥጥር ማድረግ።', 'requirements' => 'በሲቪል ምህንድስና የመጀመሪያ ወይም ሁለተኛ ዲግሪና ከ4 ዓመት በላይ ልምድ።', 'department' => 'የከተማ ልማትና መሰረተ ልማት ጽ/ቤት', 'location' => 'ማዘጋጃ ቤት፣ ፒያሳ'],
                'om' => ['title' => 'Injinaara Siiviilii Olaanaa', 'description' => 'Ijaarsa ujummoo dhangala\'aa fi daandii magaalichaa to\'achuu.', 'requirements' => 'Muuxannoo hojii waggaa 4 ol kan qabu.', 'department' => 'Waajjira Bu\'uura Misoomaa', 'location' => 'Kombolchaa']
            ],
            [
                'slug' => 'senior-it-systems-administrator-e-services',
                'deadline' => $now->addDays(18)->toDateString(),
                'salary_range' => 'ETB 26,000 - 38,000 / month',
                'is_published' => true,
                'en' => ['title' => 'IT Systems Administrator & E-Government Lead', 'description' => 'Oversee the municipal citizen portal, local server infrastructure, and kebele digital connectivity.', 'requirements' => 'Degree in Computer Science or IT, experience with Linux, networking, and web applications.', 'department' => 'Kombolcha Innovation & Technology Unit', 'location' => 'Kombolcha Municipal Complex'],
                'am' => ['title' => 'የኢንፎርሜሽን ቴክኖሎጂ ሲስተም አስተዳዳሪ', 'description' => 'የከተማውን ዲጂታል አገልግሎት ፖርታል፣ የአገልጋይ ሰርቨሮችንና የቀበሌዎች የኔትወርክ ግንኙነት መከታተል።', 'requirements' => 'በኮምፒውተር ሳይንስ ወይም አይቲ የተመረቀና የስራ ልምድ ያለው።', 'department' => 'የኢኖቬሽንና ቴክኖሎጂ ክፍል', 'location' => 'ማዘጋጃ ቤት ግቢ'],
                'om' => ['title' => 'Ogeessa Teeknooloojii Odeeffannoo', 'description' => 'Tajaajila dijitaalaa fi sirna netwoorkii magaalichaa to\'achuu.', 'requirements' => 'Barnoota Saayinsii Kompiitaraa kan qabu.', 'department' => 'Kutaa Teeknooloojii Odeeffannoo', 'location' => 'Kombolchaa']
            ]
        ];

        foreach ($vacancies as $vac) {
            $vId = DB::table('vacancies')->insertGetId([
                'slug' => $vac['slug'],
                'deadline' => $vac['deadline'],
                'salary_range' => $vac['salary_range'],
                'is_published' => $vac['is_published'],
                'author_id' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('vacancy_translations')->insert([
                ['vacancy_id' => $vId, 'locale' => 'en', 'title' => $vac['en']['title'], 'description' => $vac['en']['description'], 'requirements' => $vac['en']['requirements'], 'department' => $vac['en']['department'], 'location' => $vac['en']['location']],
                ['vacancy_id' => $vId, 'locale' => 'am', 'title' => $vac['am']['title'], 'description' => $vac['am']['description'], 'requirements' => $vac['am']['requirements'], 'department' => $vac['am']['department'], 'location' => $vac['am']['location']],
                ['vacancy_id' => $vId, 'locale' => 'om', 'title' => $vac['om']['title'], 'description' => $vac['om']['description'], 'requirements' => $vac['om']['requirements'], 'department' => $vac['om']['department'], 'location' => $vac['om']['location']],
            ]);
        }

        // 11. Tenders
        $tenders = [
            [
                'slug' => 'tender-kombolcha-solar-street-lighting-phase-2',
                'reference_number' => 'KMB-ENG-2026-034',
                'opening_date' => $now->toDateString(),
                'closing_date' => $now->addDays(25)->toDateString(),
                'status' => 'open',
                'attachment' => '/documents/tender-kmb-eng-2026-034.pdf',
                'is_published' => true,
                'en' => ['title' => 'Supply and Installation of 1,200 Solar LED Integrated Streetlights', 'description' => 'Design, supply, battery bank installation, and warranty maintenance across Kebele 01, 02, and 04 arterial roads.'],
                'am' => ['title' => '1,200 የፀሐይ ብርሃን የተቀናጁ የመንገድ መብራቶች አቅርቦትና ተከላ ጨረታ', 'description' => 'በቀበሌ 01፣ 02 እና 04 አውራ ጎዳናዎች ላይ የሶላር የመንገድ መብራቶችን የማቅረብና የመትከል ይፋዊ ጨረታ።'],
                'om' => ['title' => 'Caalbaasii Ibsaa Daandii Aduutiin Hojjetu Dhaabuu', 'description' => 'Ibsaa daandii 1,200 dhaabuuf caalbaasii bahe.']
            ],
            [
                'slug' => 'tender-drainage-canal-stone-masonry-culverts',
                'reference_number' => 'KMB-CON-2026-052',
                'opening_date' => $now->toDateString(),
                'closing_date' => $now->addDays(30)->toDateString(),
                'status' => 'open',
                'attachment' => '/documents/tender-kmb-con-2026-052.pdf',
                'is_published' => true,
                'en' => ['title' => 'Construction of Heavy-Duty Stone Masonry Drainage Culverts & Flood Retaining Walls', 'description' => 'Construction of 4.2 km flood protective retaining structures along Borkena river tributaries.'],
                'am' => ['title' => 'የድንጋይ ሜሶነሪ የፍሳሽ ቦዮችና የጎርፍ መከላከያ ግንባታ ጨረታ', 'description' => 'በቦርከና ወንዝ ገባሮች ዙሪያ 4.2 ኪሎ ሜትር የጎርፍ መከላከያ ግንብ የመገንባት ይፋዊ ጨረታ።'],
                'om' => ['title' => 'Caalbaasii Ijaarsa Ujummoo Dhangala\'aa fi Dallaa Balaa Bishaanii', 'description' => 'Ijaarsa to\'annoo lolaa kiiloo meetira 4.2 ijaaruuf.']
            ]
        ];

        foreach ($tenders as $tnd) {
            $tId = DB::table('tenders')->insertGetId([
                'slug' => $tnd['slug'],
                'reference_number' => $tnd['reference_number'],
                'opening_date' => $tnd['opening_date'],
                'closing_date' => $tnd['closing_date'],
                'status' => $tnd['status'],
                'attachment' => $tnd['attachment'],
                'is_published' => $tnd['is_published'],
                'author_id' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('tender_translations')->insert([
                ['tender_id' => $tId, 'locale' => 'en', 'title' => $tnd['en']['title'], 'description' => $tnd['en']['description']],
                ['tender_id' => $tId, 'locale' => 'am', 'title' => $tnd['am']['title'], 'description' => $tnd['am']['description']],
                ['tender_id' => $tId, 'locale' => 'om', 'title' => $tnd['om']['title'], 'description' => $tnd['om']['description']],
            ]);
        }

        // 12. Explore Categories & Items (Kombolcha Hubs)
        $catParkId = DB::table('explore_categories')->insertGetId([
            'slug' => 'industrial-and-logistics',
            'featured_image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 1,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('explore_category_translations')->insert([
            ['explore_category_id' => $catParkId, 'locale' => 'en', 'name' => 'Industrial & Logistics Infrastructure', 'description' => 'World-class industrial parks, dry ports, and manufacturing flagships.'],
            ['explore_category_id' => $catParkId, 'locale' => 'am', 'name' => 'የኢንዱስትሪና የሎጂስቲክስ መሰረተ ልማቶች', 'description' => 'ዓለም አቀፍ የኢንዱስትሪ ፓርክ፣ ደረቅ ወደብና ግዙፍ አምራች ፋብሪካዎች።'],
            ['explore_category_id' => $catParkId, 'locale' => 'om', 'name' => 'Paarkii Warshaa fi Loojistiksii', 'description' => 'Bu\'uuraalee misoomaa warshaa fi buufataalee gurguddoo.'],
        ]);

        $catMuseumId = DB::table('explore_categories')->insertGetId([
            'slug' => 'heritage-and-trade-routes',
            'featured_image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 2,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('explore_category_translations')->insert([
            ['explore_category_id' => $catMuseumId, 'locale' => 'en', 'name' => 'Heritage & Regional Attractions', 'description' => 'Historic trade corridors, cultural markets, and scenic Wollo highlands.'],
            ['explore_category_id' => $catMuseumId, 'locale' => 'am', 'name' => 'ቅርሶችና የቀጣናው መስህቦች', 'description' => 'የንግድ መተላለፊያ መንገዶች፣ ታዋቂ የባህል ገበያዎችና የተፈጥሮ ውበቶች።'],
            ['explore_category_id' => $catMuseumId, 'locale' => 'om', 'name' => 'Dhaala Seenaa fi Hawwata', 'description' => 'Seenaa daldalaa fi bakkeewwan uumamaa bareedaa.'],
        ]);

        $destinations = [
            [
                'category_id' => $catParkId,
                'slug' => 'kombolcha-industrial-park-ipdc',
                'featured_image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
                'is_published' => true,
                'en' => ['name' => 'Kombolcha Industrial Park (IPDC)', 'description' => 'Modern industrial eco-park spanning 75 hectares dedicated to apparel, textiles, and light manufacturing with dedicated wastewater treatment.'],
                'am' => ['name' => 'የኮምቦልቻ ኢንዱስትሪ ፓርክ', 'description' => 'በ75 ሄክታር ላይ ያረፈ ዘመናዊ የኢንዱስትሪ ፓርክ፤ ለአልባሳት፣ ጨርቃጨርቅና ቀላል ማምረቻዎች የተዘጋጀ።'],
                'om' => ['name' => 'Paarkii Warshaa Kombolchaa', 'description' => 'Paarkii warshaa ammayyaa hektaara 75 irratti ijaaramee oomisha huccuu fi uffataaf oolu.']
            ],
            [
                'category_id' => $catParkId,
                'slug' => 'kombolcha-dry-port-multimodal-terminal',
                'featured_image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
                'is_published' => true,
                'en' => ['name' => 'Kombolcha Dry Port & Customs Hub', 'description' => 'The premier multimodal freight inland port connecting Djibouti port transit cargo with northern Ethiopia.'],
                'am' => ['name' => 'የኮምቦልቻ ደረቅ ወደብና ጉምሩክ ማዕከል', 'description' => 'ከጅቡቲ ወደብ የሚመጡና ወደ ውጭ የሚላኩ የኮንቴነር ጭነቶችን የሚያስተናግድ የቀዳሚው የሰሜን ኢትዮጵያ ወደብ።'],
                'om' => ['name' => 'Buufata Gogaa Kombolchaa', 'description' => 'Buufata gogaa loojistiksii geejjiba alaa fi keessaa qindeessu.']
            ],
            [
                'category_id' => $catMuseumId,
                'slug' => 'bati-cultural-camel-market-gateway',
                'featured_image' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
                'is_published' => true,
                'en' => ['name' => 'Bati Historic Market & Cultural Trade Gateway', 'description' => 'Famous cultural Monday market bringing together Amhara, Oromo, and Afar merchants for centuries of vibrant traditional trade.'],
                'am' => ['name' => 'የባቲ ታሪካዊ የባህል ገበያ መተላለፊያ', 'description' => 'የአማራ፣ የኦሮሞና የአፋር ህዝቦች ለዘመናት የሚገበያዩበትና በዓለም አቀፍ ደረጃ የታወቀው የሰኞ የባህል ገበያ።'],
                'om' => ['name' => 'Gabaa Seena Qabeessa Baatee', 'description' => 'Gabaa aadaa seena qabeessa daldaltoota Oromoo, Amaaraa fi Affaar walitti fidu.']
            ]
        ];

        foreach ($destinations as $dest) {
            $dId = DB::table('explore_items')->insertGetId([
                'explore_category_id' => $dest['category_id'],
                'slug' => $dest['slug'],
                'featured_image' => $dest['featured_image'],
                'sort_order' => $dest['sort_order'],
                'is_published' => $dest['is_published'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('explore_item_translations')->insert([
                ['explore_item_id' => $dId, 'locale' => 'en', 'name' => $dest['en']['name'], 'description' => $dest['en']['description']],
                ['explore_item_id' => $dId, 'locale' => 'am', 'name' => $dest['am']['name'], 'description' => $dest['am']['description']],
                ['explore_item_id' => $dId, 'locale' => 'om', 'name' => $dest['om']['name'], 'description' => $dest['om']['description']],
            ]);
        }

        // 13. Programs & Key Initiatives
        $programs = [
            [
                'slug' => 'kombolcha-green-corridor-and-drainage-masterplan',
                'featured_image' => 'https://images.unsplash.com/photo-1477959858617-67f30bc75b82?auto=format&fit=crop&w=800&q=80',
                'start_date' => '2025-01-01',
                'end_date' => '2027-12-31',
                'budget' => 95000000.00,
                'progress_percentage' => 68,
                'status' => 'ongoing',
                'is_published' => true,
                'en' => ['title' => 'Kombolcha Green Boulevard & River Corridor Project', 'description' => 'Revitalizing the primary highway boulevard with continuous LED solar lighting, separated pedestrian pavements, and Borkena riverbank greenery.', 'objectives' => '1. Complete 14km solar-lit roadway. 2. Plant 80,000 shade trees. 3. Upgrade storm drainage systems.'],
                'am' => ['title' => 'የኮምቦልቻ አረንጓዴ ኮሪደርና የወንዝ ዳርቻ ልማት ፕሮጀክት', 'description' => 'የዋናውን አውራ ጎዳና በሶላር መብራቶች፣ በእግረኛ መንገዶችና በቦርከና ወንዝ ዳርቻ አረንጓዴ ፓርክ የማስዋብ ስራ።', 'objectives' => '1. 14 ኪሜ የመንገድ መብራት ማጠናቀቅ። 2. 80,000 ዛፎችን መትከል። 3. የፍሳሽ ቦዮችን ማዘመን።'],
                'om' => ['title' => 'Pirojektii Koriidarii Magariisaa Magaalaa Kombolchaa', 'description' => 'Ijaarsa daandii ibsaa aduu, daandii miilaa fi magariisummaa qarqara lagaa.', 'objectives' => '1. Daandii km 14 xumuruu. 2. Biqiltuu 80,000 dhaabuu.']
            ],
            [
                'slug' => 'kombolcha-smart-city-e-governance-rollout',
                'featured_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
                'start_date' => '2025-06-01',
                'end_date' => '2027-06-30',
                'budget' => 45000000.00,
                'progress_percentage' => 52,
                'status' => 'ongoing',
                'is_published' => true,
                'en' => ['title' => 'Kombolcha Digital City Portal & Kebele Connectivity', 'description' => 'Connecting all 5 kebeles via fiber optic intranet, automating civil status records, and launching online citizen requests.', 'objectives' => '1. Fiber connection to all 5 kebeles. 2. 100% digital business permits.'],
                'am' => ['title' => 'የኮምቦልቻ ስማርት ከተማና የዲጂታል አገልግሎት ዝርጋታ', 'description' => 'ሁሉንም 5 ቀበሌዎች በፋይበር ኦፕቲክ በማስተሳሰር የነዋሪዎችን አገልግሎትና የንግድ ፈቃድ በኦንላይን ማስተናገድ።', 'objectives' => '1. 5ቱን ቀበሌዎች በፋይበር ማገናኘት። 2. 100% ዲጂታል የንግድ ፈቃድ።'],
                'om' => ['title' => 'Tajaajila Dijitaalaa fi Wal-qunnamtii Gandaalee', 'description' => 'Gandaalee 5n faayiberiin walitti hidhuu fi tajaajila uummataa saffisiisuu.', 'objectives' => '1. Gandaalee 5n faayiberiin walqabsiisuu. 2. Tajaajila dijitaaleessuu.']
            ]
        ];

        foreach ($programs as $prg) {
            $pId = DB::table('programs')->insertGetId([
                'slug' => $prg['slug'],
                'featured_image' => $prg['featured_image'],
                'start_date' => $prg['start_date'],
                'end_date' => $prg['end_date'],
                'budget' => $prg['budget'],
                'progress_percentage' => $prg['progress_percentage'],
                'status' => $prg['status'],
                'is_published' => $prg['is_published'],
                'author_id' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('program_translations')->insert([
                ['program_id' => $pId, 'locale' => 'en', 'title' => $prg['en']['title'], 'description' => $prg['en']['description'], 'objectives' => $prg['en']['objectives']],
                ['program_id' => $pId, 'locale' => 'am', 'title' => $prg['am']['title'], 'description' => $prg['am']['description'], 'objectives' => $prg['am']['objectives']],
                ['program_id' => $pId, 'locale' => 'om', 'title' => $prg['om']['title'], 'description' => $prg['om']['description'], 'objectives' => $prg['om']['objectives']],
            ]);
        }

        // 14. Resource Filters & Resources
        $rf1 = DB::table('resource_filters')->insertGetId(['slug' => 'directives', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now]);
        DB::table('resource_filter_translations')->insert([
            ['resource_filter_id' => $rf1, 'locale' => 'en', 'name' => 'Directives & Regulations'],
            ['resource_filter_id' => $rf1, 'locale' => 'am', 'name' => 'መመሪያዎችና ደንቦች'],
            ['resource_filter_id' => $rf1, 'locale' => 'om', 'name' => 'Qajeelfamoota fi Dambii'],
        ]);

        $rf2 = DB::table('resource_filters')->insertGetId(['slug' => 'reports', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now]);
        DB::table('resource_filter_translations')->insert([
            ['resource_filter_id' => $rf2, 'locale' => 'en', 'name' => 'Annual Budget & Audit Reports'],
            ['resource_filter_id' => $rf2, 'locale' => 'am', 'name' => 'አመታዊ የበጀትና የኦዲት ሪፖርቶች'],
            ['resource_filter_id' => $rf2, 'locale' => 'om', 'name' => 'Gabaasa Baajataa fi Ooditii'],
        ]);

        $resources = [
            [
                'filter_id' => $rf1,
                'slug' => 'kombolcha-building-and-industrial-zoning-directive-2026',
                'file_path' => '/documents/kombolcha-zoning-directive-2026.pdf',
                'file_type' => 'pdf',
                'file_size' => 2484500,
                'is_published' => true,
                'en' => ['title' => 'Kombolcha Industrial & Urban Zoning Directive No. 04/2026', 'description' => 'Mandatory spatial guidelines for industrial warehouse buffers, environmental clearances, and commercial building standards.'],
                'am' => ['title' => 'የኮምቦልቻ ከተማ የኢንዱስትሪና የዞኒንግ መመሪያ ቁጥር 04/2026', 'description' => 'የመጋዘኖች አቀማመጥ፣ የአካባቢ ጥበቃ መስፈርቶችና የንግድ ህንፃዎች ግንባታ ደንብ።'],
                'om' => ['title' => 'Qajeelfama Karoora Lafaa fi Warshaalee Magaalaa Kombolchaa', 'description' => 'Ulaagaalee ijaarsa gamoo daldalaa fi warshaalee to\'achuuf bahe.']
            ],
            [
                'filter_id' => $rf2,
                'slug' => 'kombolcha-annual-budget-allocation-report-2025-2026',
                'file_path' => '/documents/kombolcha-budget-report-2026.pdf',
                'file_type' => 'pdf',
                'file_size' => 3852100,
                'is_published' => true,
                'en' => ['title' => 'Approved Annual Municipal Budget & Capital Expenditure Report', 'description' => 'Fiscal allocation across roadway drainage, water network expansion, public schooling, and primary clinics.'],
                'am' => ['title' => 'የኮምቦልቻ ከተማ አስተዳደር ዓመታዊ በጀትና የካፒታል ወጪ ሪፖርት', 'description' => 'ለመንገድና ፍሳሽ ግንባታ፣ ለንፁህ መጠጥ ውሃ መስፋፋት፣ ለትምህርትና ጤና ተቋማት የተመደበ በጀት።'],
                'om' => ['title' => 'Gabaasa Baajata Waggaa fi Baasii Bulchiinsa Magaalaa Kombolchaa', 'description' => 'Baajata daandii, bishaan dhugaatii, fayyaa fi barnootaaf ramadame.']
            ]
        ];

        foreach ($resources as $res) {
            $rId = DB::table('resources')->insertGetId([
                'slug' => $res['slug'],
                'file_path' => $res['file_path'],
                'file_type' => $res['file_type'],
                'file_size' => $res['file_size'],
                'is_published' => $res['is_published'],
                'download_count' => rand(150, 920),
                'author_id' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('resource_translations')->insert([
                ['resource_id' => $rId, 'locale' => 'en', 'title' => $res['en']['title'], 'description' => $res['en']['description']],
                ['resource_id' => $rId, 'locale' => 'am', 'title' => $res['am']['title'], 'description' => $res['am']['description']],
                ['resource_id' => $rId, 'locale' => 'om', 'title' => $res['om']['title'], 'description' => $res['om']['description']],
            ]);

            DB::table('resource_resource_filter')->insert([
                'resource_id' => $rId,
                'resource_filter_id' => $res['filter_id']
            ]);
        }

        // 15. Civic Polls
        $pollId = DB::table('polls')->insertGetId([
            'slug' => 'kombolcha-civic-priorities-neighborhood-improvement-2026',
            'start_date' => $now->subDays(5),
            'end_date' => $now->addDays(25),
            'is_active' => true,
            'is_multiple_choice' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('poll_translations')->insert([
            ['poll_id' => $pollId, 'locale' => 'en', 'question' => 'Which public infrastructure project should receive top priority in your kebele this year?', 'description' => 'Help the Kombolcha city cabinet allocate participatory municipal development funds directly.'],
            ['poll_id' => $pollId, 'locale' => 'am', 'question' => 'በዚህ በጀት ዓመት በቀበሌዎ የትኛው የልማት ስራ ቅድሚያ ሊሰጠው ይገባል ብለው ያስባሉ?', 'description' => 'የኮምቦልቻ ከተማ አስተዳደር የህዝብ ተሳትፎ በጀትን ነዋሪዎች በሚፈልጉት ቅድሚያ ላይ እንዲያውል ድምጽዎን ይስጡ።'],
            ['poll_id' => $pollId, 'locale' => 'om', 'question' => 'Bara baajata kana keessa naannoo keessanitti hojii misoomaa isa kamtu dursa argachuu qaba?', 'description' => 'Bulchiinsi magaalichaa baajata uummataaf barbaachisaa ta\'e irratti akka oolchu yaada kennaa.']
        ]);

        $pollOptions = [
            ['order' => 1, 'en' => 'Neighborhood asphalt roads & solar street lighting', 'am' => 'የአካባቢ የአስፋልት መንገዶችና የሶላር የመንገድ መብራቶች', 'om' => 'Daandii fi ibsaa aduu'],
            ['order' => 2, 'en' => 'Flood-resilient stone masonry drainage channels', 'am' => 'የጎርፍ መከላከያ የድንጋይ ሜሶነሪ የፍሳሽ ቦዮች', 'om' => 'Ujummoo lolaa ittisu'],
            ['order' => 3, 'en' => 'Piped drinking water distribution network overhaul', 'am' => 'የንፁህ መጠጥ ውሃ መስመሮች ጥገናና አዳዲስ ዝርጋታዎች', 'om' => 'Bishaan dhugaatii qulqulluu'],
            ['order' => 4, 'en' => 'Youth vocational training & digital innovation hubs', 'am' => 'የወጣቶች ሙያ ስልጠናና የቴክኖሎጂ ማዕከላት', 'om' => 'Wiirtuu leenjii dargaggootaa'],
        ];

        foreach ($pollOptions as $opt) {
            $poId = DB::table('poll_options')->insertGetId([
                'poll_id' => $pollId,
                'sort_order' => $opt['order'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('poll_option_translations')->insert([
                ['poll_option_id' => $poId, 'locale' => 'en', 'text' => $opt['en']],
                ['poll_option_id' => $poId, 'locale' => 'am', 'text' => $opt['am']],
                ['poll_option_id' => $poId, 'locale' => 'om', 'text' => $opt['om']],
            ]);
        }

        // 16. Forum Categories & Topics
        $fCatId = DB::table('forum_categories')->insertGetId([
            'slug' => 'kombolcha-civic-engagement-and-feedback',
            'sort_order' => 1,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('forum_category_translations')->insert([
            ['forum_category_id' => $fCatId, 'locale' => 'en', 'name' => 'Civic Engagement & Ideas', 'description' => 'Share citizen solutions, recommendations, and public suggestions for Kombolcha'],
            ['forum_category_id' => $fCatId, 'locale' => 'am', 'name' => 'የህዝብ ተሳትፎና የልማት ሀሳቦች', 'description' => 'ለኮምቦልቻ ከተማ እድገት የሚረዱ ሀሳቦችንና የመፍትሄ ጥቆማዎችን ያጋሩ'],
            ['forum_category_id' => $fCatId, 'locale' => 'om', 'name' => 'Hirmaannaa Uummataa fi Yaada', 'description' => 'Misooma Kombolchaaf yaada qabdan uummataaf qoodaa'],
        ]);

        $topicId = DB::table('forum_topics')->insertGetId([
            'forum_category_id' => $fCatId,
            'user_id' => $adminId,
            'slug' => 'traffic-safety-near-industrial-corridor-crossings',
            'views_count' => 420,
            'is_pinned' => true,
            'is_locked' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('forum_topic_translations')->insert([
            ['forum_topic_id' => $topicId, 'locale' => 'en', 'title' => 'Ideas for improving pedestrian and heavy truck traffic flow along the dry port road', 'content' => 'With the influx of cargo logistics, creating dedicated truck lanes and illuminated pedestrian crossings near factory shifts will protect workers.'],
            ['forum_topic_id' => $topicId, 'locale' => 'am', 'title' => 'በደረቅ ወደቡ አቅጣጫ የከባድ መኪናዎችና የእግረኞች እንቅስቃሴን ስለማስተካከል', 'content' => 'የጭነት መኪናዎች መተላለፊያ መስመሮችና የፈረቃ ሰራተኞች የሚሻገሩባቸው መብራቶች መኖራቸው የሰራተኞችን ደህንነት ያስጠብቃል።'],
            ['forum_topic_id' => $topicId, 'locale' => 'om', 'title' => 'Nageenya tiraafikaa daandii buufata gogaa irratti', 'content' => 'Nageenya miiltootaa fi geejjiba konkolaattota gurguddoo qindeessuuf mari\'achuun barbaachisaadha.']
        ]);

        // 17. Social Media Links
        $socials = [
            ['platform' => 'facebook', 'url' => 'https://facebook.com/kombolchacity', 'icon' => 'facebook', 'sort_order' => 1, 'is_active' => true],
            ['platform' => 'twitter', 'url' => 'https://x.com/kombolchacity', 'icon' => 'twitter', 'sort_order' => 2, 'is_active' => true],
            ['platform' => 'telegram', 'url' => 'https://t.me/kombolchacity', 'icon' => 'send', 'sort_order' => 3, 'is_active' => true],
            ['platform' => 'youtube', 'url' => 'https://youtube.com/@kombolchacity', 'icon' => 'youtube', 'sort_order' => 4, 'is_active' => true],
        ];

        foreach ($socials as $s) {
            DB::table('social_media_links')->insert(array_merge($s, ['created_at' => $now, 'updated_at' => $now]));
        }

        // 18. About Page
        $aboutPageId = DB::table('pages')->insertGetId([
            'slug' => 'about-kombolcha',
            'type' => 'about',
            'featured_image' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1200&q=80',
            'is_published' => true,
            'published_at' => $now,
            'author_id' => $adminId,
            'sort_order' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('page_translations')->insert([
            ['page_id' => $aboutPageId, 'locale' => 'en', 'title' => 'About Kombolcha City Administration', 'content' => 'Located in north-central Ethiopia in the Amhara Region at an elevation of 1,842 meters, Kombolcha stands as the premier industrial, logistics, and trading heartbeat of the region. Boasting an airport, a dry port multimodal terminal, and a modern industrial eco-park, Kombolcha is dynamically evolving into an accessible, green, and digitized commercial capital.'],
            ['page_id' => $aboutPageId, 'locale' => 'am', 'title' => 'ስለ ኮምቦልቻ ከተማ አስተዳደር', 'content' => 'ኮምቦልቻ በአማራ ብሔራዊ ክልላዊ መንግሥት በደቡብ ወሎ ዞን የምትገኝ፣ የሰሜን ኢትዮጵያ ቁልፍ የኢንዱስትሪ፣ የንግድና የሎጂስቲክስ መዲና ናት። ዘመናዊ ኤርፖርት፣ ደረቅ ወደብና የኢንዱስትሪ ፓርክ ያላት ይህች ከተማ ምቹ፣ አረንጓዴና በዲጂታል ቴክኖሎጂ የተደገፈች ቀዳሚ የኢንቨስትመንት ማዕከል በመሆን በፍጥነት እያደገች ትገኛለች።'],
            ['page_id' => $aboutPageId, 'locale' => 'om', 'title' => 'Waa\'ee Bulchiinsa Magaalaa Kombolchaa', 'content' => 'Magaalaan Kombolchaa handhuura warshaa, loojistiksii fi daldalaa kaaba biyyattiiti. Buufata xiyyaaraa, buufata gogaa fi paarkii warshaa ammayyaa kan qabduudha.']
        ]);

        // 19. Contact Info
        $contactId = DB::table('contacts')->insertGetId([
            'phone' => '+251 33 551 0022',
            'email' => 'info@kombolchacity.gov.et',
            'fax' => '+251 33 551 0125',
            'location_lat' => 11.0833,
            'location_lng' => 39.7333,
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31435.1!2d39.7333!3d11.0833',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('contact_translations')->insert([
            ['contact_id' => $contactId, 'locale' => 'en', 'address' => 'Kombolcha City Hall, Main Boulevard, P.O. Box 24, Kombolcha, Ethiopia', 'office_name' => 'Kombolcha Municipal Headquarters', 'working_hours' => 'Mon - Fri: 8:30 AM - 5:30 PM'],
            ['contact_id' => $contactId, 'locale' => 'am', 'address' => 'የኮምቦልቻ ማዘጋጃ ቤት፣ ዋናው አውራ ጎዳና፣ የፖ.ሳ.ቁ 24፣ ኮምቦልቻ፣ ኢትዮጵያ', 'office_name' => 'የኮምቦልቻ ከተማ አስተዳደር ማዕከላዊ ግቢ', 'working_hours' => 'ከሰኞ - አርብ፡ 2:30 ጠዋት - 11:30 ከሰዓት'],
            ['contact_id' => $contactId, 'locale' => 'om', 'address' => 'Galma Magaalaa Kombolchaa, Lakk. Saan. 24, Kombolcha', 'office_name' => 'Waajjira Ol\'aanaa Bulchiinsa Magaalaa', 'working_hours' => 'Wiixata - Jimaata: Sa\'aatii 2:30 - 11:30']
        ]);

        // 20. Populate Searchables index for fast global search
        $searchables = [
            ['searchable_type' => 'App\\Models\\Service', 'searchable_id' => 1, 'locale' => 'en', 'title' => 'Building & Construction Permit Application', 'content' => 'Kombolcha building permit architecture engineering land', 'url' => '/services/building-construction-permit'],
            ['searchable_type' => 'App\\Models\\Service', 'searchable_id' => 1, 'locale' => 'am', 'title' => 'የህንፃና ግንባታ ፈቃድ ማመልከቻ', 'content' => 'የግንባታ ፈቃድ ፕላን አርክቴክቸር ሰነድ ማረጋገጫ ምህንድስና', 'url' => '/services/building-construction-permit'],
            ['searchable_type' => 'App\\Models\\News', 'searchable_id' => 1, 'locale' => 'en', 'title' => 'Kombolcha Industrial Park Welcomes 6 New Export Textile & Garment Manufacturers', 'content' => 'Kombolcha industrial park textile export manufacturing jobs', 'url' => '/media/news/kombolcha-industrial-park-expansion-new-investors'],
            ['searchable_type' => 'App\\Models\\News', 'searchable_id' => 1, 'locale' => 'am', 'title' => 'የኮምቦልቻ ኢንዱስትሪ ፓርክ 6 አዳዲስ የጨርቃጨርቅ አምራች ኩባንያዎችን ተቀበለ', 'content' => 'የኮምቦልቻ ኢንዱስትሪ ፓርክ ጨርቃጨርቅ ስራ እድል ወጪ ንግድ', 'url' => '/media/news/kombolcha-industrial-park-expansion-new-investors'],
            ['searchable_type' => 'App\\Models\\GovernmentDepartment', 'searchable_id' => 2, 'locale' => 'en', 'title' => 'Kebele 01 Administration', 'content' => 'Kebele 01 Teklehaimanot Qera civic office services', 'url' => '/government'],
            ['searchable_type' => 'App\\Models\\ExploreItem', 'searchable_id' => 1, 'locale' => 'en', 'title' => 'Kombolcha Industrial Park (IPDC)', 'content' => 'Kombolcha industrial park textiles manufacturing export investment', 'url' => '/explore/kombolcha-industrial-park-ipdc'],
        ];

        foreach ($searchables as $s) {
            DB::table('searchables')->insert(array_merge($s, ['created_at' => $now, 'updated_at' => $now]));
        }
    }
}
