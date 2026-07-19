<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourtDateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LedgerEntryController;
use App\Http\Controllers\LegalCaseController;
use App\Http\Controllers\LegalDocumentController;
use App\Models\Client;
use App\Models\CourtDate;
use App\Models\LegalCase;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest / Authentication Routes
|--------------------------------------------------------------------------
*/
// Public landing page — accessible to guests AND authenticated users
Route::get('/', function () {
    return view('welcome');
})->name('home');

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

    // Cases — full resource
    Route::resource('cases', LegalCaseController::class);
    Route::get('/cases/{id}/export-brief', [LegalDocumentController::class, 'exportCaseBrief'])->name('cases.export-brief');

    // Scheduling (CourtDates) — full resource at /scheduling
    Route::resource('scheduling', CourtDateController::class);

    // Documents — full resource
    Route::resource('documents', DocumentController::class)->except(['edit', 'update']);

    // Billing Dashboard
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');

    // Ledger Entries
    Route::resource('ledger-entries', LedgerEntryController::class)->only(['create', 'store']);

});

// Authentication routes (provided by Laravel Breeze / Jetstream / or manual below)
// If you haven't installed an auth scaffold yet, add:
//   php artisan breeze:install blade --no-interaction && npm install && npm run build

