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
            <li class="breadcrumb-item">Course</li>
            <li class="breadcrumb-item">?</li>
        </ol>
    </nav>
</div>
<div class="section educaiton">
    <div class="row">
        <div class="col-lg-8">

        </div>
    </div>
</div>
<script>
    tinymce.init({
        selector: 'textarea#default',
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
        branding: false
    });
</script>
@endsection