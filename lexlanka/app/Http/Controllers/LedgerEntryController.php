<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLedgerEntryRequest;
use App\Models\LedgerEntry;
use App\Models\LegalCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LedgerEntryController extends Controller
{
    public function create(): View
    {
        $cases = LegalCase::with('client')->orderBy('id', 'desc')->get();
        return view('billing.create', compact('cases'));
    }

    public function store(StoreLedgerEntryRequest $request): RedirectResponse
    {
        LedgerEntry::create([
            ...$request->validated(),
            'recorded_by' => Auth::id(),
        ]);

        return redirect()->route('billing.index')->with('success', 'Ledger entry recorded successfully.');
    }
}
