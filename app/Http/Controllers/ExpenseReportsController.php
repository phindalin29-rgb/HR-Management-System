<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ExpenseReportsController extends Controller
{
    // view page
    public function index()
    {
        return view('reports.expensereport');
    }

    // view page
    public function invoiceReports()
    {
        return view('reports.invoicereports');
    }
    
    // daily report page
    public function dailyReport()
    {
        return view('reports.dailyreports');
    }

    // leave reports page
    public function leaveReport()
    {
        $leaves = DB::table('leaves')
                ->join('users', 'users.id', '=', 'leaves.staff_id')
                ->select('leaves.*', 'users.*')
                ->get();
                
        return view('reports.leavereports', compact('leaves'));
    }
        

    /** payment report index page */
    public function paymentsReportIndex()
    {
        return view('reports.payments-reports');
    }

    /** employee-reports page */
    public function employeeReportsIndex()
    {
        return view('reports.employee-reports');
    }

    /** Payslip Reports */
    public function payslipReports()
    {
        return view('reports.payslipreports');
    }

    /** Attendance Reports */
    public function attendanceReports()
    {
        return view('reports.attendance-reports');
    }
}
