<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.invoice.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.invoice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        // Get logo as base64 for preview
        $logoPath = public_path('assets/img/logo-black.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }
        
        return view('admin.invoice.show', [
            'invoice' => $invoice,
            'logoBase64' => $logoBase64
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return view('admin.invoice.edit', ['invoice' => $invoice]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Generate PDF for invoice
     */
    public function pdf($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        // Get logo as base64 for PDF
        $logoPath = public_path('assets/img/logo-black.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }
        
        $pdf = Pdf::loadView('admin.invoice.pdf', [
            'invoice' => $invoice,
            'logoBase64' => $logoBase64
        ]);
        
        $filename = 'Invoice_' . $invoice->order_number . '.pdf';
        
        return $pdf->download($filename);
    }
}
