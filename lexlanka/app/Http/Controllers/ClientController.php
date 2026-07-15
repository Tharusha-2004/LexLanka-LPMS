<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a paginated, searchable list of clients.
     */
    public function index(Request $request): View
    {
        $query = Client::withCount('legalCases')
                       ->orderBy('name');

        if ($search = $request->string('search')->trim()) {
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                  ->orWhere('nic',   'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $clients = $query->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::create($request->validated());

        return redirect()
            ->route('clients.show', $client)
            ->with('success', "Client \"{$client->name}\" was added successfully.");
    }

    /**
     * Display the client profile and their case history.
     */
    public function show(Client $client): View
    {
        $client->loadCount('legalCases');

        $cases = $client->legalCases()
                        ->with('assignedAttorney')
                        ->latest()
                        ->get();

        return view('clients.show', compact('client', 'cases'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()
            ->route('clients.show', $client)
            ->with('success', "Client \"{$client->name}\" was updated successfully.");
    }

    /**
     * Remove the specified client from storage.
     * Blocked if the client has any associated cases (cascade is handled at DB level,
     * but we surface a friendly message here before anything is deleted).
     */
    public function destroy(Client $client): RedirectResponse
    {
        $name = $client->name;

        if ($client->legalCases()->exists()) {
            return redirect()
                ->route('clients.show', $client)
                ->with('error', "Cannot delete \"{$name}\" because they have associated cases. Close all cases first.");
        }

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', "Client \"{$name}\" was deleted.");
    }
}
