<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Scan Result</title>
    <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #f5f6fa; }
        .result-card { max-width: 420px; width: 100%; text-align: center; padding: 40px; }
        .icon { font-size: 64px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="card result-card">
        <div class="card-body">
            @if($success)
                <div class="icon text-success">&#10004;</div>
                <h4 class="text-success">{{ $message }}</h4>
                @if(isset($employee))
                    <p class="mt-3">Employee: <strong>{{ $employee }}</strong></p>
                @endif
                @if(isset($time))
                    <p>Time: <strong>{{ $time }}</strong></p>
                @endif
            @else
                <div class="icon text-danger">&#10006;</div>
                <h4 class="text-danger">{{ $message }}</h4>
            @endif
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
