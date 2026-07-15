<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Models\LegalCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    /**
     * Display all documents, newest first.
     * Eager-loads legalCase and uploader.
     */
    public function index(Request $request): View
    {
        $query = Document::with(['legalCase', 'uploader'])
                         ->latest();

        // Optional category filter
        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        // Optional case filter
        if ($caseId = $request->query('case_id')) {
            $query->where('case_id', $caseId);
        }

        $documents = $query->paginate(20)->withQueryString();

        $categoryOptions = [
            'evidence'       => 'Evidence',
            'deeds'          => 'Deeds',
            'correspondence' => 'Correspondence',
        ];

        return view('documents.index', compact('documents', 'categoryOptions'));
    }

    /**
     * Show the upload form.
     */
    public function create(Request $request): View
    {
        $cases = LegalCase::with('client')
                          ->orderBy('id', 'desc')
                          ->get();

        $preselectedCaseId = $request->query('case_id');

        $categoryOptions = [
            'evidence'       => 'Evidence',
            'deeds'          => 'Deeds',
            'correspondence' => 'Correspondence',
        ];

        return view('documents.create', compact('cases', 'preselectedCaseId', 'categoryOptions'));
    }

    /**
     * Store the uploaded document.
     *
     * Flow:
     *  1. Validate via StoreDocumentRequest
     *  2. Store file to storage/app/public/documents/ via Storage facade
     *  3. Extract file extension for the file_type column
     *  4. Set uploaded_by to the authenticated user's ID
     *  5. Create the DB record and redirect
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $uploadedFile = $request->file('file');

        // Store the file; returns path like "documents/randomhash.pdf"
        $filePath = $uploadedFile->store('documents', 'public');

        // Extract extension (e.g. "pdf", "jpg", "png")
        $fileType = strtolower($uploadedFile->getClientOriginalExtension());

        Document::create([
            'case_id'     => $request->validated()['case_id'],
            'uploaded_by' => Auth::id(),
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'category'    => $request->validated()['category'],
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display a single document's metadata and a download link.
     */
    public function show(Document $document): View
    {
        $document->load('legalCase.client', 'uploader');

        return view('documents.show', compact('document'));
    }

    /**
     * Delete a document record and its stored file.
     */
    public function destroy(Document $document): RedirectResponse
    {
        // Remove the physical file from disk
        Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted.');
    }
}
