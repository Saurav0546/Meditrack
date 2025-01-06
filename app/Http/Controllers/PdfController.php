<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generateMedicinePDF()
    {
        // Fetch all medicines 
        $medicines = Medicine::all();

        $pdf = Pdf::loadView('pdf.medicines', compact('medicines'));
        
        return $pdf->download('medicines.pdf');
    }
}