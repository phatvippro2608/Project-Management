@extends('auth.main-lms')

@section('head')
    <style>
        .workshop-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .card-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
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

            @if(\App\Http\Controllers\AccountController::permission() == 1)
                <div class="card-buttons">
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editWorkshopModal-{{ $workshop->workshop_id }}" onclick="event.stopPropagation();">Edit</button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editWorkshopModal-{{ $workshop->workshop_id }}" tabindex="-1" aria-labelledby="editWorkshopModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWorkshopModalLabel">Edit Workshop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="workshopTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="workshopTitle" name="workshop_title" value="{{ $workshop->workshop_title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="workshopDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="workshopDescription" name="workshop_description" rows="3" required>{{ $workshop->workshop_description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="workshopContent" class="form-label">Content</label>
                        <textarea class="form-control" id="workshopContent" name="workshop_content" rows="5" required>{{ $workshop->workshop_content }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveWorkshopChanges({{ $workshop->workshop_id }})">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function saveWorkshopChanges(workshopId) {
        const modal = document.getElementById('editWorkshopModal-' + workshopId);
        const form = modal.querySelector('form');

        const formData = new FormData(form);

        // Disable the button and show loading text
        const saveButton = modal.querySelector('.btn-primary');
        saveButton.disabled = true;
        saveButton.textContent = 'Saving...';

        fetch('/lms/workshop/' + workshopId, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                saveButton.disabled = false;
                saveButton.textContent = 'Save changes';

                if (data.success) {
                    alert('Workshop updated successfully!');
                    location.reload();  // Reload the page or update the content dynamically
                } else {
                    alert('There was an error updating the workshop.');
                }
            })
            .catch(error => {
                saveButton.disabled = false;
                saveButton.textContent = 'Save changes';
                console.error('Error:', error);
            });
    }
</script>

@endsection
