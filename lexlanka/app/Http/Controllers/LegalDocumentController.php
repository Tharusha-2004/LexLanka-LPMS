<?php

namespace App\Http\Controllers;

use App\Models\LegalCase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LegalDocumentController extends Controller
{
    /**
     * Export a PDF 'Case Brief' for a specific legal case.
     */
    public function exportCaseBrief(Request $request, $id)
    {
        $case = LegalCase::with(['client', 'assignedAttorney'])->findOrFail($id);

        $pdf = Pdf::loadView('documents.templates.case_brief', compact('case'));

        return $pdf->download('case_brief_' . $case->id . '.pdf');
    }
}
