@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Roles & Permissions</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Roles</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                @foreach($roles as $role)
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ $role->name }}</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">{{ $role->description }}</p>
                                <div class="mt-3">
                                    @if($role->modules)
                                        @foreach($role->modules as $module)
                                            <span class="badge bg-primary-light mr-1 mb-1">{{ ucwords(str_replace('-', ' ', $module)) }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

@endsection
