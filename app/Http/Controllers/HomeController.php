<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\department;
use App\Models\Leave;
use App\Models\Attendance;
use Carbon\Carbon;
use PDF;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    /** Main Dashboard */
    public function index()
    {
        $today = Carbon::now()->toDateString();

        // -------- Top widget stats --------
        $totalEmployees = User::count();
        $totalDepartments = department::count();
        $presentToday = Attendance::where('date', $today)->whereIn('status', ['Present', 'Late'])->count();
        $pendingLeaves = Leave::where('status', 'Pending')->count();

        // -------- Employees per department (bar chart) --------
        $departmentLabels = department::pluck('department');
        $departmentData = $departmentLabels->map(function ($dept) {
            return User::where('department', $dept)->count();
        });

        // -------- Attendance for the last 7 days (line chart) --------
        $attendanceLabels = [];
        $attendancePresent = [];
        $attendanceAbsent = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $attendanceLabels[] = $date->format('D d');
            $presentCount = Attendance::where('date', $date->toDateString())
                ->whereIn('status', ['Present', 'Late', 'Half Day'])
                ->count();
            $attendancePresent[] = $presentCount;
            $attendanceAbsent[] = max($totalEmployees - $presentCount, 0);
        }

        // -------- Leave status distribution (doughnut chart) --------
        $leaveStatuses = ['Pending', 'Approved', 'Rejected'];
        $leaveStatusData = array_map(function ($status) {
            return Leave::where('status', $status)->count();
        }, $leaveStatuses);

        // -------- Attendance method distribution (this month) --------
        $attendanceMethods = ['manual', 'qr', 'biometric'];
        $attendanceMethodData = array_map(function ($m) {
            return Attendance::whereMonth('date', Carbon::now()->month)->where('method', $m)->count();
        }, $attendanceMethods);

        $chartData = [
            'departmentLabels'  => $departmentLabels,
            'departmentData'    => $departmentData,
            'attendanceLabels'  => $attendanceLabels,
            'attendancePresent' => $attendancePresent,
            'attendanceAbsent'  => $attendanceAbsent,
            'leaveStatusLabels' => $leaveStatuses,
            'leaveStatusData'   => $leaveStatusData,
            'attendanceMethodLabels' => $attendanceMethods,
            'attendanceMethodData'   => $attendanceMethodData,
        ];

        return view('dashboard.dashboard', compact(
            'totalEmployees', 'totalDepartments', 'presentToday', 'pendingLeaves', 'chartData'
        ));
    }
    
    /** Employee Dashboard */
    public function emDashboard()
    {
        $dt        = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        return view('dashboard.emdashboard',compact('todayDate'));
    }

    /** Generate PDF */
    public function generatePDF(Request $request)
    {
        // $data = ['title' => 'Welcome to ItSolutionStuff.com'];
        // $pdf = PDF::loadView('payroll.salaryview', $data);
        // return $pdf->download('text.pdf');
        // selecting PDF view
        $pdf = PDF::loadView('payroll.salaryview');
        // download pdf file
        return $pdf->download('pdfview.pdf');
    }
}
