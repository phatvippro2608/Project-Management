@extends('auth.main')

@section('contents')
    <div class="row">
        <div class="col-6">
            <form action="{{action('App\Http\Controllers\UploadFileController@imageStore')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file"
                       class="filepond"
                       name="filepond"
                       multiple
                       data-allow-reorder="true"
                       data-max-file-size="3MB"
                       data-max-files="3"
                       accept="image/png, image/jpeg, image/gif">
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );

        FilePond.create(
            document.querySelector('input'),
            {
                server: {
                    process:'/imageUpload',
                    revert: '/imageDelete',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                }
            }
        );
    </script>
@endsection
