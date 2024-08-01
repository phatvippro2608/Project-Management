@extends('auth.main')

@section('contents')

<div class="pagetitle">
    <h1>Disciplinaries</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Disciplinaries</li>
        </ol>
    </nav>
</div>


@endsection

@section('script')

@endsection
