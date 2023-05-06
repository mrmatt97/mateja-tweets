<?php

use Inertia\Inertia;
use App\Events\Hello;
use App\Models\Tweet;
use App\Events\TweetCreated;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return redirect()->route('register');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/broadcast', function () {
    broadcast(new Hello());
});
Route::any('/tweets', [TweetController::class, 'tweets']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tweet', TweetController::class)->only(['store', 'update', 'destroy']);
    Route::any('/tweet/{id}/like', [TweetController::class, 'like'])->name('tweet.like');
    Route::any('/tweet/{id}/unlike', [TweetController::class, 'unlike'])->name('tweet.unlike');
});

require __DIR__.'/auth.php';
