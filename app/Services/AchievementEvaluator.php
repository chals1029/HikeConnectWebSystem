<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\CommunityPost;
use App\Models\HikeBooking;
use App\Models\MountainReview;
use App\Models\User;
use Illuminate\Support\Collection;

class AchievementEvaluator
{
    public function __construct(protected User $user) {}

    /**
     * @return array{
     *   completed_bookings: Collection<int, HikeBooking>,
     *   unique_mountain_ids: Collection<int, int>,
     *   completed_count: int,
     *   elevation_sum: int,
     *   community_posts_count: int,
     *   reviews_count: int,
     * }
     */
    public function buildContext(): array
    {
        $completed = HikeBooking::query()
            ->where('user_id', $this->user->id)
            ->where('status', 'completed')
            ->with('mountain')
            ->get();

        return [
            'completed_bookings' => $completed,
            'unique_mountain_ids' => $completed->pluck('mountain_id')->unique()->values(),
            'completed_count' => $completed->count(),
            'elevation_sum' => (int) $completed->sum(fn (HikeBooking $b) => $b->mountain?->elevation_meters ?? 0),
            'community_posts_count' => CommunityPost::query()->where('user_id', $this->user->id)->count(),
            'reviews_count' => MountainReview::query()->where('user_id', $this->user->id)->count(),
        ];
    }

    public function isEligible(Achievement $achievement, array $ctx): bool
    {
        $rules = $achievement->rule_json ?? [];

        return match ($achievement->rule_type) {
            'mountain_completed' => $this->hasCompletedMountainSlug($ctx['completed_bookings'], (string) ($rules['slug'] ?? '')),
            'unique_mountains' => $ctx['unique_mountain_ids']->count() >= (int) ($rules['min'] ?? PHP_INT_MAX),
            'completed_bookings' => $ctx['completed_count'] >= (int) ($rules['min'] ?? PHP_INT_MAX),
            'elevation_total' => $ctx['elevation_sum'] >= (int) ($rules['min'] ?? PHP_INT_MAX),
            'community_posts' => $ctx['community_posts_count'] >= (int) ($rules['min'] ?? PHP_INT_MAX),
            'mountain_reviews' => $ctx['reviews_count'] >= (int) ($rules['min'] ?? PHP_INT_MAX),
            default => false,
        };
    }

    /**
     * @param  Collection<int, HikeBooking>  $completed     */
    private function hasCompletedMountainSlug(Collection $completed, string $slug): bool
    {
        if ($slug === '') {
            return false;
        }

        return $completed->contains(fn (HikeBooking $b) => $b->mountain && $b->mountain->slug === $slug);
    }
}
