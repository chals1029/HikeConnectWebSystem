<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return redirect('/?auth=login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    return redirect('/')->withInput($request->only('email'))->with(
        'login_notice',
        'Sign-in is not wired to a user database yet. Hook up Auth here when you are ready.'
    );
})->name('login.attempt');

Route::get('/register', function () {
    return redirect('/?auth=register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'first_name' => ['required', 'string', 'max:100'],
        'last_name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{7,22}$/'],
        'password' => ['required', 'string', 'min:8'],
        'confirm_details' => ['accepted'],
    ], [
        'phone.regex' => 'Enter a valid phone number (7–22 digits, optional + or spaces).',
        'confirm_details.accepted' => 'Please confirm your details are correct.',
    ]);

    return redirect('/')->withInput($request->only('first_name', 'last_name', 'email', 'phone'))->with(
        'register_notice',
        'Accounts are not stored yet. Connect a users table and Auth::register when you are ready.'
    );
})->name('register.attempt');
