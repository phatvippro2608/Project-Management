@extends('auth.main-lms')

@section('head')
@endsection

@section('contents')
<div class="pagetitle">
    <h1>Workshops</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ action('App\Http\Controllers\LMSDashboardController@getView') }}">LMS</a></li>
            <li class="breadcrumb-item active" href="{{ action('App\Http\Controllers\WorkshopController@getViewDashboard') }}">Workshop</li>
        </ol>
    </nav>
</div>
@endsection
