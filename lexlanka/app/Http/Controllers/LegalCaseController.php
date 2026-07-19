<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegalCaseRequest;
use App\Models\Client;
use App\Models\LegalCase;
use App\Models\User;
use App\Notifications\CaseStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class LegalCaseController extends Controller
{
    /**
     * Display a paginated list of all cases with eager-loaded relationships.
     */
    public function index(Request $request): View
    {
        $query = LegalCase::with(['client', 'assignedAttorney'])
                          ->latest();

        // Optional status filter
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Optional search by client name or case type
        if ($search = $request->string('search')->trim()) {
            $query->where(function ($q) use ($search) {
                $q->where('case_type', 'like', "%{$search}%")
                  ->orWhereHas('client', fn ($q2) =>
                      $q2->where('name', 'like', "%{$search}%")
                  );
            });
        }

        $cases = $query->paginate(15)->withQueryString();

        $statusOptions = [
            'pending'            => 'Pending',
            'active'             => 'Active',
            'trial_scheduled'    => 'Trial Scheduled',
            'judgment_delivered' => 'Judgment Delivered',
            'case_closed'        => 'Case Closed',
        ];

        return view('cases.index', compact('cases', 'statusOptions'));
    }

    /**
     * Show the form to create a new legal case.
     */
    public function create(Request $request): View
    {
        $clients   = Client::orderBy('name')->get(['id', 'name']);
        $attorneys = User::whereIn('role', ['partner', 'associate'])
                         ->orderBy('name')
                         ->get(['id', 'name', 'role']);

        // Pre-select client if coming from clients.show page
        $preselectedClientId = $request->query('client_id');

        $statusOptions = [
            'pending'            => 'Pending',
            'active'             => 'Active',
            'trial_scheduled'    => 'Trial Scheduled',
            'judgment_delivered' => 'Judgment Delivered',
            'case_closed'        => 'Case Closed',
        ];

        return view('cases.create', compact(
            'clients',
            'attorneys',
            'statusOptions',
            'preselectedClientId'
        ));
    }

    /**
     * Store a newly created legal case.
     */
    public function store(StoreLegalCaseRequest $request): RedirectResponse
    {
        $case = LegalCase::create($request->validated());

        return redirect()
            ->route('cases.show', $case)
            ->with('success', 'Case created successfully.');
    }

    /**
     * Display a single case with full details.
     */
    public function show(LegalCase $case): View
    {
        $case->load([
            'client',
            'assignedAttorney',
            'courtDates' => fn ($q) => $q->orderBy('date'),
            'documents',
            'ledgerEntries',
        ]);

        return view('cases.show', compact('case'));
    }

    /**
     * Show the form for editing a case.
     */
    public function edit(LegalCase $case): View
    {
        $clients   = Client::orderBy('name')->get(['id', 'name']);
        $attorneys = User::whereIn('role', ['partner', 'associate'])
                         ->orderBy('name')
                         ->get(['id', 'name', 'role']);

        $statusOptions = [
            'pending'            => 'Pending',
            'active'             => 'Active',
            'trial_scheduled'    => 'Trial Scheduled',
            'judgment_delivered' => 'Judgment Delivered',
            'case_closed'        => 'Case Closed',
        ];

        return view('cases.edit', compact('case', 'clients', 'attorneys', 'statusOptions'));
    }

    /**
     * Update the specified case in storage.
     * Sends an email notification to the client when the status changes.
     */
    public function update(StoreLegalCaseRequest $request, LegalCase $case): RedirectResponse
    {
        // Capture the original status BEFORE saving
        $previousStatus = $case->status;

        $case->update($request->validated());

        // Fire notification only if status changed AND client has an email
        if ($case->status !== $previousStatus && $case->client?->email) {
            // Reload relationships so the notification has fresh data
            $case->load('client', 'assignedAttorney');

            Notification::route('mail', $case->client->email)
                        ->notify(new CaseStatusUpdated($case));
        }

        return redirect()
            ->route('cases.show', $case)
            ->with('success', 'Case updated successfully.');
    }

    /**
     * Remove the specified case from storage.
     */
    public function destroy(LegalCase $case): RedirectResponse
    {
        $case->delete();

        return redirect()
            ->route('cases.index')
            ->with('success', 'Case was deleted.');
    }
}
