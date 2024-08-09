@extends('auth.main-lms')

@section('head')
    <style>
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
        }
        .card-container .col {
            display: flex;
        }
        .card {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Workshops</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ action('App\Http\Controllers\LMSDashboardController@getView') }}">LMS</a></li>
                <li class="breadcrumb-item active" href="{{ action('App\Http\Controllers\WorkshopController@index') }}">Workshop</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid mt-4">
        @if(\App\Http\Controllers\AccountController::permission() == 1)
            <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addWorkshopModal">Add Workshop</button>
        @endif
        <div class="row card-container">
            @foreach($workshops as $workshop)
                <div class="col-md-4 position-relative">
                    <div class="card mb-4 shadow-sm" onclick="window.location='{{ route('workshop.show', ['workshop_id' => $workshop->workshop_id]) }}'">
                        @if($workshop->workshop_image_url)
                            <img class="card-img-top" src="{{ $workshop->workshop_image_url }}" alt="Workshop Image">
                        @else
                            <img class="card-img-top" src="{{ asset('assets/img/workshop-default.jpg') }}" alt="Default Workshop Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($workshop->workshop_title, 30) }}</h5>
                            <p class="card-text">{{ Str::limit($workshop->workshop_description, 100) }}</p>
                            @if(\App\Http\Controllers\AccountController::permission() == 1)
                                <div class="card-buttons">
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editWorkshopModal-{{ $workshop->workshop_id }}" onclick="event.stopPropagation();">Edit</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Edit Workshop Modal -->
                <div class="modal fade" id="editWorkshopModal-{{ $workshop->workshop_id }}" tabindex="-1" aria-labelledby="editWorkshopModalLabel-{{ $workshop->workshop_id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editWorkshopModalLabel-{{ $workshop->workshop_id }}">Edit Workshop</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('workshop.update', ['workshop_id' => $workshop->workshop_id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="workshop_title_{{ $workshop->workshop_id }}" class="form-label">Workshop Title</label>
                                        <input type="text" class="form-control" id="workshop_title_{{ $workshop->workshop_id }}" name="workshop_title" value="{{ $workshop->workshop_title }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="workshop_description_{{ $workshop->workshop_id }}" class="form-label">Workshop Description</label>
                                        <textarea class="form-control" id="workshop_description_{{ $workshop->workshop_id }}" name="workshop_description" rows="3" required>{{ $workshop->workshop_description }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="workshop_image_{{ $workshop->workshop_id }}" class="form-label">Workshop Image</label>
                                        <input type="file" class="form-control" id="workshop_image_{{ $workshop->workshop_id }}" name="workshop_image">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add Workshop Modal -->
    <div class="modal fade" id="addWorkshopModal" tabindex="-1" aria-labelledby="addWorkshopModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWorkshopModalLabel">Add Workshop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('workshop.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="workshop_title" class="form-label">Workshop Title</label>
                            <input type="text" class="form-control" id="workshop_title" name="workshop_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="workshop_description" class="form-label">Workshop Description</label>
                            <textarea class="form-control" id="workshop_description" name="workshop_description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="workshop_img_url" class="form-label">Workshop Image URL</label>
                            <input type="text" class="form-control" id="workshop_image_url" name="workshop_image_url">
                        </div>
                        <div class="mb-3">
                            <label for="workshop_image" class="form-label">Workshop Image</label>
                            <input type="file" class="form-control" id="workshop_image" name="workshop_image">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
