@extends('auth.main')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        #preview {
            width: 150px;
            height: 150px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 50%;
        }

        #preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Settings</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('setting.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="container mt-5">
            <div class="content bg-light card p-4 border rounded-4">
                <div class="content--img text-center mb-4">
                    <div id="preview" class="rounded-circle mb-2">
                        @if (isset($options->option_img))
                            <img src="{{ asset($options->option_img) }}" alt="Image Preview">
                        @else
                            No Image Selected
                        @endif
                    </div>
                    <input type="file" id="imageInput" accept="image/*" name="option_img" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="inp--title" class="form-label">Site Title</label>
                    <input name="option_title" type="text" class="form-control" id="inp--title" value="{{ $options->option_title }}"
                        placeholder="H R System (CI)">
                </div>

                <div class="mb-3">
                    <label for="inp--des" class="form-label">Site Description</label>
                    <textarea name="option_description" class="form-control" id="inp--des" rows="3" placeholder="Just a demo description and nothing else!">{{ $options->option_description }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="inp--copyr" class="form-label">Copyright</label>
                    <input name="option_copyright" type="text" class="form-control" id="inp--copyr" value="{{ $options->option_copyright }}"
                        placeholder="VENTECH">
                </div>

                <div class="mb-3">
                    <label for="inp--cont" class="form-label">Contact</label>
                    <input name="option_contact" type="text" class="form-control" id="inp--cont" value="{{ $options->option_contact }}"
                        placeholder="0001110000">
                </div>

                <div class="mb-3">
                    <label for="inp--currency" class="form-label">Currency</label>
                    <select class="form-select" id="inp--currency" name="option_currency">
                        @foreach ($currencies as $item)
                            <option value="{{ $item->currency_id }}"
                                {{ $options->currency_id == $item->currency_id ? 'selected' : '' }}>
                                {{ $item->currency_currency . ' (' . $item->currency_symbol . ') - ' . $item->currency_country }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="mb-3">
                    <label for="inp--symbol" class="form-label">Symbol</label>
                    <select class="form-select" id="inp--symbol" name="option_symbol">
                        @foreach ($symbolOptions as $id => $symbol)
                            <option value="{{ $id }}" {{ $options->currency_id == $id ? 'selected' : '' }}>
                                {{ $symbol }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="mb-3">
                    <label for="inp--semail" class="form-label">System Email</label>
                    <input name="option_email" type="email" class="form-control" id="inp--semail" value="{{ $options->option_email }}"
                        placeholder="contact@hrms.abc">
                </div>

                <div class="mb-3">
                    <label for="inp--address" class="form-label">Address</label>
                    <input name="option_address" type="text" class="form-control" id="inp--address" value="{{ $options->option_address }}"
                        placeholder="96 Cao Thang Street, Ward 4, District 3, Ho Chi Minh City">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success col-3">Change</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Image Preview">';
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = 'No Image Selected';
            }
        });
    </script>
@endsection
