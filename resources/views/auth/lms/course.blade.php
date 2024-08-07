@extends('auth.main')

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
            <li class="breadcrumb-item active">Course</li>
        </ol>
    </nav>
</div>
<div class="section educaiton">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddCourse">
        <i class="bi bi-plus me-1"></i>Add course
    </button>
    <div class="row mt-2">
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4">
            <div class="card shadow bg-white border-none rounded-4">
                <div class="card-header m-2 p-0 text-primary">
                    <img class="border-none rounded-top-4" style="max-width: 100%" src="https://cdn.discordapp.com/attachments/997416866622492692/1270221480684163132/screenshot_1722914773.png?ex=66b2e970&is=66b197f0&hm=4ee5b15d5d25e1c0a2b7e2484c86fbc7072b394cce29b89e81ebefad9038db7f&">
                    <h4>Lập trình cơ bảnaaaaaaaayyyy</h4>
                </div>
                <div class="card-body m-3 p-0">
                    Khóa học hấp dẫn thú vị, cuốn hút!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4">
            <div class="card shadow bg-white border-none rounded-4">
                <div class="card-header m-2 p-0 text-primary">
                    <img class="border-none rounded-top-4" style="max-width: 100%" src="https://cdn.discordapp.com/attachments/997416866622492692/1270221480684163132/screenshot_1722914773.png?ex=66b2e970&is=66b197f0&hm=4ee5b15d5d25e1c0a2b7e2484c86fbc7072b394cce29b89e81ebefad9038db7f&">
                    <h4>Lập trình cơ bản</h4>
                </div>
                <div class="card-body m-3 p-0">
                    Khóa học hấp dẫn thú vị, cuốn hút!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                </div>
            </div>
        </div>
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
                        <input type="file" id="course_img" name="course_img" accept="image/*" hidden>
                        <label for="course_img">
                            <a class="btn btn-primary"><i class="bi bi-plus"></i>Upload image</a>
                        </label>
                    </div>
                    <img id="bg_img" src="" class="img-fluid mt-1" alt="">
                    <div class="form-group mt-3">
                        <label for="course_name"><strong><i class="bi bi-bookmark me-1"></i>Course name</strong></label>
                        <input type="text" name="course_name" id="course_name" class="form-control">
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
<script>
    $('#course_img').change(function() {
        var file = $(this)[0].files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#bg_img').attr('src', e.target.result);
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
        images_upload_handler: function (blobInfo, success, failure) {
            failure('Image uploading is disabled');
        },
        plugins_exclude: 'print media'
    });
    $('#formAddCourse').submit(function(e) {
        console.log($(this).serialize());
        //log course_description
        console.log(tinymce.get('course_description').getContent());
        //log tên file ảnh
    });
</script>
@endsection