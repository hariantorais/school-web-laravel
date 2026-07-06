<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
});

Volt::route('/posts/{slug}', 'home.post-detail')->name('home.post-detail');

Volt::route('/posts', 'home.posts');
Volt::route('/donasi', 'home.donations.index')->name('home.donations.index');
Volt::route('/donasi/{slug}', 'home.donations.detail')->name('home.donations.detail');
Volt::route('/profil', 'home.profil')->name('home.profil');

Route::middleware('guest')->group(function () {
    // Rute Halaman Login Utama
    Volt::route('/login', 'auth.login')->name('login');
});


// 2. RUTE KHUSUS ADMIN/GURU (AUTH PROTECTED) - Wajib login dulu
Route::middleware('auth')->group(function () {

    // Kelompok Rute Postingan Sekolah

    Volt::route('/admin/dashboard', 'admin.dashboard.index')->name('admin.dashboard.index');


    Volt::route('/admin/posts', 'admin.posts.index')->name('admin.posts.index');
    Volt::route('/admin/posts/create', 'admin.posts.form')->name('admin.posts.create');
    Volt::route('/admin/posts/{slug}/edit', 'admin.posts.form')->name('admin.posts.edit');

    Volt::route('/admin/testimonials', 'admin.testimonials.index')->name('admin.testimonials.index');
    Volt::route('/admin/users', 'admin.users.index')->name('admin.users.index');

    Volt::route('/admin/settings', 'admin.institution.form')->name('admin.institution.form');

    // Volt::route('/admin/donations', 'admin.donations.index')->name('admin.donations.index');

    Volt::route('/admin/donations/transactions', 'admin.donations.transactions')->name('admin.donations.transactions');

    // Handler Aksi Prosedural Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
