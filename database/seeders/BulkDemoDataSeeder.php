<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\AuditLog;
use App\Models\CommunityPost;
use App\Models\HikeBooking;
use App\Models\HikerLocation;
use App\Models\Mountain;
use App\Models\MountainReview;
use App\Models\TourGuide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class BulkDemoDataSeeder extends Seeder
{
    private const ACCOUNT_COUNT = 50;

    private const RECORDS_PER_TRANSACTION_TYPE = 100;

    private const LOCATION_RECORDS = 150;

    private const COMMUNITY_POST_RECORDS = 120;

    private const DEMO_EMAIL_PREFIX = 'demo-hiker-';

    private const TOUR_GUIDE_ACCOUNT_COUNT = 50;

    private const DEMO_GUIDE_EMAIL_PREFIX = 'demo-guide-';

    private const DEMO_GUIDE_SLUG_PREFIX = 'demo-guide-';

    public function run(): void
    {
        DB::transaction(function (): void {
            $mountains = Mountain::query()->orderBy('sort_order')->get();
            $guides = TourGuide::query()->orderBy('sort_order')->get();
            $achievements = Achievement::query()->orderBy('sort_order')->get();
            $admin = User::query()->where('role', User::ROLE_ADMIN)->first();

            if ($mountains->isEmpty() || $guides->isEmpty() || $achievements->isEmpty()) {
                throw new RuntimeException('Run HikeConnectDomainSeeder before BulkDemoDataSeeder.');
            }

            $emails = $this->demoEmails();
            $demoUserIds = User::query()->whereIn('email', $emails)->pluck('id');

            $this->deleteExistingDemoData($demoUserIds, $emails);

            $users = $this->createDemoUsers();
            $this->createDemoTourGuides($mountains);
            $guides = TourGuide::query()->orderBy('sort_order')->get();
            $bookings = $this->createBookings($users, $mountains, $guides);
            $posts = $this->createCommunityPosts($users, $mountains);
            $reviews = $this->createMountainReviews($bookings['completed']);
            $locations = $this->createHikerLocations($users, $mountains, $bookings);

            $this->createAchievementClaims($users, $achievements);
            $this->createAuditLogs($users, $admin, $bookings, $posts, $reviews, $locations);
        });
    }

    /**
     * @return array<int, string>
     */
    private function demoEmails(): array
    {
        $hikerEmails = collect(range(1, self::ACCOUNT_COUNT))
            ->map(fn (int $i): string => sprintf('%s%03d@hikeconnect.test', self::DEMO_EMAIL_PREFIX, $i))
            ->all();

        $guideEmails = collect(range(1, self::TOUR_GUIDE_ACCOUNT_COUNT))
            ->map(fn (int $i): string => sprintf('%s%03d@hikeconnect.test', self::DEMO_GUIDE_EMAIL_PREFIX, $i))
            ->all();

        return array_merge($hikerEmails, $guideEmails);
    }

    /**
     * @param  Collection<int, int>  $demoUserIds
     * @param  array<int, string>  $emails
     */
    private function deleteExistingDemoData(Collection $demoUserIds, array $emails): void
    {
        AuditLog::query()->where('description', 'like', '[Demo seed]%')->delete();
        CommunityPost::query()->where('author_name', 'like', 'Demo Hiker %')->delete();
        MountainReview::query()->where('reviewer_name', 'like', 'Demo Hiker %')->delete();

        if ($demoUserIds->isNotEmpty()) {
            DB::table('achievement_user')->whereIn('user_id', $demoUserIds)->delete();
            HikerLocation::query()->whereIn('user_id', $demoUserIds)->delete();
            MountainReview::query()->whereIn('user_id', $demoUserIds)->delete();
            CommunityPost::query()->whereIn('user_id', $demoUserIds)->delete();
            HikeBooking::query()->whereIn('user_id', $demoUserIds)->delete();
            AuditLog::query()
                ->whereIn('user_id', $demoUserIds)
                ->orWhereIn('actor_id', $demoUserIds)
                ->delete();
        }

        TourGuide::query()->where('slug', 'like', self::DEMO_GUIDE_SLUG_PREFIX.'%')->delete();
        User::query()->whereIn('email', $emails)->delete();
    }

    /**
     * @return Collection<int, User>
     */
    private function createDemoUsers(): Collection
    {
        $firstNames = [
            'Aira', 'Ben', 'Carla', 'Dino', 'Elena', 'Francis', 'Gia', 'Harvey', 'Iris', 'Jules',
            'Kara', 'Leo', 'Mika', 'Nico', 'Olivia', 'Paolo', 'Queenie', 'Rafael', 'Sofia', 'Tomas',
            'Una', 'Vince', 'Wendy', 'Xander', 'Yana', 'Zed', 'Bianca', 'Cedric', 'Dahlia', 'Enzo',
            'Fatima', 'Gelo', 'Hazel', 'Ivan', 'Joy', 'Kian', 'Lara', 'Miguel', 'Nadine', 'Oscar',
            'Patty', 'Quinn', 'Rica', 'Sam', 'Trixie', 'Uriel', 'Via', 'Waldo', 'Yumi', 'Zion',
        ];

        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Garcia', 'Mendoza', 'Torres', 'Ramos', 'Flores', 'Bautista', 'Aquino',
            'Castillo', 'Villanueva', 'Dela Cruz', 'Navarro', 'Rivera', 'Gonzales', 'Domingo', 'Mercado', 'Lim', 'Sy',
        ];

        $bios = [
            'Weekend hiker building stamina one summit at a time.',
            'Loves sunrise trails, coffee stops, and careful packing.',
            'New to hiking and excited to learn safer trail habits.',
            'Group hike regular who enjoys scenic ridge routes.',
            'Trail photographer collecting Batangas summit memories.',
        ];

        $password = Hash::make('password');

        return collect(range(1, self::ACCOUNT_COUNT))->map(function (int $i) use ($firstNames, $lastNames, $bios, $password): User {
            $firstName = $firstNames[$i - 1];
            $lastName = $lastNames[($i - 1) % count($lastNames)];

            return User::query()->create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => sprintf('09%09d', 810000000 + $i),
                'email' => sprintf('%s%03d@hikeconnect.test', self::DEMO_EMAIL_PREFIX, $i),
                'email_verified_at' => now()->subDays(($i % 20) + 1),
                'password' => $password,
                'bio' => $bios[($i - 1) % count($bios)],
                'role' => User::ROLE_HIKER,
            ]);
        })->values();
    }

    /**
     * @param  Collection<int, Mountain>  $mountains
     * @return Collection<int, TourGuide>
     */
    private function createDemoTourGuides(Collection $mountains): Collection
    {
        $firstNames = [
            'Adrian', 'Bella', 'Caloy', 'Dana', 'Emman', 'Faye', 'Gino', 'Hanna', 'Ian', 'Jessa',
            'Kobe', 'Lani', 'Marco', 'Nina', 'Owen', 'Pia', 'Ramil', 'Selene', 'Theo', 'Vera',
            'Wally', 'Ysa', 'Zach', 'Amara', 'Bryce', 'Clara', 'Diego', 'Erika', 'Felix', 'Greta',
            'Hugo', 'Isla', 'Jonas', 'Kyla', 'Luis', 'Mara', 'Noel', 'Opal', 'Pepito', 'Rhea',
            'Santi', 'Tala', 'Uly', 'Vida', 'Wilma', 'Xavi', 'Yani', 'Zara', 'Berto', 'Celine',
        ];

        $lastNames = [
            'Santiago', 'Manalo', 'Tuazon', 'Abad', 'Soriano', 'Lazaro', 'Valdez', 'Ocampo', 'Salazar', 'Padilla',
            'Rosales', 'Fernandez', 'Delos Santos', 'Marquez', 'Velasco', 'Aguilar', 'Cabrera', 'Pascual', 'Cortez', 'David',
        ];

        $specialties = [
            'Beginner Trail Coach',
            'Sunrise Hike Lead',
            'Ridge Safety Guide',
            'Family Hike Coordinator',
            'Trail Photography Guide',
            'Emergency Response Support',
            'Large Group Marshal',
            'Leave No Trace Educator',
        ];

        $statuses = ['available', 'available', 'available', 'on-hike', 'off-duty', 'unavailable'];
        $password = Hash::make('password');

        return collect(range(1, self::TOUR_GUIDE_ACCOUNT_COUNT))->map(function (int $i) use ($firstNames, $lastNames, $specialties, $statuses, $mountains, $password): TourGuide {
            $firstName = $firstNames[$i - 1];
            $lastName = $lastNames[($i - 1) % count($lastNames)];
            /** @var Mountain $mountain */
            $mountain = $mountains[($i - 1) % $mountains->count()];
            $email = sprintf('%s%03d@hikeconnect.test', self::DEMO_GUIDE_EMAIL_PREFIX, $i);
            $phone = sprintf('09%09d', 820000000 + $i);

            $user = User::query()->create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $phone,
                'email' => $email,
                'email_verified_at' => now()->subDays(($i % 30) + 1),
                'password' => $password,
                'bio' => 'Demo tour guide specializing in safe, organized hikes around '.$mountain->name.'.',
                'role' => User::ROLE_TOUR_GUIDE,
            ]);

            return TourGuide::query()->create([
                'user_id' => $user->id,
                'slug' => sprintf('%s%03d', self::DEMO_GUIDE_SLUG_PREFIX, $i),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'specialty' => $specialties[($i - 1) % count($specialties)],
                'phone' => $phone,
                'email' => $email,
                'bio' => 'Leads varied demo hikes with route briefings, pacing plans, and safety checks.',
                'experience_years' => (($i - 1) % 12) + 1,
                'status' => $statuses[($i - 1) % count($statuses)],
                'mountain_id' => $mountain->id,
                'avatar_gradient' => $this->avatarGradient($i),
                'profile_picture_path' => null,
                'sort_order' => 100 + $i,
            ]);
        })->values();
    }

    /**
     * @param  Collection<int, User>  $users
     * @param  Collection<int, Mountain>  $mountains
     * @param  Collection<int, TourGuide>  $guides
     * @return array<string, Collection<int, HikeBooking>>
     */
    private function createBookings(Collection $users, Collection $mountains, Collection $guides): array
    {
        $statuses = ['pending', 'approved', 'completed', 'cancelled', 'rejected'];
        $notes = [
            'Joining with friends and prefers a relaxed pace.',
            'Needs a short briefing for first-time hikers.',
            'Requests extra photo stops near the ridge.',
            'Bringing a small group with mixed experience levels.',
            'Prefers an early jump-off to avoid midday heat.',
            'Will bring own trekking poles and emergency kit.',
        ];

        $bookings = [];

        foreach ($statuses as $statusIndex => $status) {
            $bookings[$status] = collect();

            foreach (range(1, self::RECORDS_PER_TRANSACTION_TYPE) as $i) {
                /** @var User $user */
                $user = $users[($i + $statusIndex) % $users->count()];
                /** @var Mountain $mountain */
                $mountain = $mountains[($i + $statusIndex) % $mountains->count()];
                $guide = $this->guideForMountain($guides, $mountain, $i);
                $isCompleted = $status === 'completed';

                $booking = HikeBooking::query()->create([
                    'user_id' => $user->id,
                    'mountain_id' => $mountain->id,
                    'tour_guide_id' => $guide->id,
                    'hike_on' => $this->hikeDateForStatus($status, $i),
                    'hikers_count' => ($i % 8) + 1,
                    'notes' => $notes[($i + $statusIndex) % count($notes)],
                    'status' => $status,
                    'rating' => $isCompleted ? (($i % 5) + 1) : null,
                    'review_text' => $isCompleted ? $this->completedBookingReview($mountain, $i) : null,
                    'duration_hours' => $isCompleted ? (3.5 + (($i + $statusIndex) % 7) * 0.5) : null,
                ]);

                $bookings[$status]->push($booking);
            }
        }

        return $bookings;
    }

    /**
     * @param  Collection<int, TourGuide>  $guides
     */
    private function guideForMountain(Collection $guides, Mountain $mountain, int $offset): TourGuide
    {
        $matchingGuides = $guides->where('mountain_id', $mountain->id)->values();

        if ($matchingGuides->isNotEmpty()) {
            return $matchingGuides[$offset % $matchingGuides->count()];
        }

        return $guides[$offset % $guides->count()];
    }

    private function hikeDateForStatus(string $status, int $index): string
    {
        return match ($status) {
            'pending' => today()->addDays(($index % 45) + 1)->toDateString(),
            'approved' => today()->addDays(($index % 60) + 7)->toDateString(),
            'completed' => today()->subDays(($index % 90) + 1)->toDateString(),
            'cancelled' => today()->addDays(($index % 2 === 0 ? 1 : -1) * (($index % 30) + 1))->toDateString(),
            'rejected' => today()->subDays(($index % 45) + 3)->toDateString(),
            default => today()->toDateString(),
        };
    }

    private function completedBookingReview(Mountain $mountain, int $index): string
    {
        $summaries = [
            'Great pacing from the guide and a very smooth trail day.',
            'Clear instructions, scenic stops, and enough rest breaks for the group.',
            'The weather changed quickly, but the guide handled the route well.',
            'Loved the summit views and the safety reminders along exposed sections.',
            'A tiring but memorable climb with helpful updates before the hike.',
        ];

        return $summaries[$index % count($summaries)].' '.$mountain->name.' was worth the early start.';
    }

    /**
     * @param  Collection<int, User>  $users
     * @param  Collection<int, Mountain>  $mountains
     * @return Collection<int, CommunityPost>
     */
    private function createCommunityPosts(Collection $users, Collection $mountains): Collection
    {
        $templates = [
            'Trail check: {mountain} had clear paths today, but bring sun protection for exposed sections.',
            'Looking for joiners for {mountain}. Beginner-friendly pace and lots of photo stops.',
            'Packing reminder for {mountain}: extra water, electrolytes, and a light rain shell helped a lot.',
            'Our group enjoyed {mountain}. The guide briefing made the route feel organized and safe.',
            'Weather shifted near the summit of {mountain}, so start early and keep a backup layer ready.',
            'Family hike report: {mountain} was manageable with steady pacing and plenty of snack breaks.',
        ];

        return collect(range(1, self::COMMUNITY_POST_RECORDS))->map(function (int $i) use ($users, $mountains, $templates): CommunityPost {
            /** @var User $user */
            $user = $users[$i % $users->count()];
            /** @var Mountain $mountain */
            $mountain = $mountains[($i + 1) % $mountains->count()];
            $body = str_replace('{mountain}', $mountain->name, $templates[$i % count($templates)]);

            return CommunityPost::query()->create([
                'user_id' => $user->id,
                'author_name' => 'Demo Hiker '.$user->first_name,
                'author_initials' => mb_substr($user->first_name, 0, 1).mb_substr($user->last_name, 0, 1),
                'body' => $body,
                'mountain_id' => $mountain->id,
                'avatar_gradient' => $this->avatarGradient($i),
            ]);
        })->values();
    }

    /**
     * @param  Collection<int, HikeBooking>  $completedBookings
     * @return Collection<int, MountainReview>
     */
    private function createMountainReviews(Collection $completedBookings): Collection
    {
        $reviews = [
            'The route was rewarding and the guide kept everyone informed.',
            'Beautiful views, clear pacing, and a good balance of challenge and rest.',
            'Registration and jump-off instructions were easy to follow.',
            'Great for hikers who want scenic stops without rushing the group.',
            'The trail was tougher than expected, but the summit made it worthwhile.',
        ];

        return $completedBookings->take(self::RECORDS_PER_TRANSACTION_TYPE)
            ->values()
            ->map(function (HikeBooking $booking, int $i) use ($reviews): MountainReview {
                $user = $booking->user()->firstOrFail();

                return MountainReview::query()->create([
                    'user_id' => $user->id,
                    'reviewer_name' => 'Demo Hiker '.$user->first_name,
                    'rating' => $booking->rating ?? (($i % 5) + 1),
                    'body' => $reviews[$i % count($reviews)],
                    'mountain_id' => $booking->mountain_id,
                    'hike_booking_id' => $booking->id,
                ]);
            });
    }

    /**
     * @param  Collection<int, User>  $users
     * @param  Collection<int, Mountain>  $mountains
     * @param  array<string, Collection<int, HikeBooking>>  $bookings
     * @return Collection<int, HikerLocation>
     */
    private function createHikerLocations(Collection $users, Collection $mountains, array $bookings): Collection
    {
        $bookingPool = collect($bookings)
            ->only(['approved', 'completed', 'pending'])
            ->flatMap(fn (Collection $items): Collection => $items)
            ->values();

        return collect(range(1, self::LOCATION_RECORDS))->map(function (int $i) use ($users, $mountains, $bookingPool): HikerLocation {
            /** @var HikeBooking|null $booking */
            $booking = $bookingPool->isNotEmpty() ? $bookingPool[$i % $bookingPool->count()] : null;
            /** @var Mountain $mountain */
            $mountain = $booking?->mountain()->first() ?? $mountains[$i % $mountains->count()];
            /** @var User $user */
            $user = $booking?->user()->first() ?? $users[$i % $users->count()];
            $progress = ($i % 20) / 20;
            $jitter = (($i % 7) - 3) * 0.00012;

            return HikerLocation::query()->create([
                'user_id' => $user->id,
                'hike_booking_id' => $booking?->id,
                'mountain_id' => $mountain->id,
                'lat' => (float) $mountain->jumpoff_lat + (((float) $mountain->summit_lat - (float) $mountain->jumpoff_lat) * $progress) + $jitter,
                'lng' => (float) $mountain->jumpoff_lng + (((float) $mountain->summit_lng - (float) $mountain->jumpoff_lng) * $progress) - $jitter,
                'accuracy_m' => 8 + ($i % 18),
                'altitude_m' => max(40, $mountain->elevation_meters * (0.35 + ($progress * 0.65))),
                'speed_mps' => round(0.4 + (($i % 9) * 0.12), 2),
                'recorded_at' => now()->subMinutes($i * 9),
            ]);
        })->values();
    }

    /**
     * @param  Collection<int, User>  $users
     * @param  Collection<int, Achievement>  $achievements
     */
    private function createAchievementClaims(Collection $users, Collection $achievements): void
    {
        $now = now();
        $rows = collect(range(0, self::RECORDS_PER_TRANSACTION_TYPE - 1))->map(function (int $i) use ($users, $achievements, $now): array {
            $userIndex = $i % $users->count();
            $achievementIndex = ($userIndex + intdiv($i, $users->count())) % $achievements->count();

            return [
                'user_id' => $users[$userIndex]->id,
                'achievement_id' => $achievements[$achievementIndex]->id,
                'claimed_at' => $now->copy()->subDays(($i % 60) + 1),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        DB::table('achievement_user')->insertOrIgnore($rows);
    }

    /**
     * @param  Collection<int, User>  $users
     * @param  array<string, Collection<int, HikeBooking>>  $bookings
     * @param  Collection<int, CommunityPost>  $posts
     * @param  Collection<int, MountainReview>  $reviews
     * @param  Collection<int, HikerLocation>  $locations
     */
    private function createAuditLogs(
        Collection $users,
        ?User $admin,
        array $bookings,
        Collection $posts,
        Collection $reviews,
        Collection $locations,
    ): void {
        $actions = [
            ['action' => 'booking.created', 'entity_type' => 'HikeBooking', 'pool' => $bookings['pending'], 'actor' => 'user'],
            ['action' => 'booking.approved', 'entity_type' => 'HikeBooking', 'pool' => $bookings['approved'], 'actor' => 'admin'],
            ['action' => 'booking.completed', 'entity_type' => 'HikeBooking', 'pool' => $bookings['completed'], 'actor' => 'admin'],
            ['action' => 'booking.cancelled', 'entity_type' => 'HikeBooking', 'pool' => $bookings['cancelled'], 'actor' => 'user'],
            ['action' => 'booking.rejected', 'entity_type' => 'HikeBooking', 'pool' => $bookings['rejected'], 'actor' => 'admin'],
            ['action' => 'review.created', 'entity_type' => 'MountainReview', 'pool' => $reviews, 'actor' => 'user'],
            ['action' => 'community.post.created', 'entity_type' => 'CommunityPost', 'pool' => $posts, 'actor' => 'user'],
            ['action' => 'location.recorded', 'entity_type' => 'HikerLocation', 'pool' => $locations, 'actor' => 'user'],
        ];

        foreach ($actions as $actionIndex => $definition) {
            /** @var Collection<int, mixed> $pool */
            $pool = $definition['pool'];

            foreach (range(1, self::RECORDS_PER_TRANSACTION_TYPE) as $i) {
                $entity = $pool[($i - 1) % $pool->count()];
                $subjectUser = $this->subjectUserForEntity($entity, $users, $i);
                $actor = $definition['actor'] === 'admin' ? $admin : $subjectUser;

                AuditLog::query()->create([
                    'user_id' => $subjectUser->id,
                    'actor_id' => $actor?->id,
                    'action' => $definition['action'],
                    'entity_type' => $definition['entity_type'],
                    'entity_id' => $entity->id,
                    'description' => sprintf('[Demo seed] %s transaction #%03d for %s', $definition['action'], $i, $subjectUser->email),
                    'meta' => [
                        'seeded_by' => self::class,
                        'batch' => 'bulk-demo',
                        'transaction_type' => $definition['action'],
                        'sequence' => $i,
                        'variant' => ($i + $actionIndex) % 12,
                    ],
                    'ip_address' => sprintf('10.%d.%d.%d', ($actionIndex % 10) + 1, ($i % 250) + 1, (($i * 7) % 250) + 1),
                    'user_agent' => $i % 2 === 0 ? 'HikeConnect Demo Mobile/1.0' : 'HikeConnect Demo Browser/1.0',
                ]);
            }
        }
    }

    private function subjectUserForEntity(object $entity, Collection $users, int $offset): User
    {
        if (isset($entity->user_id)) {
            $user = User::query()->find($entity->user_id);

            if ($user) {
                return $user;
            }
        }

        return $users[$offset % $users->count()];
    }

    private function avatarGradient(int $index): string
    {
        $gradients = [
            'linear-gradient(135deg,#065f46,#10b981)',
            'linear-gradient(135deg,#7c3aed,#a78bfa)',
            'linear-gradient(135deg,#b45309,#f59e0b)',
            'linear-gradient(135deg,#0369a1,#38bdf8)',
            'linear-gradient(135deg,#be123c,#fb7185)',
        ];

        return $gradients[$index % count($gradients)];
    }
}
