@extends('auth.main-course')

@section('head')
    <script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
@endsection

@section('contents')
    <style>
        .tox-promotion {
            display: none !important;
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
                <li class="breadcrumb-item"><a
                        href="{{ action('App\Http\Controllers\CourseController@getViewCourses') }}">Course</a></li>
                @foreach ($course as $c)
                    <li class="breadcrumb-item active">{{ $c->course_name }}</li>
                @endforeach
            </ol>
        </nav>

    </div>
    <div class="section educaiton">
        <div class="card rounded-4">
            @foreach ($course as $c)
                <div class="card-header rounded-4">
                    <div class="d-flex justify-content-between">
                        <h3 class="text-primary" id="v_course_name">{{ $c->course_name }}</h3>
                        <button id="btn_edit" class="btn btn-info text-white" data-bs-toggle="modal"
                            data-bs-target="#modalEditCourse"><i class="bi bi-pencil-square me-1"></i>Edit
                        </button>
                    </div>
                </div>
                <div class="card-body" id="v_course_description">
                    @if ($show)
                        {!! $c->description !!}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @if ($show)
        <div class="modal fade" id="modalEditCourse" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="formEditCourse" method="post" enctype="multipart/form-data">
                        @csrf
                        @foreach ($course as $c)
                            <div class="modal-header">
                                <h5 class="modal-title">Edit course</h5>
                                <input type="text" id="course_id" name="course_id" value="{{ $c->course_id }}" hidden>
                            </div>
                            <div class="modal-body row">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="bi bi-card-image me-1"></i>Background image</strong>
                                    <input type="file" id="course_img_e" name="course_img" accept="image/*" hidden>
                                    <label for="course_img_e">
                                        <a class="btn btn-primary"><i class="bi bi-plus"></i>Upload image</a>
                                    </label>
                                </div>
                                <img id="bg_img_e" src="{{ asset('uploads/course/') }}/{{ $c->course_image }}"
                                    class="img-fluid mt-1" alt="">
                                <div class="form-group mt-3 row">
                                    <div class="col-6">
                                        <label for="course_name_e"><strong><i class="bi bi-bookmark me-1"></i>Course
                                                name</strong></label>
                                        <input type="text" name="course_name" id="course_name_e" class="form-control"
                                            value="{{ $c->course_name }}">
                                    </div>
                                    <div class="col-6">
                                        <label for="course_type_e"><strong><i class="bi bi-bookmark me-1"></i>Course
                                                type</strong></label>
                                        <select name="course_type" id="course_type_e" class="form-select">
                                            @foreach ($getTypeName as $type)
                                                <option value="{{ $type->course_type_id }}"
                                                    @if ($type->course_type_id == $c->course_type_id) selected @endif>
                                                    {{ $type->type_name }}
                                                </option>
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
                        @endforeach
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalEditSection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="formEditSection" method="post" action="#">
                        @csrf
                        <div class="modal-header d-flex justify-content-between">
                            <h5 class="modal-title">Edit section</h5>
                            <div class="dropdown">
                                <button class="btn text-white" type="button" id="dropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots" style="font-size: 3vh;"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                                    <li><button class="dropdown-item" type="submit">Change</button></li>
                                    <li><a class="dropdown-item" onclick="deleteSection()">Delete</a></li>
                                    <li><a class="dropdown-item" data-bs-dismiss="modal">Close</a></li>
                                </ul>
                            </div>
                            <input type="text" id="section_id" name="section_id" hidden>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mt-3 ">
                                <label for="section_name"><strong>
                                        <i class="bi bi-bookmark me-1"></i>Section name</strong>
                                </label>
                                <input type="text" name="section_name" id="section_name" class="form-control">
                            </div>
                            <div class="form-group mt-3">
                                <label for="course_description"><strong>
                                        <i class="bi bi-card-text me-1"></i>Description</strong>
                                </label>
                                <textarea name="section_description" id="section_description_e" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" onclick="deleteSection()">Delete</button>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <script>
        @if ($show == false)
            joinCourse();

            function joinCourse() {
                Swal.fire({
                    title: 'Join course',
                    text: "Do you want to join this course?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#34a853',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Join'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ action('App\Http\Controllers\CourseController@joinCourse') }}',
                            type: 'post',
                            data: {
                                course_id: {{ $id }},
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.success) {
                                    toastr.success(data.message);
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    toastr.error(data.message);
                                }
                            }
                        });
                    }
                    else{
                        //go back to course list
                        window.location.href = '{{ action('App\Http\Controllers\CourseController@getViewCourses') }}';
                    }
                });
            }
        @endif
        @if ($show)
            $('#course_img_e').change(function() {
                var file = $(this)[0].files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#bg_img_e').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            });
            tinymce.init({
                selector: 'textarea#course_description_e',
                setup: function(editor) {
                    editor.on('init', function() {
                        @foreach ($course as $c)
                            editor.setContent(`{!! $c->description !!}`);
                        @endforeach
                    });
                },
                height: 300,
                plugins: [
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor',
                    'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media',
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
                selector: 'textarea#section_description_e',
                height: 300,
                plugins: [
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor',
                    'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media',
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

            function addsection(e) {
                e.innerHTML = '<i class="bi bi-pencil-square"></i><span>Reset</span>';
                $('#new-section').empty();
                $('#new-section').append(
                    `<div class="input-group">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Section..." id="section_name">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" data-course_id="{{ $id }}" type="button" onclick="createSection(this)"><i class="bi bi-check"></i></button>
                    </div>
                </div>
            </div>`
                );
            };
            $('#formEditCourse').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var type = 'single';
                formData.append('type', type);
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
                            toastr.success(data.message);
                            $('#modalEditCourse').modal('hide');
                            data.courses.forEach(course => {
                                $('#course_id').val(course.course_id);
                                $('#course_name_e').val(course.course_name);
                                tinymce.get('course_description_e').setContent(
                                    course.description);
                                $('#bg_img_e').attr('src',
                                    '{{ asset('uploads/course/') }}/' +
                                    course.course_image);
                                $('#course_type_e').val(course.course_type_id);
                                $('#v_course_name').html(course.course_name);
                                $('#v_course_description').html(course.description);
                                $('#course_type_e').val(course.course_type_id);
                            });
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            });

            function createSection(e) {
                var course_id = e.getAttribute('data-course_id');
                var section_name = $('#section_name').val();
                $.ajax({
                    url: '{{ action('App\Http\Controllers\CourseController@createSection') }}',
                    type: 'post',
                    data: {
                        course_id: course_id,
                        section_name: section_name,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            toastr.success(data.message);
                            loadSection();
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            };

            loadSection();

            function loadSection() {
                $.ajax({
                    url: '{{ route('course.getSection') }}',
                    type: 'post',
                    data: {
                        id: '{{ $id }}',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#sidebar').empty();
                            var temp = `<ul class="sidebar-nav" id="sidebar-nav">
                            <li class="nav-item">
                                <a class="nav-link fs-5 fw-bold "
                                    href="{{ action('App\Http\Controllers\LMSDashboardController@getView') }}">
                                    <i class="bi bi-grid"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item position-relative">
                                <a class="nav-link fs-5 fw-bold"
                                    href="{{ action('App\Http\Controllers\CourseController@getViewCourses') }}">
                                    <i class="bi bi-folder"></i>
                                    <span class="me-4">Courses</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5" href="">
                                    <i class="bi bi-bookmark"></i><span>Description</span>
                                </a>
                            </li>`;
                            data.sections.forEach(section => {
                                temp += `
                                <li class="nav-item">
                                    <button style="width: 100%;" class="nav-link fs-5" onclick="loadSectionDetail(${section.courses_section_id})">
                                        <i class="bi ${section.course_employee_id ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                                        <span class="text-start">${section.section_name}</span>
                                    </button>
                                </li>`;
                            });
                            temp += `<li class="nav-item" id="new-section">
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5" href="#" onclick="addsection(this)">
                                    <i class="bi bi-plus-square"></i><span>New section</span>
                                </a>
                            </li>
                        </ul>`;
                            $('#sidebar').append(temp);
                        }
                    }
                });
            }

            function loadSectionDetail(id) {
                $.ajax({
                    url: '{{ route('course.getSection') }}',
                    type: 'post',
                    data: {
                        id: id,
                        type: 1,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            data.sections.forEach(section => {
                                $('#v_course_name').html(section.section_name);
                                $('#v_course_description').html(section.detail);
                                $('#btn_edit').attr('data-bs-target', '#modalEditSection');
                                $('#section_id').val(section.courses_section_id);
                                $('#section_name').val(section.section_name);
                                tinymce.get('section_description_e').setContent(section
                                    .detail != null ?
                                    section.detail : '');
                            });
                            loadSection();
                        }
                    }
                });
            };

            $('#formEditSection').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                if (tinymce.get('section_description_e').getContent() == '') {
                    toastr.error('Description is required');
                    return;
                }
                formData.append('section_description', tinymce.get('section_description_e')
                    .getContent());
                $.ajax({
                    url: '{{ action('App\Http\Controllers\CourseController@updateSection') }}',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            toastr.success(data.message);
                            $('#modalEditSection').modal('hide');
                            data.sections.forEach(section => {
                                $('#v_course_name').html(section.section_name);
                                $('#v_course_description').html(section.detail);
                                $('#section_name').val(section.section_name);
                                tinymce.get('section_description_e').setContent(
                                    section.detail);
                            });
                            loadSection();
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            });

            function deleteSection() {
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
                            url: '{{ action('App\Http\Controllers\CourseController@deleteSection') }}',
                            type: 'delete',
                            data: {
                                section_id: $('#section_id').val(),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.success) {
                                    toastr.success(data.message);
                                    $('#modalEditSection').modal('hide');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    toastr.error(data.message);
                                }
                            }
                        });
                    }
                });
            }
        @endif
    </script>
@endsection
