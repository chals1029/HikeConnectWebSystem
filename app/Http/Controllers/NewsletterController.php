<?php

namespace App\Http\Controllers;

use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function __construct(protected EmailService $emailService)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'newsletter')->withInput();
        }

        $email = (string) $request->input('email');
        $sent = $this->emailService->sendNewsletterSubscription($email);

        if (! $sent) {
            return back()
                ->withErrors(['email' => 'Could not process your subscription right now. Please try again.'], 'newsletter')
                ->withInput();
        }

        return back()->with('newsletter_success', 'Thanks for subscribing!');
    }
}
