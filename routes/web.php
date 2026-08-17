<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\StudioController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Tersembunyi untuk Autentikasi
Route::prefix('auth-portal')->middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Lupa Password
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // Reset Password
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Logout harus pakai middleware auth (hanya bisa diakses kalau sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Ruang Kerja (Profile Studio) - Harus Login
Route::middleware(['auth'])->prefix('studio')->group(function () {
    Route::get('/', [StudioController::class, 'index'])->name('studio.index');

    // Update Profil Utama
    Route::post('/home', [StudioController::class, 'updateHome'])->name('studio.home.update');
    Route::post('/about', [StudioController::class, 'updateAbout'])->name('studio.about.update');
    Route::post('/social-media', [StudioController::class, 'storeSocialMedia'])->name('studio.social-media.store');
    Route::delete('/social-media/{id}', [StudioController::class, 'destroySocialMedia'])->name('studio.social-media.destroy');
    Route::post('/social-media/sync', [StudioController::class, 'syncSocialMedia'])->name('studio.social-media.sync');
    
    // Pengalaman
    Route::post('/experience', [StudioController::class, 'storeExperience'])->name('studio.experience.store');
    Route::delete('/experience/{id}', [StudioController::class, 'destroyExperience'])->name('studio.experience.destroy');
    Route::post('/experience/sync', [StudioController::class, 'syncExperience'])->name('studio.experience.sync');
    
    // Proyek
    Route::post('/project', [StudioController::class, 'storeProject'])->name('studio.project.store');
    Route::delete('/project/{id}', [StudioController::class, 'destroyProject'])->name('studio.project.destroy');
    Route::post('/project/sync', [StudioController::class, 'syncProject'])->name('studio.project.sync');
    
    // Keahlian
    Route::post('/skill', [StudioController::class, 'storeSkill'])->name('studio.skill.store');
    Route::delete('/skill/{id}', [StudioController::class, 'destroySkill'])->name('studio.skill.destroy');
    Route::post('/skill/sync', [StudioController::class, 'syncSkill'])->name('studio.skill.sync');
    
    // Sertifikat
    Route::post('/certificate/sync', [StudioController::class, 'syncCertificate'])->name('studio.certificate.sync');
});

// Halaman Portofolio Utama (Publik)
Route::get('/{username?}', function ($username = null) {
    if ($username) {
        $user = User::with(['experiences', 'projects', 'skills', 'certificates'])->where('username', $username)->first();
    } else {
        if (auth()->check()) {
            return redirect('/' . auth()->user()->username);
        }
        $user = User::with(['experiences', 'projects', 'skills', 'certificates'])->first();
    }
    
    if (!$user) {
        return 'Portofolio tidak ditemukan. Silakan register dulu di /auth-portal/register';
    }
    
    return view('portfolio', compact('user'));
})->where('username', '^(?!auth-portal|studio|contact).*$');

// Contact Route
Route::post('/contact/{username}', function (\Illuminate\Http\Request $request, $username) {
    $user = User::where('username', $username)->first(); // Send to the specific user
    if (!$user) return response()->json(['success' => false, 'error' => 'No user found'], 404);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    try {
        \Illuminate\Support\Facades\Mail::to($user->email)->send(
            new \App\Mail\ContactMessage(
                $validated['name'],
                $validated['email'],
                $validated['subject'],
                $validated['message']
            )
        );
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
})->name('portfolio.contact');