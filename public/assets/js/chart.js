$(document).ready(function() {

	// Only render on pages that actually have the chart containers
	// (this file is loaded globally from the master layout).
	if (!document.getElementById('bar-charts') || !document.getElementById('line-charts')) {
		return;
	}

	var hrData = window.dashboardChartData;

	// -------------------- Bar Chart: Employees by Department --------------------
	var barData;
	if (hrData && hrData.departmentLabels && hrData.departmentLabels.length) {
		barData = hrData.departmentLabels.map(function (label, i) {
			return { department: label, employees: hrData.departmentData[i] || 0 };
		});
	} else {
		barData = [{ department: 'No Data', employees: 0 }];
	}

	Morris.Bar({
		element: 'bar-charts',
		data: barData,
		xkey: 'department',
		ykeys: ['employees'],
		labels: ['Employees'],
		barColors: ['#f43b48'],
		resize: true,
		redraw: true
	});

	// -------------------- Line Chart: Attendance - Last 7 Days --------------------
	var lineData;
	if (hrData && hrData.attendanceLabels && hrData.attendanceLabels.length) {
		lineData = hrData.attendanceLabels.map(function (label, i) {
			return {
				day: label,
				present: hrData.attendancePresent[i] || 0,
				absent: hrData.attendanceAbsent[i] || 0
			};
		});
	} else {
		lineData = [{ day: 'No Data', present: 0, absent: 0 }];
	}

	Morris.Line({
		element: 'line-charts',
		data: lineData,
		xkey: 'day',
		ykeys: ['present', 'absent'],
		labels: ['Present', 'Absent'],
		lineColors: ['#28a745', '#f43b48'],
		lineWidth: '3px',
		resize: true,
		redraw: true
	});

});
