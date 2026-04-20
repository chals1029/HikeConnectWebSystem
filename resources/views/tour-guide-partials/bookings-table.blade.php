@if ($rows->isEmpty())
    <div class="tg-empty">No bookings here yet.</div>
@else
    <div class="tg-table-wrap">
        <table class="tg-table">
            <thead>
                <tr>
                    <th>Hiker</th>
                    <th>Mountain</th>
                    <th>Date</th>
                    <th>Group</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $b)
                    <tr>
                        <td>
                            <div class="who">
                                <div class="tg-mini-avatar" style="{{ $b->user?->profile_picture_url ? 'background-image:url('.$b->user->profile_picture_url.')' : '' }}">
                                    {{ strtoupper(substr($b->user?->first_name ?? '?', 0, 1).substr($b->user?->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="tg-who-name">{{ $b->user?->full_name ?? 'Hiker' }}</div>
                                    <div class="tg-who-sub">{{ $b->user?->phone ?? $b->user?->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $b->mountain?->name ?? '—' }}</td>
                        <td>{{ $b->hike_on->format('M j, Y') }}</td>
                        <td>{{ $b->hikers_count }}</td>
                        <td><span class="tg-status {{ $b->status }}">{{ $b->status }}</span></td>
                        <td style="text-align:right;">
                            <div class="tg-row-actions">
                                @if ($showActions === 'pending')
                                    <button class="tg-btn primary" data-booking-action="approve" data-booking-id="{{ $b->id }}">
                                        <iconify-icon icon="lucide:check"></iconify-icon> Approve
                                    </button>
                                    <button class="tg-btn danger" data-booking-action="reject" data-booking-id="{{ $b->id }}">
                                        <iconify-icon icon="lucide:x"></iconify-icon> Reject
                                    </button>
                                @elseif ($showActions === 'approved')
                                    <button class="tg-btn primary" data-booking-action="complete" data-booking-id="{{ $b->id }}">
                                        <iconify-icon icon="lucide:flag"></iconify-icon> Complete
                                    </button>
                                    <button class="tg-btn danger" data-booking-action="reject" data-booking-id="{{ $b->id }}">
                                        Cancel
                                    </button>
                                @elseif ($b->rating)
                                    <span class="tg-review-stars">
                                        {{ str_repeat('★', (int) $b->rating) }}<span class="dim">{{ str_repeat('★', 5 - (int) $b->rating) }}</span>
                                    </span>
                                @else
                                    <span class="tg-who-sub">—</span>
                                @endif
                            </div>
                            @if ($b->notes && $showActions !== 'none')
                                <div class="tg-row-note" style="text-align:left;">{{ $b->notes }}</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
