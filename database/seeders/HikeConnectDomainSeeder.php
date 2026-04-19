<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\CommunityPost;
use App\Models\Mountain;
use App\Models\MountainReview;
use App\Models\PackingItem;
use App\Models\TourGuide;
use Illuminate\Database\Seeder;

class HikeConnectDomainSeeder extends Seeder
{
    public function run(): void
    {
        $batulao = Mountain::query()->updateOrCreate(
            ['slug' => 'batulao'],
            [
                'name' => 'Mt. Batulao',
                'short_description' => 'Famous for its rolling ridges and stunning landscapes. Perfect for beginners and seasoned hikers.',
                'location' => 'Nasugbu, Batangas',
                'difficulty' => 'Moderate',
                'rating' => 5.0,
                'image_path' => 'images/mt-batulao.jpg',
                'status' => 'open',
                'elevation_label' => '811 MASL',
                'elevation_meters' => 811,
                'duration_label' => '4-5 hours',
                'trail_type_label' => 'Ridge / Open Trail',
                'best_time_label' => 'November – May',
                'full_description' => 'Mt. Batulao is one of the most popular hiking destinations in Batangas, known for its stunning rolling ridges and panoramic views. The mountain offers multiple trail systems suitable for both beginners and experienced hikers, with well-established campsites and markers along the way. At 811 meters above sea level, the summit rewards hikers with breathtaking 360-degree views of the surrounding countryside, the coastline, and on clear days, even Taal Volcano.',
                'jumpoff_name' => 'Batulao Jumpoff',
                'jumpoff_address' => 'Batulao Trail Road, Nasugbu, Batangas',
                'jumpoff_meeting_time' => '5:00 AM',
                'jumpoff_notes' => 'Trail access starts from the mapped Batulao Jumpoff on Batulao Trail Road. Confirm current registration, parking, and guide requirements on arrival because local rules may change.',
                'jumpoff_lat' => 14.0554825,
                'jumpoff_lng' => 120.8205722,
                'summit_lat' => 14.0399434,
                'summit_lng' => 120.8023782,
                'open_meteo_lat' => 14.0450000,
                'open_meteo_lng' => 120.8044400,
                'gear' => ['Water (2L min)', 'Trail Food & Snacks', 'Sunscreen', 'Hat / Cap', 'Arm Sleeves', 'First Aid Kit', 'Headlamp'],
                'trail_plan' => [
                    ['title' => '1. Jump-off Briefing (5:00 AM)', 'body' => 'Meet your joiners, check weather and trail advisory, and do a quick warm-up before moving.'],
                    ['title' => '2. Ridge Push (6:00 AM - 8:00 AM)', 'body' => 'Slow and steady pacing through open ridges. Hydrate every 20-30 minutes to maintain energy.'],
                    ['title' => '3. Summit Window (8:00 AM - 9:00 AM)', 'body' => 'Photo stop, snack break, and cloud watch. Keep at least one spotter near trail edges.'],
                    ['title' => '4. Controlled Descent (9:30 AM onwards)', 'body' => 'Use downhill zig-zag steps on loose sections. Regroup at every key marker before continuing.'],
                ],
                'trail_gear_list' => [
                    '2L water minimum', 'Cap and arm sleeves', 'Trail snacks and electrolytes',
                    'First aid mini kit', 'Headlamp for early starts', 'Whistle and emergency contacts',
                ],
                'emergency_contact' => '0917-123-4567',
                'sort_order' => 1,
            ]
        );

        $pico = Mountain::query()->updateOrCreate(
            ['slug' => 'pico'],
            [
                'name' => 'Mt. Pico de Loro',
                'short_description' => 'Known for its iconic monolith and challenging forest trails. A breathtaking and fulfilling adventure.',
                'location' => 'Maragondon, Cavite',
                'difficulty' => 'Hard',
                'rating' => 4.8,
                'image_path' => 'images/mt-pico-de-loro.jpg',
                'status' => 'open',
                'elevation_label' => '664 MASL',
                'elevation_meters' => 664,
                'duration_label' => '5-6 hours',
                'trail_type_label' => 'Forest / Rocky',
                'best_time_label' => 'October - May',
                'full_description' => 'Mt. Pico de Loro, named after its iconic parrot beak-shaped monolith, is a challenging but immensely rewarding mountain sitting at the border of Cavite and Batangas. The trail passes through dense forest with rocky sections leading to the summit. The monolith itself offers daring climbers an unforgettable panoramic view of the West Philippine Sea and Cavite coastline. This is a must-visit for every serious hiker in the region.',
                'jumpoff_name' => 'Pico de Loro Old Jump Off',
                'jumpoff_address' => 'Pico de Loro old jump-off, Maragondon, Cavite',
                'jumpoff_meeting_time' => '5:30 AM',
                'jumpoff_notes' => 'This verified point-to-point Pico route starts from the old north jump-off and exits on the south side. Confirm access rules and arrange exit transport before hiking day.',
                'jumpoff_lat' => 14.2343636,
                'jumpoff_lng' => 120.6597593,
                'summit_lat' => 14.2140951,
                'summit_lng' => 120.6463093,
                'open_meteo_lat' => 14.2232780,
                'open_meteo_lng' => 120.6519280,
                'gear' => ['Water (3L)', 'Gloves', 'Headlamp', 'Hard Snacks', 'Rain Cover', 'First Aid Kit', 'Trekking Pole'],
                'trail_plan' => [
                    ['title' => '1. DENR registration & briefing', 'body' => 'Complete permits, meet your guide, and review safety rules before entry.'],
                    ['title' => '2. Forest approach', 'body' => 'Paced climb through shaded trails; watch for roots and loose rocks.'],
                    ['title' => '3. Summit & monolith zone', 'body' => 'Extra care near exposed sections; helmets recommended when crowded.'],
                    ['title' => '4. Descent & exit', 'body' => 'Start down early enough to clear DENR cutoff times.'],
                ],
                'trail_gear_list' => ['3L water', 'Gloves', 'Headlamp', 'Trail food', 'Rain shell', 'First aid', 'Trekking poles'],
                'emergency_contact' => '0917-123-4567',
                'sort_order' => 2,
            ]
        );

        $talamitam = Mountain::query()->updateOrCreate(
            ['slug' => 'talamitam'],
            [
                'name' => 'Mt. Talamitam',
                'short_description' => 'Known for long open trails and rolling green hills, ideal for training climbs and quick hikes.',
                'location' => 'Nasugbu, Batangas',
                'difficulty' => 'Easy',
                'rating' => 5.0,
                'image_path' => 'images/mt-talamitam.jpg',
                'status' => 'open',
                'elevation_label' => '630 MASL',
                'elevation_meters' => 630,
                'duration_label' => '3-4 hours',
                'trail_type_label' => 'Grassland / Open',
                'best_time_label' => 'November - May',
                'full_description' => 'Mt. Talamitam, often called Batulao\'s sister mountain, offers gentle slopes and expansive grasslands perfect for beginners and training hikes. The relatively short trails still deliver stunning views, and the open terrain provides incredible sunrise and sunset opportunities. It\'s an ideal choice for those new to hiking or looking for a relaxed day hike with friends and family.',
                'jumpoff_name' => 'Toong Jump-off',
                'jumpoff_address' => 'Toong trail jump-off, Nasugbu, Batangas',
                'jumpoff_meeting_time' => '5:00 AM',
                'jumpoff_notes' => 'Use the mapped Toong Jump-off as the main meetup point for the hike. Trail fees, guide setup, and parking availability can change, so confirm them on arrival.',
                'jumpoff_lat' => 14.0885028,
                'jumpoff_lng' => 120.7760430,
                'summit_lat' => 14.1078115,
                'summit_lng' => 120.7599079,
                'open_meteo_lat' => 14.0981572,
                'open_meteo_lng' => 120.7679755,
                'gear' => ['Water (1.5L)', 'Trail Snacks', 'Sun Protection', 'Light Jacket', 'First Aid Kit', 'Camera'],
                'trail_plan' => [
                    ['title' => '1. Base registration', 'body' => 'Pay fees, hydrate, and confirm weather before starting.'],
                    ['title' => '2. Grassland ascent', 'body' => 'Open sun exposure - pace yourself and reapply sunscreen.'],
                    ['title' => '3. Summit rest', 'body' => 'Short summit ridge; great for photos and snacks.'],
                    ['title' => '4. Return', 'body' => 'Descend the same way; regroup at the base.'],
                ],
                'trail_gear_list' => ['1.5L+ water', 'Sun hat', 'Snacks', 'Wind layer', 'Basic first aid'],
                'emergency_contact' => '0917-123-4567',
                'sort_order' => 3,
            ]
        );

        $guides = [
            ['slug' => 'marco', 'first_name' => 'Marco', 'last_name' => 'Santos', 'specialty' => 'Day Hikes Specialist', 'phone' => '0917-555-0101', 'experience_years' => 5, 'status' => 'available', 'mountain_id' => $batulao->id, 'avatar_gradient' => 'linear-gradient(135deg,#065f46,#10b981)', 'sort_order' => 1],
            ['slug' => 'rica', 'first_name' => 'Rica', 'last_name' => 'Mendoza', 'specialty' => 'Trail Photography', 'phone' => '0918-555-0202', 'experience_years' => 3, 'status' => 'available', 'mountain_id' => $pico->id, 'avatar_gradient' => 'linear-gradient(135deg,#7c3aed,#a78bfa)', 'sort_order' => 2],
            ['slug' => 'jun', 'first_name' => 'Jun', 'last_name' => 'Reyes', 'specialty' => 'Overnight Treks', 'phone' => '0919-555-0303', 'experience_years' => 7, 'status' => 'on-hike', 'mountain_id' => $talamitam->id, 'avatar_gradient' => 'linear-gradient(135deg,#b45309,#f59e0b)', 'sort_order' => 3],
            ['slug' => 'liza', 'first_name' => 'Liza', 'last_name' => 'Cruz', 'specialty' => 'Nature Education', 'phone' => '0920-555-0404', 'experience_years' => 4, 'status' => 'unavailable', 'mountain_id' => null, 'avatar_gradient' => 'linear-gradient(135deg,#dc2626,#f87171)', 'sort_order' => 4],
            ['slug' => 'carlo', 'first_name' => 'Carlo', 'last_name' => 'Bautista', 'specialty' => 'Group Hikes', 'phone' => '0921-555-0505', 'experience_years' => 6, 'status' => 'available', 'mountain_id' => $batulao->id, 'avatar_gradient' => 'linear-gradient(135deg,#0369a1,#38bdf8)', 'sort_order' => 5],
            ['slug' => 'anya', 'first_name' => 'Anya', 'last_name' => 'del Rosario', 'specialty' => 'Beginner Guide', 'phone' => '0922-555-0606', 'experience_years' => 2, 'status' => 'off-duty', 'mountain_id' => $talamitam->id, 'avatar_gradient' => 'linear-gradient(135deg,#64748b,#94a3b8)', 'sort_order' => 6],
        ];

        foreach ($guides as $g) {
            TourGuide::query()->updateOrCreate(['slug' => $g['slug']], $g);
        }

        $packing = [
            ['slug' => 'water', 'category' => 'Essentials', 'label' => 'Water (2L minimum)', 'sort_order' => 1],
            ['slug' => 'food', 'category' => 'Essentials', 'label' => 'Food / Trail Snacks', 'sort_order' => 2],
            ['slug' => 'firstaid', 'category' => 'Essentials', 'label' => 'First Aid Kit', 'sort_order' => 3],
            ['slug' => 'electrolytes', 'category' => 'Essentials', 'label' => 'Electrolytes / Oresol', 'sort_order' => 4],
            ['slug' => 'extraclothes', 'category' => 'Clothing', 'label' => 'Extra Clothes', 'sort_order' => 1],
            ['slug' => 'raincoat', 'category' => 'Clothing', 'label' => 'Raincoat / Poncho', 'sort_order' => 2],
            ['slug' => 'jacket', 'category' => 'Clothing', 'label' => 'Light Jacket', 'sort_order' => 3],
            ['slug' => 'hat', 'category' => 'Clothing', 'label' => 'Hat / Cap', 'sort_order' => 4],
            ['slug' => 'flashlight', 'category' => 'Equipment', 'label' => 'Flashlight / Headlamp', 'sort_order' => 1],
            ['slug' => 'powerbank', 'category' => 'Equipment', 'label' => 'Power Bank', 'sort_order' => 2],
            ['slug' => 'whistle', 'category' => 'Equipment', 'label' => 'Whistle', 'sort_order' => 3],
            ['slug' => 'trekpole', 'category' => 'Equipment', 'label' => 'Trekking Pole', 'sort_order' => 4],
            ['slug' => 'medicine', 'category' => 'Personal', 'label' => 'Personal Medicine', 'sort_order' => 1],
            ['slug' => 'sunscreen', 'category' => 'Personal', 'label' => 'Sunscreen', 'sort_order' => 2],
            ['slug' => 'repellent', 'category' => 'Personal', 'label' => 'Insect Repellent', 'sort_order' => 3],
            ['slug' => 'id', 'category' => 'Personal', 'label' => 'Valid ID / Emergency Info', 'sort_order' => 4],
        ];

        foreach ($packing as $row) {
            PackingItem::query()->updateOrCreate(['slug' => $row['slug']], $row);
        }

        CommunityPost::query()->whereNull('user_id')->whereIn('author_name', ['Jade M.', 'Ron V.', 'Kei A.'])->delete();
        MountainReview::query()->whereNull('user_id')->whereIn('reviewer_name', ['Camille T.', 'Paolo R.', 'Nica G.'])->delete();

        CommunityPost::query()->create([
            'user_id' => null,
            'author_name' => 'Jade M.',
            'author_initials' => 'JM',
            'body' => 'Just completed the Mt. Batulao sunrise hike! The view from the summit was absolutely breathtaking. Highly recommend going before 6 AM for the best experience.',
            'mountain_id' => $batulao->id,
            'avatar_gradient' => 'linear-gradient(135deg,#065f46,#10b981)',
        ]);

        CommunityPost::query()->create([
            'user_id' => null,
            'author_name' => 'Ron V.',
            'author_initials' => 'RV',
            'body' => 'Trail update: Mt. Pico de Loro monolith trail is clear. Brought my drone and got some amazing shots of the rock formation! Who\'s joining next weekend?',
            'mountain_id' => $pico->id,
            'avatar_gradient' => 'linear-gradient(135deg,#7c3aed,#a78bfa)',
        ]);

        CommunityPost::query()->create([
            'user_id' => null,
            'author_name' => 'Kei A.',
            'author_initials' => 'KA',
            'body' => 'First time hiking Mt. Talamitam with the family! The open grasslands are so beautiful, even the kids loved it. Perfect for beginners.',
            'mountain_id' => $talamitam->id,
            'avatar_gradient' => 'linear-gradient(135deg,#b45309,#f59e0b)',
        ]);

        MountainReview::query()->create([
            'user_id' => null,
            'reviewer_name' => 'Camille T.',
            'rating' => 5,
            'body' => 'Perfect sunrise mountain for first-timers. Trail was manageable and views were worth the early call time.',
            'mountain_id' => $batulao->id,
        ]);

        MountainReview::query()->create([
            'user_id' => null,
            'reviewer_name' => 'Paolo R.',
            'rating' => 4,
            'body' => 'Fun ridges and great guide support. Bring extra water because shade is limited in open sections.',
            'mountain_id' => $batulao->id,
        ]);

        MountainReview::query()->create([
            'user_id' => null,
            'reviewer_name' => 'Nica G.',
            'rating' => 5,
            'body' => 'Community updates here are very helpful. We timed our hike better and avoided crowded summit hours.',
            'mountain_id' => $pico->id,
        ]);

        $achievementRows = [
            ['slug' => 'first_batulao', 'name' => 'Ridge Runner', 'description' => 'Complete a hike on Mt. Batulao.', 'badge_icon' => 'lucide:mountain', 'rule_type' => 'mountain_completed', 'rule_json' => ['slug' => 'batulao'], 'sort_order' => 1],
            ['slug' => 'first_pico', 'name' => 'Monolith Courage', 'description' => 'Complete a hike on Mt. Pico de Loro.', 'badge_icon' => 'lucide:landmark', 'rule_type' => 'mountain_completed', 'rule_json' => ['slug' => 'pico'], 'sort_order' => 2],
            ['slug' => 'first_talamitam', 'name' => 'Grassland Wanderer', 'description' => 'Complete a hike on Mt. Talamitam.', 'badge_icon' => 'lucide:footprints', 'rule_type' => 'mountain_completed', 'rule_json' => ['slug' => 'talamitam'], 'sort_order' => 3],
            ['slug' => 'triple_peaks', 'name' => 'Triple Peak', 'description' => 'Complete hikes on 3 different mountains.', 'badge_icon' => 'lucide:layers', 'rule_type' => 'unique_mountains', 'rule_json' => ['min' => 3], 'sort_order' => 4],
            ['slug' => 'five_summers', 'name' => 'Trail Veteran', 'description' => 'Complete 5 hikes (any mountains).', 'badge_icon' => 'lucide:medal', 'rule_type' => 'completed_bookings', 'rule_json' => ['min' => 5], 'sort_order' => 5],
            ['slug' => 'sky_high', 'name' => 'Sky High', 'description' => 'Accumulate 2,000m+ total elevation from completed hikes.', 'badge_icon' => 'lucide:cloud', 'rule_type' => 'elevation_total', 'rule_json' => ['min' => 2000], 'sort_order' => 6],
            ['slug' => 'community_voice', 'name' => 'Trail Talker', 'description' => 'Post at least once in Community Groups.', 'badge_icon' => 'lucide:message-circle', 'rule_type' => 'community_posts', 'rule_json' => ['min' => 1], 'sort_order' => 7],
            ['slug' => 'critic', 'name' => 'Summit Critic', 'description' => 'Leave at least one mountain review.', 'badge_icon' => 'lucide:star', 'rule_type' => 'mountain_reviews', 'rule_json' => ['min' => 1], 'sort_order' => 8],
        ];
        foreach ($achievementRows as $row) {
            Achievement::query()->updateOrCreate(
                ['slug' => $row['slug']],
                $row
            );
        }
    }
}
