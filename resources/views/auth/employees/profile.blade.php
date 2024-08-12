@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>
    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        @php
                            $data = \Illuminate\Support\Facades\DB::table('accounts')
                                        ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
                                        ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
                                        ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')

                                        ->where(
                                        'accounts.account_id',
                                        \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
                                        )
                                        ->first();
                            $info = \Illuminate\Support\Facades\DB::table('job_details')
                                        ->join('job_positions', 'job_details.job_position_id', '=', 'job_positions.position_id')
                                        ->where(
                                            'job_details.employee_id', $data->employee_id
                                        )
                                        ->first();
                            $country = \Illuminate\Support\Facades\DB::table('job_details')
                                        ->join('job_countries', 'job_details.job_country_id', '=', 'job_countries.country_id')
                                        ->where(
                                            'job_details.employee_id', $data->employee_id
                                        )
                                        ->first();

                                    $photoPath = asset($data->photo);
                                    $defaultPhoto = asset('assets/img/avt.png');
                                    $photoExists = !empty($data->photo) && file_exists(public_path($data->photo));
                            @endphp
                        <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" class="rounded-circle object-fit-cover" width="100" height="100">
                        <h2>{{$data->first_name  . ' ' . $data->last_name }}</h2>
                        <h3>@if($info){{$info->position_name}}@endif</h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">

                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview"
                                        aria-selected="true" role="tab">Overview
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit"
                                        aria-selected="false" tabindex="-1" role="tab">Edit Profile
                                </button>
                            </li>


                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password"
                                        aria-selected="false" tabindex="-1" role="tab">Change Password
                                </button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview"
                                 role="tabpanel">
                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div
                                        class="col-lg-9 col-md-8">{{$data->first_name  . ' ' . $data->last_name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Company</div>
                                    <div class="col-lg-9 col-md-8">Ventech</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Job</div>
                                    <div class="col-lg-9 col-md-8">@if($info){{$info->position_name}}@endif</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Country</div>
                                    <div class="col-lg-9 col-md-8">@if($country){{$country->country_name}}@endif</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8">{{$data->permanent_address}}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8">{{$data->phone_number}}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{$data->email}}</div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit" role="tabpanel">
                                <!-- Profile Edit Form -->
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                    <div class="col-lg-2 text-center">
                                        <img class="border rounded-pill object-fit-cover" width="100px" height="100px" id="profileImage" src="{{$photoExists ? $photoPath : $defaultPhoto}}" alt="Profile">
                                    </div>
                                    <div class="col-lg-6">
                                        <form class="border rounded-4 p-2 text-center" action="{{route('img-store')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file"
                                                   id="image"
                                                   name="image"
                                                   data-max-files="1"
                                                   accept="image/png, image/jpeg, image/gif"
                                            >
                                            <button class="btn btn-primary mx-auto d-none filepond-upload" type="submit"><i class="bi bi-upload me-2"></i>Upload</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">First
                                        Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="" type="text" class="form-control" id="first_name"
                                               value="{{$data->first_name}}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="" type="text" class="form-control" id="last_name"
                                               value="{{$data->last_name}}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Job" class="col-md-4 col-lg-3 col-form-label">Job</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select class="form-select" id="position_name" aria-label="Default select example">
                                            <option value="">No Select</option>
                                            @foreach( $dataEmployee['jobPositions'] as $item)
                                                <option value="{{$item->position_id}}"  @if($info){{ $item->position_id == $info->job_position_id ? 'selected' : '' }}@endif>{{$item->position_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select class="form-select" id="country_name" aria-label="Default select example">
                                            <option value="">No Select</option>
                                            @foreach($dataEmployee['jobCountry'] as $item)
                                                <option value="{{$item->country_id}}" @if($country){{ $item->country_id == $country->job_country_id ? 'selected' : '' }}@endif>{{$item->country_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="address" type="text" class="form-control"
                                               id="permanent_address"
                                               value="{{$data->permanent_address}}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="phone" type="text" class="form-control" id="phone_number"
                                               value="{{$data->phone_number}}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="text" class="form-control" id="email"
                                               value="{{$data->email}}">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-primary btnLuu"><i class="bi bi-floppy me-3"></i>Save Changes</button>
                                </div>
                            </div>


                            <div class="tab-pane fade pt-3" id="profile-change-password" role="tabpanel">
                                <!-- Change Password Form -->
                                <form>
                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="current_password" type="password" class="form-control" id="currentPassword" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="new_password" type="password" class="form-control" id="newPassword" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="renew_password" type="password" class="form-control" id="renewPassword" required>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary btnChangePwd">Change Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $('.btnLuu').click(function (event) {
            $.ajax({
                url: '{{ action('App\Http\Controllers\ProfileController@postProfile')}}',
                type: "POST",
                data: {
                    'employee_id': "{{$employ_detail['employee_id']}}",
                    '_token': "{{ csrf_token() }}",
                    'first_name': $('#first_name').val(),
                    'last_name': $('#last_name').val(),
                    'position_name': $('#position_name').val(),
                    'country_name': $('#country_name').val(),
                    'permanent_address': $('#permanent_address').val(),
                    'phone_number': $('#phone_number').val(),
                    'email': $('#email').val(),
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Lưu thành công");
                        setTimeout(function () {
                            location.reload()
                        }, 250);
                    } else if (result.status === 400) {
                        toastr.error(result.message, "Lỗi");
                    } else {
                        toastr.error(result.message, "Thao tác thất bại");
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });


        $('.btn_photo').click(function (event) {
            event.preventDefault();
            let filePhoto = $('#photo')[0].files[0];
            let formData = new FormData();

            if (filePhoto) {
                formData.append('photo', filePhoto);
            }

            formData.append('employee_id', {{$data->employee_id}} );

            $.ajax({
                url: '{{action('App\Http\Controllers\UploadFileController@uploadPhoto')}}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Thao tác thành công");
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.error(result.message, "Thao tác thất bại");
                    }
                }
            });
        })

        $('.btnChangePwd').click(function (event) {
            $.ajax({
                url: '{{ action('App\Http\Controllers\ProfileController@changePassword')}}',
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'currentPassword': $('#currentPassword').val(),
                    'newPassword': $('#newPassword').val(),
                    'renewPassword': $('#renewPassword').val(),
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Lưu thành công");
                        setTimeout(function () {
                            location.reload()
                        }, 500);
                    } else if (result.status === 400) {
                        toastr.error(result.message, "Lỗi");
                    } else {
                        toastr.error(result.message, "Thao tác thất bại");
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType
        );

        FilePond.create(
            document.querySelector('#image'),
            {
                allowPdfPreview: true,
                pdfPreviewHeight: 320,
                pdfComponentExtraParams: 'toolbar=0&view=fit&page=1',
                server: {
                    process: {
                        url: '{{route('img-upload')}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        ondata: (formData) => {
                            formData.append('_token', csrfToken);
                            formData.append('employee_id',{{$employ_detail['employee_id']}})
                            return formData;
                        },
                    },
                    revert:{
                        url: '{{route('img-delete')}}',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }
                },

            }
        );

        const filePond = document.querySelector("#image")
        const filepondUpload = document.querySelector('.filepond-upload')
        filePond.addEventListener('FilePond:processfile', e => {
            if (e.returnValue) {
                filepondUpload.classList.remove('d-none')
            }
        });

        filePond.addEventListener('FilePond:removefile',e=>{
            if (e.returnValue) {
                filepondUpload.classList.add('d-none')
            }
        })


    </script>
@endsection
