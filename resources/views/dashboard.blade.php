@extends('includes.app')

@section('title', 'Dashboard')

@section('content')
<!-- Add custom dashboard CSS -->
<link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">

@php
    $isAgent = Auth::user()->privilege === 'agent';
    $agent = $isAgent && Auth::user()->agent_id ? \App\Models\Agent::find(Auth::user()->agent_id) : null;
    $companyName = $agent ? $agent->company_name : null;
    $agentUrl = $agent ? $agent->unique_url : null;
@endphp

@if(Auth::check() && Auth::user()->privilege === 'agent')
    @php
        $agent = \App\Models\Agent::find(Auth::user()->agent_id);
        $agentUrl = $agent ? $agent->unique_url : null;
    @endphp
    @if($agentUrl)
        <div class="d-flex flex-column align-items-center my-4">
            <h5 class="fw-bold mb-2">Your Unique URL</h5>
            <div class="d-flex align-items-center mb-2">
                <span class="me-2">Copy your link and share it anywhere</span>
                <button class="btn btn-primary btn-sm" id="copyAgentUrlDashboard">Copy Link</button>
            </div>
            <div style="width:100%; max-width:700px;">
                <input type="text" id="agentUrlInput" class="form-control text-center" value="{{ $agentUrl }}" readonly style="background:#fafbfc; border:2px dashed #ddd; font-size:1.1em; font-weight:500; padding:18px 8px;" />
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copyAgentUrlDashboard');
            const urlInput = document.getElementById('agentUrlInput');
            if (copyBtn && urlInput) {
                copyBtn.addEventListener('click', function() {
                    urlInput.select();
                    urlInput.setSelectionRange(0, 99999);
                    navigator.clipboard.writeText(urlInput.value).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied!',
                            text: 'Agent URL copied to clipboard',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    });
                });
            }
        });
        </script>
    @endif
@endif

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <!-- <h4 class="page-title">Dashboard</h4>                             -->
                        <div class="d-flex align-items-center">
                            <!-- <a href="{{ route('user.profile') }}" class="btn btn-primary btn-sm">
                                <i class="las la-user me-1"></i> My Profile
                            </a> -->
                        </div>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div><!--end row-->
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                    <i class="fas fa-boxes fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Deliveries</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "DELIVERY"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "DELIVERY"%')
                                                ->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('services', 'like', '%"name": "DELIVERY"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('services', 'like', '%"name": "DELIVERY"%')
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/1.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-info-subtle text-info thumb-md rounded-circle">
                                    <i class="far fa-trash-alt fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Removals</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "REMOVAL"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "REMOVAL"%')
                                                ->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('services', 'like', '%"name": "REMOVAL"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('services', 'like', '%"name": "REMOVAL"%')
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/2.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-warning-subtle text-warning thumb-md rounded-circle">
                                    <i class="fas fa-arrows-alt fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Moving Service</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "MOVING SERVICES"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "MOVING SERVICES"%')
                                                ->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('services', 'like', '%"name": "MOVING SERVICES"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('services', 'like', '%"name": "MOVING SERVICES"%')
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/3.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-danger-subtle text-danger thumb-md rounded-circle">
                                    <i class="fas fa-user-astronaut fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total College Move</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthTotal = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "COLLEGE ROOM MOVE"%')
                                                ->count();
                                            $lastMonthTotal = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "COLLEGE ROOM MOVE"%')
                                                ->count();
                                        } else {
                                            $currentMonthTotal = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('services', 'like', '%"name": "COLLEGE ROOM MOVE"%')
                                                ->count();
                                            $lastMonthTotal = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('services', 'like', '%"name": "COLLEGE ROOM MOVE"%')
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthTotal > 0 ? (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthTotal }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/4.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->                    
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                    <i class="fas fa-bed fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Mattress Removal</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "MATTRESS REMOVAL"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "MATTRESS REMOVAL"%')
                                                ->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('services', 'like', '%"name": "MATTRESS REMOVAL"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('services', 'like', '%"name": "MATTRESS REMOVAL"%')
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/5.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
               
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-info-subtle text-info thumb-md rounded-circle">
                                    <i class="fas fa-boxes fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Load Board</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)->where('assigned_agent', $agent->id)->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)->where('assigned_agent', $agent->id)->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/6.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-warning-subtle text-warning thumb-md rounded-circle">
                                    <i class="fas fa-broom fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Cleaning Service</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "CLEANING SERVICES"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "CLEANING SERVICES"%')
                                                ->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('services', 'like', '%"name": "CLEANING SERVICES"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('services', 'like', '%"name": "CLEANING SERVICES"%')
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/7.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-danger-subtle text-danger thumb-md rounded-circle">
                                    <i class="fas fa-columns fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Re-Arranging Service</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "RE ARRANGING SERVICE"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "RE ARRANGING SERVICE"%')
                                                ->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('services', 'like', '%"name": "RE ARRANGING SERVICE"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('services', 'like', '%"name": "RE ARRANGING SERVICE"%')
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/8.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->                    
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                    <i class="fas fa-tshirt fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Exterminator, Washing <br>and Replacing moving blankets</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "Exterminator, Washing and Replacing Moving Blankets"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('assigned_agent', $agent->id)
                                                ->where('services', 'like', '%"name": "Exterminator, Washing and Replacing Moving Blankets"%')
                                                ->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)
                                                ->where('services', 'like', '%"name": "Exterminator, Washing and Replacing Moving Blankets"%')
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)
                                                ->where('services', 'like', '%"name": "Exterminator, Washing and Replacing Moving Blankets"%')
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/9.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-info-subtle text-info thumb-md rounded-circle">
                                    <i class="fas fa-users-cog fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Sales Reps</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\SalesRep::whereMonth('created_at', now()->month)->where('agent_id', $agent->id)->count();
                                            $lastMonthCount = \App\Models\SalesRep::whereMonth('created_at', now()->subMonth()->month)->where('agent_id', $agent->id)->count();
                                        } else {
                                            $currentMonthCount = \App\Models\SalesRep::whereMonth('created_at', now()->month)->count();
                                            $lastMonthCount = \App\Models\SalesRep::whereMonth('created_at', now()->subMonth()->month)->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/10.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-warning-subtle text-warning thumb-md rounded-circle">
                                    <i class="fas fa-headphones fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Call Center</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Lead::whereMonth('created_at', now()->month)->where('company', $agent->company_name)->count();
                                            $lastMonthCount = \App\Models\Lead::whereMonth('created_at', now()->subMonth()->month)->where('company', $agent->company_name)->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Lead::whereMonth('created_at', now()->month)->count();
                                            $lastMonthCount = \App\Models\Lead::whereMonth('created_at', now()->subMonth()->month)->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/11.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-danger-subtle text-danger thumb-md rounded-circle">
                                    <i class="fas fa-dollar-sign fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Sales</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthTotal = \App\Models\Transaction::whereMonth('created_at', now()->month)->where('assigned_agent', $agent->id)->sum('grand_total');
                                            $lastMonthTotal = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)->where('assigned_agent', $agent->id)->sum('grand_total');
                                        } else {
                                            $currentMonthTotal = \App\Models\Transaction::whereMonth('created_at', now()->month)->sum('grand_total');
                                            $lastMonthTotal = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)->sum('grand_total');
                                        }
                                        $percentageChange = $lastMonthTotal > 0 ? (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">${{ number_format($currentMonthTotal, 2) }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/12.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->                    
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                    <i class="far fa-calendar-check fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Completed Jobs</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::where('status', 'completed')
                                                ->where('assigned_agent', $agent->id)
                                                ->whereMonth('created_at', now()->month)
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::where('status', 'completed')
                                                ->where('assigned_agent', $agent->id)
                                                ->whereMonth('created_at', now()->subMonth()->month)
                                                ->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::where('status', 'completed')
                                                ->whereMonth('created_at', now()->month)
                                                ->count();
                                            $lastMonthCount = \App\Models\Transaction::where('status', 'completed')
                                                ->whereMonth('created_at', now()->subMonth()->month)
                                                ->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/13.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-info-subtle text-info thumb-md rounded-circle">
                                    <i class="fas fa-boxes fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Total Load Board</p>
                                    @php
                                        if ($isAgent && $agent) {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)->where('assigned_agent', $agent->id)->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)->where('assigned_agent', $agent->id)->count();
                                        } else {
                                            $currentMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->month)->count();
                                            $lastMonthCount = \App\Models\Transaction::whereMonth('created_at', now()->subMonth()->month)->count();
                                        }
                                        $percentageChange = $lastMonthCount > 0 ? (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;
                                        $isIncrease = $percentageChange > 0;
                                    @endphp
                                    <span class="text-{{ $isIncrease ? 'success' : 'danger' }}">{{ number_format(abs($percentageChange), 1) }}%</span>
                                    {{ $isIncrease ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">{{ $currentMonthCount }}</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/14.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-warning-subtle text-warning thumb-md rounded-circle">
                                    <i class="fas fa-clipboard-list fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Local Leads</p>
                                    <p class="mb-0 text-truncate text-muted"><span class="text-danger">0.7%</span>
                                        Decrease from last month</p>
                                </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">0</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/15.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-danger-subtle text-danger thumb-md rounded-circle">
                                    <i class="fas fa-clipboard-list fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Long Distance Leads</p>
                                    <p class="mb-0 text-truncate text-muted"><span class="text-success">2.7%</span>
                                        Increase from last month</p>
                                </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">0</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/16.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->                    
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                    <i class="far fa-clipboard fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate">
                                    <p class="text-dark mb-0 fw-semibold fs-14">Pending Leads</p>
                                    <p class="mb-0 text-truncate text-muted"><span class="text-success">8.5%</span>
                                        Increase from last month</p>
                                </div><!--end media-body-->
                            </div><!--end media-->
                            <div class="row d-flex justify-content-center">
                                <div class="col">                                        
                                    <h3 class="mt-2 mb-0 fw-bold">0</h3>
                                </div>
                                <!--end col-->
                                <div class="col align-self-center">
                                    <img src="assets/images/dashboard/17.png" alt="" class="img-fluid">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <!--end col-->                   
            </div>
            <!--end row-->



            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12">
                    <!-- Monthly Avg. Income section removed -->
                </div>
                <!--end col-->
             
        </div><!-- container -->


@endsection<style>
/* Style for chart removed */
</style>

