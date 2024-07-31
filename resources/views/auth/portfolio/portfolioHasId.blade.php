@extends('auth.main')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        /* Tắt chọn văn bản cho một phần cụ thể */
        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Portfolio</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('portfolio') }}">Portfolio</a></li>
                <li class="breadcrumb-item active">{{ $id }}</li>
            </ol>
        </nav>
    </div>
@endsection
