@extends('auth.main-lms')

@section('head')
    <style>
        .workshop-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
    </style>
@endsection

@section('contents')
    <div class="container mt-4">
        <div class="card mb-4 shadow-sm">
            @if($workshop->workshop_image_url)
                <img class="workshop-image" src="{{ $workshop->workshop_image_url }}" alt="Workshop Image">
            @else
                <img class="workshop-image" src="{{ asset('assets/img/workshop-default.jpg') }}" alt="Default Workshop Image">
            @endif
            <div class="card-body">
                <h1 class="card-title">{{ $workshop->workshop_title }}</h1>
                <p class="card-text">{{ $workshop->workshop_description }}</p>
                <p class="card-text">{{ $workshop->workshop_content }}</p>
                <button class="btn btn-primary" onclick="window.location='{{ route('lms.live', ['workshop_id' => $workshop->workshop_id]) }}'">Click here</button>
                <a href="{{ route('workshop.index') }}" class="btn btn-primary">Back to Workshops</a>
            </div>
        </div>
    </div>
@endsection
