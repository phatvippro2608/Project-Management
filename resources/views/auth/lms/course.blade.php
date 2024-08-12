@extends('auth.main-lms')

@section('head')
    <script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
@endsection

@section('contents')
    <style>
        .tox-promotion {
            display: none !important;
        }

        .card {
            height: 350px;
            transition: transform 0.3s ease;
        }

        .card img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .card-body {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card:hover {
            transform: scale(1.05);
        }
    </style>
    <div class="pagetitle">
        <h1>Course</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ action('App\Http\Controllers\LMSDashboardController@getView') }}">LMS</a></li>
                <li class="breadcrumb-item active">Course</li>
            </ol>
        </nav>
    </div>
    <div class="section educaiton">
        <div class="d-flex justify-content-between">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddCourse">
                    <i class="bi bi-plus me-1"></i>Add course
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSelectCourse">
                    <i class="bi bi-pencil me-1"></i>Edit course
                </button>
            </div>
            <div>
                <div class="d-flex">
                    <input type="text" id="courseNameFilter" onkeyup="search()" class="form-control me-1"
                        style="width: 50vh;" placeholder="Search by name">
                    <select id="courseTypeFilter" class="form-select" onchange="search()" style="max-width: 200px;">
                        <option noselect value="">Type</option>
                        @foreach ($getTypeName as $type)
                            <option value="{{ $type->type_name }}">{{ $type->type_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-2" id="course_handel">
            @foreach ($courses as $course)
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-3">
                    <a href="{{ url('/lms/course') }}/{{ $course->course_id }}/view">
                        <div class="card shadow bg-white border-none rounded-4">
                            <div class="card-header m-2 p-0 text-primary">
                                <img class="border-none rounded-4" style="max-width: 100%"
                                    src="{{ asset('uploads/course/' . $course->course_image) }}">
                                <h5 class="mt-2 mb-1 course_name">
                                    {{ \Illuminate\Support\Str::limit($course->course_name, 30) }}</h5>
                                <p class="text-secondary course_type">{{ $course->type_name }}</p>
                            </div>
                            <div class="card-body">
                                {!! \Illuminate\Support\Str::limit(strip_tags($course->description), 30) !!}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal fade" id="modalAddCourse" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="formAddCourse" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add new course</h5>
                    </div>
                    <div class="modal-body row">
                        <div class="d-flex justify-content-between">
                            <strong><i class="bi bi-card-image me-1"></i>Background image</strong>
                            <input type="file" id="course_img" name="course_img" accept="image/*" hidden required>
                            <label for="course_img">
                                <a class="btn btn-primary"><i class="bi bi-plus"></i>Upload image</a>
                            </label>
                        </div>
                        <img id="bg_img" src="" class="img-fluid mt-1" alt="">
                        <div class="form-group mt-3 row">
                            <div class="col-6">
                                <label for="course_name"><strong><i class="bi bi-bookmark me-1"></i>Course
                                        name</strong></label>
                                <input type="text" name="course_name" id="course_name" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label for="course_type"><strong><i class="bi bi-bookmark me-1"></i>Course
                                        type</strong></label>
                                <select name="course_type" id="course_type" class="form-select" required>
                                    @foreach ($getTypeName as $type)
                                        <option value="{{ $type->course_type_id }}">{{ $type->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="course_description"><strong><i
                                        class="bi bi-card-text me-1"></i>Description</strong></label>
                            <textarea name="course_description" id="course_description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSelectCourse" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formSelectCourse">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Select course to edit</h5>
                    </div>
                    <div class="modal-body row">
                        <select name="" id="select_data" class="form-control">
                            @foreach ($courses as $course)
                                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Select</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditCourse" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="formEditCourse" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title">Edit course</h5>
                        <div class="dropdown">
                            <button class="btn text-white" type="button" id="dropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots" style="font-size: 3vh;"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                                <li><button class="dropdown-item" type="submit">Change</button></li>
                                <li><a class="dropdown-item" onclick="deleteCourse()">Delete</a></li>
                                <li><a class="dropdown-item" data-bs-dismiss="modal">Close</a></li>
                            </ul>
                        </div>
                        <input type="text" id="course_id" name="course_id" hidden>
                    </div>
                    <div class="modal-body row">
                        <div class="d-flex justify-content-between">
                            <strong><i class="bi bi-card-image me-1"></i>Background image</strong>
                            <input type="file" id="course_img_e" name="course_img" accept="image/*" hidden>
                            <label for="course_img_e">
                                <a class="btn btn-primary"><i class="bi bi-plus"></i>Upload image</a>
                            </label>
                        </div>
                        <img id="bg_img_e" src="" class="img-fluid mt-1" alt="">
                        <div class="form-group mt-3 row">
                            <div class="col-6">
                                <label for="course_name_e"><strong><i class="bi bi-bookmark me-1"></i>Course
                                        name</strong></label>
                                <input type="text" name="course_name" id="course_name_e" class="form-control">
                            </div>
                            <div class="col-6">
                                <label for="course_type_e"><strong><i class="bi bi-bookmark me-1"></i>Course
                                        type</strong></label>
                                <select name="course_type" id="course_type_e" class="form-select">
                                    @foreach ($getTypeName as $type)
                                        <option value="{{ $type->course_type_id }}">{{ $type->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="course_description"><strong><i
                                        class="bi bi-card-text me-1"></i>Description</strong></label>
                            <textarea name="course_description" id="course_description_e" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" onclick="deleteCourse()">Delete</button>
                        <button type="submit" class="btn btn-primary">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#course_img').change(function() {
            var file = $(this)[0].files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#bg_img').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });
        $('#course_img_e').change(function() {
            var file = $(this)[0].files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#bg_img_e').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });
        tinymce.init({
            selector: 'textarea#course_description',
            height: 300,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                'table', 'emoticons', 'template', 'codesample'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons',
            menu: {
                favs: {
                    title: 'menu',
                    items: 'code visualaid | searchreplace | emoticons'
                }
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body{font-family:Helvetica,Arial,sans-serif; font-size:16px}',
            branding: false,
        });
        tinymce.init({
            selector: 'textarea#course_description_e',
            height: 300,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                'table', 'emoticons', 'template', 'codesample'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons',
            menu: {
                favs: {
                    title: 'menu',
                    items: 'code visualaid | searchreplace | emoticons'
                }
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body{font-family:Helvetica,Arial,sans-serif; font-size:16px}',
            branding: false,
        });
        $('#formAddCourse').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            if (tinymce.get('course_description').getContent() == '') {
                toastr.error('Description is required');
                return;
            }
            if (tinymce.get('course_description').getContent().includes('base64')) {
                toastr.error('Upload image is not allowed please use source image');
                return;
            }

            formData.append('course_description', tinymce.get('course_description').getContent());
            $.ajax({
                url: '{{ action('App\Http\Controllers\CourseController@create') }}',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.success) {
                        $('#formAddCourse').trigger('reset');
                        toastr.success(data.message);
                        $('#modalAddCourse').modal('hide');
                        $('#course_handel').empty();
                        $('#select_data').empty();
                        data.courses.forEach(course => {
                            let planinName = course.course_name.substring(0, 30) + '...';
                            let plainDescription = course.description.replace(/<\/?[^>]+(>|$)/g,
                                "").substring(0, 30) + '...';

                            $('#course_handel').append(
                                `<div class="col-xl-3 col-lg-3 col-md-4 col-sm-3">
                                    <div class="card shadow bg-white border-none rounded-4">
                                        <div class="card-header m-2 p-0 text-primary">
                                            <a href="{{ url('lms/course/') }}/${course.course_id}/view">
                                                <img class="border-none rounded-4" style="max-width: 100%" src="{{ asset('uploads/course/') }}/${course.course_image}">
                                                <h5 class="mt-2 mb-1 course_name">${planinName}</h5>
                                                <p class="text-secondary course_type">${course.type_name}</p>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            ${plainDescription}
                                        </div>
                                    </div>
                                </div>`
                            );
                            $('#select_data').append(`<option value="${course.course_id}">${course.course_name}</option>`);
                        });
                    } else {
                        toastr.error(data.message);
                    }
                }
            });

        });
        $('#formSelectCourse').submit(function(e) {
            e.preventDefault();
            var course_id = $('#select_data').val();
            $.ajax({
                url: '{{ url('lms/course/') }}/' + course_id,
                type: 'get',
                success: function(data) {
                    $('#course_type_e').empty();
                    data.getTypeName.forEach(type => {
                        $('#course_type_e').append(
                            `<option value="${type.course_type_id}">${type.type_name}</option>`
                        );
                    });
                    data.course.forEach(course => {
                        $('#course_id').val(course.course_id);
                        $('#course_name_e').val(course.course_name);
                        tinymce.get('course_description_e').setContent(course.description);
                        $('#bg_img_e').attr('src', '{{ asset('uploads/course/') }}/' + course
                            .course_image);
                        $('#course_type_e').val(course.course_type_id);
                    });

                    $('#modalSelectCourse').modal('hide');
                    $('#modalEditCourse').modal('show');
                }
            });
        });


        $('#formEditCourse').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            if (tinymce.get('course_description_e').getContent() == '') {
                toastr.error('Description is required');
                return;
            }
            if (tinymce.get('course_description').getContent().includes('base64')) {
                toastr.error('Upload image is not allowed please use source image');
                return;
            }
            formData.append('course_description', tinymce.get('course_description_e').getContent());
            $.ajax({
                url: '{{ action('App\Http\Controllers\CourseController@updateCourse') }}',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.success) {
                        $('#formEditCourse').trigger('reset');
                        toastr.success(data.message);
                        $('#modalEditCourse').modal('hide');
                        $('#course_handel').empty();
                        $('#select_data').empty();
                        data.courses.forEach(course => {
                            let planinName = course.course_name.substring(0, 30);
                            if (course.course_name.length > 30) {
                                planinName += '...';
                            }
                            let plainDescription = course.description.replace(/<\/?[^>]+(>|$)/g,
                                "").substring(0, 30);
                            if (course.description.replace(/<\/?[^>]+(>|$)/g, "").length > 30) {
                                plainDescription += '...';
                            }

                            $('#course_handel').append(
                                `<div class="col-xl-3 col-lg-3 col-md-4 col-sm-3">
                                    <div class="card shadow bg-white border-none rounded-4">
                                        <div class="card-header m-2 p-0 text-primary">
                                            <a href="{{ url('lms/course/') }}/${course.course_id}/view">
                                                <img class="border-none rounded-4" style="max-width: 100%" src="{{ asset('uploads/course/') }}/${course.course_image}">
                                                <h5 class="mt-2 mb-1 course_name">${planinName}</h5>
                                                <p class="text-secondary course_type">${course.type_name}</p>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            ${plainDescription}
                                        </div>
                                    </div>
                                </div>`
                            );
                            $('#select_data').append(`<option value="${course.course_id}">${course.course_name}</option>`);
                        });
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        });

        function search() {
            var courseName = $('#courseNameFilter').val();
            var courseType = $('#courseTypeFilter').val();
            $('.card').parent().parent().hide();
            $('.card').filter(function() {
                var courseNameFilter = $(this).find('.course_name').text().toLowerCase().indexOf(courseName
                    .toLowerCase()) > -1;
                var courseTypeFilter = $(this).find('.course_type').text().toLowerCase().indexOf(courseType
                    .toLowerCase()) > -1;
                return courseNameFilter && courseTypeFilter;
            }).parent().parent().show();
        }

        function deleteCourse(){
            var course_id = $('#course_id').val();
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ action('App\Http\Controllers\CourseController@deleteCourse') }}',
                        data: {
                            course_id: course_id,
                            _token: '{{ csrf_token() }}'
                        },
                        type: 'delete',
                        success: function(data) {
                            if (data.success) {
                                toastr.success(data.message);
                                $('#modalEditCourse').modal('hide');
                                $('#course_handel').empty();
                                $('#select_data').empty();
                                data.courses.forEach(course => {
                                    let planinName = course.course_name.substring(0, 30);
                                    if (course.course_name.length > 30) {
                                        planinName += '...';
                                    }
                                    let plainDescription = course.description.replace(/<\/?[^>]+(>|$)/g,
                                        "").substring(0, 30);
                                    if (course.description.replace(/<\/?[^>]+(>|$)/g, "").length > 30) {
                                        plainDescription += '...';
                                    }

                                    $('#course_handel').append(
                                        `<div class="col-xl-3 col-lg-3 col-md-4 col-sm-3">
                                            <div class="card shadow bg-white border-none rounded-4">
                                                <div class="card-header m-2 p-0 text-primary">
                                                    <a href="{{ url('lms/course/') }}/${course.course_id}/view">
                                                        <img class="border-none rounded-4" style="max-width: 100%" src="{{ asset('uploads/course/') }}/${course.course_image}">
                                                        <h5 class="mt-2 mb-1 course_name">${planinName}</h5>
                                                        <p class="text-secondary course_type">${course.type_name}</p>
                                                    </a>
                                                </div>
                                                <div class="card-body">
                                                    ${plainDescription}
                                                </div>
                                            </div>
                                        </div>`
                                    );
                                    $('#select_data').append(`<option value="${course.course_id}">${course.course_name}</option>`);
                                });
                            } else {
                                toastr.error(data.message);
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
