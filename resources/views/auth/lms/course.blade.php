@extends('auth.main-lms')

@section('head')
<script src="{{asset('assets/js/tinymce/tinymce.min.js')}}"></script>
@endsection

@section('contents')
<style>
    .tox-promotion {
        display: none !important;
    }
    .card {
        height: 350px;
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
</style>
<div class="pagetitle">
    <h1>Course</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">LMS</a></li>
            <li class="breadcrumb-item active">Course</li>
        </ol>
    </nav>
</div>
<div class="section education">
    <div class="d-flex justify-content-between align-items-center mt-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddCourse">
            <i class="bi bi-plus me-1"></i>Add course
        </button>
        <div class="d-flex ms-auto">
            <input type="text" id="searchInput" class="form-control me-2" style="max-width: 300px;" placeholder="Search Courses">
            <select id="courseTypeFilter" class="form-select" style="max-width: 200px;">
                <option noselect value="">Fill by Type</option>
                @foreach($getTypeName as $type)
                    <option value="{{$type->type_name}}">{{$type->type_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSelectCourse">
        <i class="bi bi-pencil me-1"></i>Edit course
    </button>
    <div class="row mt-2" id="course_handel">
        @foreach($courses as $course)
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 course-card" data-type="{{ $course->type }}">
            <div class="card shadow bg-white border-none rounded-4">
                <div class="card-header m-2 p-0 text-primary">
                    <img class="border-none rounded-4" style="max-width: 100%" src="{{asset('uploads/course/'.$course->course_image)}}">
                    <h5 class="mt-2 mb-1">{{ \Illuminate\Support\Str::limit($course->course_name, 30) }}</h5>
                </div>
                <div class="card-body">
                    {!! \Illuminate\Support\Str::limit(strip_tags($course->description), 30) !!}
                </div>
            </div>
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
                    <div class="form-group mt-3">
                        <label for="course_name"><strong><i class="bi bi-bookmark me-1"></i>Course name</strong></label>
                        <input type="text" name="course_name" id="course_name" class="form-control" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="course_description"><strong><i class="bi bi-card-text me-1"></i>Description</strong></label>
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
                        @foreach($courses as $course)
                        <option value="{{$course->course_id}}">{{$course->course_name}}</option>
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
                <div class="modal-header">
                    <h5 class="modal-title">Edit course</h5>
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
                    <div class="form-group mt-3">
                        <label for="course_name_e"><strong><i class="bi bi-bookmark me-1"></i>Course name</strong></label>
                        <input type="text" name="course_name" id="course_name_e" class="form-control">
                    </div>
                    <div class="form-group mt-3">
                        <label for="course_description_e"><strong><i class="bi bi-card-text me-1"></i>Description</strong></label>
                        <textarea name="course_description" id="course_description_e" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            'advlist', 'autolink', 'link', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
            'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime',
            'table', 'emoticons', 'template', 'codesample'
        ],
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
            'bullist numlist outdent indent | link | preview fullscreen | ' +
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
        images_upload_url: false,
        images_upload_handler: function(blobInfo, success, failure) {
            failure('Image uploading is disabled');
        },
        plugins_exclude: 'print media'
    });
    tinymce.init({
        selector: 'textarea#course_description_e',
        height: 300,
        plugins: [
            'advlist', 'autolink', 'link', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
            'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime',
            'table', 'emoticons', 'template', 'codesample'
        ],
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
            'bullist numlist outdent indent | link | preview fullscreen | ' +
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
        images_upload_url: false,
        images_upload_handler: function(blobInfo, success, failure) {
            failure('Image uploading is disabled');
        },
        plugins_exclude: 'print media'
    });
    $('#formAddCourse').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        if (tinymce.get('course_description').getContent() == '') {
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
                    toastr.success(data.message);
                    $('#modalAddCourse').modal('hide');
                    //clear course_handel
                    $('#course_handel').empty();
                    //append new course
                    data.courses.forEach(course => {
                        // Remove HTML tags and limit to 30 characters
                        let plainDescription = course.description.replace(/<\/?[^>]+(>|$)/g, "").substring(0, 30);

                        $('#course_handel').append(
                            `<div class="col-xl-3 col-lg-4 col-md-4 col-sm-4">
                                <div class="card shadow bg-white border-none rounded-4">
                                <div class="card-header m-2 p-0 text-primary">
                                    <img class="border-none rounded-top-4" style="max-width: 100%" src="{{asset('uploads/course/')}}/${course.course_image}">
                                    <h5 class="mt-2 mb-1">${course.course_name}</h5>
                                </div>
                                <div class="card-body m-3 p-0">
                                    ${plainDescription}
                                </div>
                            </div>
                        </div>`
                        );
                    });
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });
    $('#formEditCourse').submit(function(e) {});

    $(document).ready(function() {
        function loadCourses() {
            var search = $('#searchInput').val();
            var typeFilter = $('#courseTypeFilter').val();
            $.ajax({
                url: '{{ route('lms.search') }}',
                type: 'GET',
                data: {
                    search: search,
                    courseType: typeFilter
                },
                success: function(data) {
                    if (data.success) {
                        var coursesHtml = '';
                        data.data.forEach(function(course) {
                            let plainDescription = course.description.replace(/<\/?[^>]+(>|$)/g, "").substring(0, 30);
                            coursesHtml += `
                                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4">
                                    <div class="card shadow bg-white border-none rounded-4">
                                        <div class="card-header m-2 p-0 text-primary">
                                            <img class="border-none rounded-4" style="max-width: 100%" src="{{ asset('uploads/course/') }}/${course.course_image}">
                                            <h5 class="mt-2 mb-1">${course.course_name}</h5>
                                        </div>
                                        <div class="card-body">
                                            ${plainDescription}
                                        </div>
                                    </div>
                                </div>`;
                        });
                        $('#course_handel').html(coursesHtml);
                    }
                }
            });
        }

        $('#searchInput, #courseTypeFilter').on('input change', function() {
            loadCourses();
        });

        // Initialize the first load
        loadCourses();
    });
</script>
@endsection
