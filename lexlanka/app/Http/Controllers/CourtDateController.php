<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourtDateRequest;
use App\Models\CourtDate;
use App\Models\LegalCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourtDateController extends Controller
{
    /**
     * Display all court dates ordered chronologically.
     * Eager-loads legalCase → client and legalCase → assignedAttorney.
     */
    public function index(Request $request): View
    {
        $query = CourtDate::with(['legalCase.client', 'legalCase.assignedAttorney'])
                          ->orderBy('date', 'asc');

        // Optional: filter upcoming vs past
        $view = $request->query('view', 'upcoming');
        if ($view === 'upcoming') {
            $query->where('date', '>=', now());
        } elseif ($view === 'past') {
            $query->where('date', '<', now());
        }

        // Optional: filter by type
        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $courtDates = $query->paginate(20)->withQueryString();

        return view('scheduling.index', compact('courtDates', 'view'));
    }

    /**
     * Show the scheduling form.
     * Passes all active cases (with client name) for the dropdown.
     * Optionally pre-selects a case via ?case_id= query param.
     */
    public function create(Request $request): View
    {
        $cases = LegalCase::with('client')
                          ->whereNotIn('status', ['case_closed'])
                          ->orderBy('id', 'desc')
                          ->get();

        $preselectedCaseId = $request->query('case_id');

        $typeOptions = [
            'calling_date' => 'Calling Date',
            'trial_date'   => 'Trial Date',
        ];

        return view('scheduling.create', compact('cases', 'preselectedCaseId', 'typeOptions'));
    }

    /**
     * Store a new court date.
     * reminder_sent defaults to false (handled by migration default).
     */
    public function store(StoreCourtDateRequest $request): RedirectResponse
    {
        CourtDate::create([
            ...$request->validated(),
            'reminder_sent' => false,
        ]);

        return redirect()
            ->route('scheduling.index')
            ->with('success', 'Court date scheduled successfully.');
    }

    /**
     * Display a single court date's details.
     */
    public function show(CourtDate $scheduling): View
    {
        $scheduling->load('legalCase.client', 'legalCase.assignedAttorney');

        return view('scheduling.show', ['courtDate' => $scheduling]);
    }

    /**
     * Show the edit form for a court date.
     */
    public function edit(CourtDate $scheduling): View
    {
        $cases = LegalCase::with('client')
                          ->whereNotIn('status', ['case_closed'])
                          ->orderBy('id', 'desc')
                          ->get();

        $typeOptions = [
            'calling_date' => 'Calling Date',
            'trial_date'   => 'Trial Date',
        ];

        return view('scheduling.edit', [
            'courtDate'   => $scheduling,
            'cases'       => $cases,
            'typeOptions' => $typeOptions,
        ]);
    }

    /**
     * Update a court date.
     */
    public function update(StoreCourtDateRequest $request, CourtDate $scheduling): RedirectResponse
    {
        $scheduling->update($request->validated());

        return redirect()
            ->route('scheduling.index')
            ->with('success', 'Court date updated successfully.');
    }

    /**
     * Delete a court date.
     */
    public function destroy(CourtDate $scheduling): RedirectResponse
    {
        $scheduling->delete();

        return redirect()
            ->route('scheduling.index')
            ->with('success', 'Court date removed.');
    }
}
