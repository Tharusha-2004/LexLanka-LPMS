<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClientController;
use App\Models\Client;
use App\Models\CourtDate;
use App\Models\LegalCase;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest / Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard', [
            'totalCases'         => LegalCase::count(),
            'activeCasesCount'   => LegalCase::where('status', 'active')->count(),
            'upcomingCourtDates' => CourtDate::where('date', '>=', now())
                                             ->where('date', '<=', now()->addDays(30))
                                             ->count(),
            'todayCourtDates'    => CourtDate::whereDate('date', today())->count(),
            'activeClients'      => Client::whereHas('legalCases', fn ($q) =>
                                        $q->whereNotIn('status', ['case_closed'])
                                    )->count(),
            'totalClients'       => Client::count(),
            'recentCases'        => LegalCase::with('client', 'assignedAttorney')
                                             ->latest()->limit(5)->get(),
            'nextCourtDates'     => CourtDate::with('legalCase.client')
                                             ->where('date', '>=', now())
                                             ->orderBy('date')
                                             ->limit(5)
                                             ->get(),
        ]);
    })->name('dashboard');

    // Clients — full resource
    Route::resource('clients', ClientController::class);

    // ── Placeholder routes (replace with resource controllers later) ──
    Route::get('/cases',      fn() => 'Cases — coming soon')->name('cases.index');
    Route::get('/scheduling', fn() => 'Scheduling — coming soon')->name('scheduling.index');
    Route::get('/documents',  fn() => 'Documents — coming soon')->name('documents.index');
    Route::get('/billing',    fn() => 'Billing — coming soon')->name('billing.index');

});

// Authentication routes (provided by Laravel Breeze / Jetstream / or manual below)
// If you haven't installed an auth scaffold yet, add:
//   php artisan breeze:install blade --no-interaction && npm install && npm run build

