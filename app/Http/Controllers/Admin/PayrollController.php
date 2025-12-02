<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index()
    {
        return view('admin.payroll.index');
    }

    public function salaryComponents()
    {
        return view('admin.payroll.salary-components');
    }

    public function employeeSalaries()
    {
        return view('admin.payroll.employee-salaries');
    }

    public function generate()
    {
        return view('admin.payroll.generate');
    }

    public function show($id)
    {
        $payroll = Payroll::with(['user.division', 'user.jobTitle', 'details'])->findOrFail($id);
        
        // Check access - Admin can access all, user can only access their own
        if (!Auth::user()->isAdmin && $payroll->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat payroll ini.');
        }

        // Check if payroll is published or paid (user can only see published/paid payrolls)
        if (!Auth::user()->isAdmin && !in_array($payroll->status, ['published', 'paid'])) {
            abort(403, 'Payroll ini belum dipublish.');
        }

        return view('admin.payroll.show', ['payroll' => $payroll]);
    }

    public function pdf($id)
    {
        $payroll = Payroll::with(['user.division', 'user.jobTitle', 'details'])->findOrFail($id);
        
        // Check access - Admin can access all, user can only access their own
        if (!Auth::user()->isAdmin && $payroll->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat payroll ini.');
        }

        // Check if payroll is published or paid (user can only see published/paid payrolls)
        if (!Auth::user()->isAdmin && !in_array($payroll->status, ['published', 'paid'])) {
            abort(403, 'Payroll ini belum dipublish.');
        }

        // TODO: Implement PDF generation using DomPDF or similar
        // For now, return view with proper headers for PDF
        return response()
            ->view('admin.payroll.pdf', ['payroll' => $payroll])
            ->header('Content-Type', 'text/html; charset=utf-8');
    }
}
