# Temporary script — removes hardcoded mountainData/guideData from hikers.blade.php
p = r"c:\laragon\www\HikeConnectWebSystem\resources\views\hikers.blade.php"
with open(p, "r", encoding="utf-8") as f:
    c = f.read()
start = "        // Mountain Data Store\n        const mountainData = {"
end = "\n        document.addEventListener('DOMContentLoaded', () => {"
i = c.find(start)
j = c.find(end, i)
if i < 0 or j < 0:
    raise SystemExit(f"markers not found i={i} j={j}")
newb = r"""        window.HIKER_BOOTSTRAP = @json([
            'csrf' => csrf_token(),
            'userId' => $user->id,
            'weather' => ['lat' => $weatherLat, 'lng' => $weatherLng],
            'jumpoffMarkers' => $jumpoffMarkers,
            'defaultJumpoff' => $defaultJumpoff,
            'routes' => [
                'storeBooking' => url('/hikers/bookings'),
                'storeReview' => url('/hikers/reviews'),
                'storeCommunityPost' => url('/hikers/community-posts'),
                'cancelBookingPrefix' => url('/hikers/bookings'),
            ],
        ]);

        const mountainData = @json($mountainData);
        const guideData = @json($guideData);

        let currentMountain = null;
        const ratings = { mountain: 0, guide: 0, experience: 5 };
"""
with open(p, "w", encoding="utf-8") as f:
    f.write(c[:i] + newb + c[j:])
print("ok")
