@extends('auth.main-course')

@section('head')
<script src="{{asset('assets/js/tinymce/tinymce.min.js')}}"></script>
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
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Education</a></li>
            <li class="breadcrumb-item"><a href="#">Course</a></li>
            @foreach($course as $c)
            <li class="breadcrumb-item active">{{ $c->course_name }}</li>
            @endforeach
        </ol>
    </nav>
</div>
<div class="section educaiton">
    <div class="card rounded-4">
        @foreach($course as $c)
        <div class="card-header rounded-4">
            <div class="d-flex justify-content-between">
                <h3 class="text-primary" id="v_course_name">{{$c->course_name}}</h3>
                <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalEditCourse"><i class="bi bi-pencil-square me-1"></i>Edit</button>
            </div>
        </div>
        <div class="card-body" id="v_course_description">
            {!!$c->description!!}
        </div>
        @endforeach
    </div>
</div>
<div class="modal fade" id="modalEditCourse" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="formEditCourse" method="post" enctype="multipart/form-data">
                @csrf
                @foreach($course as $c)
                <div class="modal-header">
                    <h5 class="modal-title">Edit course</h5>
                    <input type="text" id="course_id" name="course_id" value="{{$c->course_id}}" hidden>
                    <input type="text" name="type" value="single" hidden>
                </div>
                <div class="modal-body row">
                    <div class="d-flex justify-content-between">
                        <strong><i class="bi bi-card-image me-1"></i>Background image</strong>
                        <input type="file" id="course_img_e" name="course_img" accept="image/*" hidden>
                        <label for="course_img_e">
                            <a class="btn btn-primary"><i class="bi bi-plus"></i>Upload image</a>
                        </label>
                    </div>
                    <img id="bg_img_e" src="{{asset('uploads/course/')}}/{{$c->course_image}}" class="img-fluid mt-1" alt="">
                    <div class="form-group mt-3 row">
                        <div class="col-6">
                            <label for="course_name_e"><strong><i class="bi bi-bookmark me-1"></i>Course name</strong></label>
                            <input type="text" name="course_name" id="course_name_e" class="form-control" value="{{$c->course_name}}">
                        </div>
                        <div class="col-6">
                            <label for="course_type_e"><strong><i class="bi bi-bookmark me-1"></i>Course type</strong></label>
                            <select name="course_type" id="course_type_e" class="form-select">
                                @foreach($getTypeName as $type)
                                    <option value="{{$type->course_type_id}}" @if($type->course_type_id == $c->course_type_id) selected @endif>{{$type->type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="course_description"><strong><i class="bi bi-card-text me-1"></i>Description</strong></label>
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
<script>
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
        setup: function (editor) {
            editor.on('init', function () {
                @foreach($course as $c)
                editor.setContent(`{!!$c->description!!}`);
                @endforeach
            });
        },
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

    function addsection(e) {
        e.innerHTML = '<i class="bi bi-pencil-square"></i><span>Reset</span>';
        $('#new-section').empty();
        $('#new-section').append(
            `<div class="input-group">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" type="button"><i class="bi bi-check"></i></button>
                    </div>
                </div>
            </div>`
        );
    };
    $('#formEditCourse').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        if (tinymce.get('course_description_e').getContent() == '') {
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
                console.log(data);
                if (data.success) {
                    toastr.success(data.message);
                    $('#modalEditCourse').modal('hide');
                    data.courses.forEach(course => {
                        $('#course_id').val(course.course_id);
                        $('#course_name_e').val(course.course_name);
                        tinymce.get('course_description_e').setContent(course.description);
                        $('#bg_img_e').attr('src', '{{asset('uploads/course/')}}/' + course.course_image);
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
</script>
@endsection
