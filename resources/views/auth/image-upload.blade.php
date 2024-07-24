@extends('auth.main')

@section('contents')
    <div class="row">
        <div class="col-6">
            <input type="file"
                   class="filepond"
                   name="filepond[]"
                   multiple
                   data-allow-reorder="true"
                   data-max-file-size="3MB"
                   data-max-files="3"
                   accept="image/png, image/jpeg, image/gif">
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
                    process: {
                        url: '{{ action('App\Http\Controllers\UploadFileController@imageUpload') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    },
                    revert: {
                        url: '{{ action('App\Http\Controllers\UploadFileController@imageDelete') }}',
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }
                }
            }
        );
    </script>
@endsection
