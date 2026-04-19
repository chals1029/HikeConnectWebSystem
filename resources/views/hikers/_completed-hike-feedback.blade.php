@php
    $mountainReview = $booking->mountainReview;
    $guideReviewRating = $booking->rating;
    $guideReviewBody = (string) ($booking->review_text ?? '');
@endphp

<div class="ns-booking-feedback" data-booking-feedback="{{ $booking->id }}">
    <div class="ns-completed-feedback-grid">
        <article class="ns-feedback-panel" data-feedback-panel="mountain" data-booking-id="{{ $booking->id }}">
            <div class="ns-feedback-panel-head">
                <div>
                    <span class="ns-feedback-kicker">Mountain feedback</span>
                    <h5>{{ $booking->mountain->name }}</h5>
                    <p>Share trail conditions, pacing, and what future hikers should expect from this route.</p>
                </div>
                <span class="ns-feedback-state {{ $mountainReview ? 'submitted' : 'pending' }}" data-feedback-state>
                    {{ $mountainReview ? 'Submitted' : 'Not yet' }}
                </span>
            </div>

            <form class="ns-inline-feedback-form" data-feedback-form data-feedback-type="mountain" data-booking-id="{{ $booking->id }}" onsubmit="submitCompletedFeedback(event)">
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="rating" value="{{ $mountainReview?->rating ?? 5 }}">

                <div class="ns-inline-rating">
                    @for($score = 1; $score <= 5; $score++)
                    <button
                        type="button"
                        class="ns-inline-rating-btn {{ ($mountainReview?->rating ?? 5) === $score ? 'active' : '' }}"
                        data-value="{{ $score }}"
                        onclick="setInlineFeedbackRating(this, {{ $score }})"
                    >{{ $score }}</button>
                    @endfor
                    <span class="ns-inline-rating-label">Rate the mountain</span>
                </div>

                <textarea class="ns-form-textarea" name="body" rows="3" placeholder="What was the trail like, and what should other hikers know?">{{ $mountainReview?->body }}</textarea>

                <div class="ns-inline-feedback-actions">
                    <button type="submit" class="ns-feedback-submit" data-feedback-submit>
                        {{ $mountainReview ? 'Update mountain feedback' : 'Save mountain feedback' }}
                    </button>
                    <span class="ns-inline-feedback-message" data-feedback-message>
                        {{ $mountainReview ? 'Saved for this completed hike.' : 'Save one review for this hike.' }}
                    </span>
                </div>
            </form>
        </article>

        <article class="ns-feedback-panel" data-feedback-panel="guide" data-booking-id="{{ $booking->id }}">
            <div class="ns-feedback-panel-head">
                <div>
                    <span class="ns-feedback-kicker">Tour guide feedback</span>
                    <h5>{{ $booking->tourGuide->full_name }}</h5>
                    <p>Leave feedback about communication, pacing, trail support, and the overall guiding experience.</p>
                </div>
                <span class="ns-feedback-state {{ $guideReviewRating ? 'submitted' : 'pending' }}" data-feedback-state>
                    {{ $guideReviewRating ? 'Submitted' : 'Not yet' }}
                </span>
            </div>

            <form class="ns-inline-feedback-form" data-feedback-form data-feedback-type="guide" data-booking-id="{{ $booking->id }}" onsubmit="submitCompletedFeedback(event)">
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="rating" value="{{ $guideReviewRating ?? 5 }}">

                <div class="ns-inline-rating">
                    @for($score = 1; $score <= 5; $score++)
                    <button
                        type="button"
                        class="ns-inline-rating-btn {{ ($guideReviewRating ?? 5) === $score ? 'active' : '' }}"
                        data-value="{{ $score }}"
                        onclick="setInlineFeedbackRating(this, {{ $score }})"
                    >{{ $score }}</button>
                    @endfor
                    <span class="ns-inline-rating-label">Rate the guide</span>
                </div>

                <textarea class="ns-form-textarea" name="body" rows="3" placeholder="How did your guide handle the hike, safety, and coordination?">{{ $guideReviewBody }}</textarea>

                <div class="ns-inline-feedback-actions">
                    <button type="submit" class="ns-feedback-submit" data-feedback-submit>
                        {{ $guideReviewRating ? 'Update guide feedback' : 'Save guide feedback' }}
                    </button>
                    <span class="ns-inline-feedback-message" data-feedback-message>
                        {{ $guideReviewRating ? 'Saved for this completed hike.' : 'Save one review for this guide on this hike.' }}
                    </span>
                </div>
            </form>
        </article>
    </div>
</div>
