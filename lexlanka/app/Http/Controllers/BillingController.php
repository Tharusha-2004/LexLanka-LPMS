<?php

namespace App\Http\Controllers;

use App\Models\LedgerEntry;
use App\Models\LegalCase;
use App\Services\BillingService;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(BillingService $billingService): View
    {
        Gate::authorize('view-financials');

        $totalOperational = LedgerEntry::where('type', 'operational')->sum('amount');
        $totalTrust = LedgerEntry::where('type', 'trust')->sum('amount');
        $totalCases = LegalCase::count();

        // Eager load necessary relationships to avoid N+1 queries when looping
        $cases = LegalCase::with(['client', 'assignedAttorney', 'courtDates', 'ledgerEntries'])->get();

        $casesData = $cases->map(function ($case) use ($billingService) {
            return [
                'case' => $case,
                'appearance_fee' => $billingService->getAppearanceFee($case),
                'trust_balance' => $billingService->getTrustBalance($case),
                'operational_balance' => $billingService->getOperationalBalance($case),
            ];
        });

        return view('billing.dashboard', compact('totalOperational', 'totalTrust', 'totalCases', 'casesData'));
    }
}
