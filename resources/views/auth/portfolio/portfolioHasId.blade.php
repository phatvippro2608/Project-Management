@extends('auth.main')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .card-header {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Portfolio</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('portfolio') }}">Portfolio</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="#">{{ $id }}</a>
                </li>
            </ol>
        </nav>
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('uploads/1/cat.jpg') }}" alt="Profile Image" class="profile-img">
                    <div class="ml-3">
                        <h4>John Doe</h4>
                        <p>UI/UX Design Team<br>Web Designer<br>Employee ID: FT-0001<br>Date of Join: 1st Jan 2013</p>
                    </div>
                </div>
                <button class="btn btn-primary">Send Message</button>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Projects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Bank & Statutory <small class="text-muted">(Admin Only)</small></a>
            </li>
        </ul>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Personal Informations</h5>
                    </div>
                    <div class="card-body">
                        <p>Passport No.: 9876543210</p>
                        <p>Passport Exp Date: 9876543210</p>
                        <p>Tel: <a href="tel:9876543210">9876543210</a></p>
                        <p>Nationality: Indian</p>
                        <p>Religion: Christian</p>
                        <p>Marital status: Married</p>
                        <p>Employment of spouse: No</p>
                        <p>No. of children: 2</p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Bank Information</h5>
                    </div>
                    <div class="card-body">
                        <p>Bank name: ICICI Bank</p>
                        <p>Bank account No: 159843014641</p>
                        <p>IFSC Code: ICI24504</p>
                        <p>PAN No: TC000Y56</p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Education Informations</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>International College of Arts and Science (UG)</strong><br>Bsc Computer Science<br>2000 -
                            2003</p>
                        <p><strong>International College of Arts and Science (PG)</strong><br>Msc Computer Science<br>2000 -
                            2003</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Emergency Contact</h5>
                    </div>
                    <div class="card-body">
                        <h6>Primary</h6>
                        <p>Name: John Doe</p>
                        <p>Relationship: Father</p>
                        <p>Phone: 9876543210, 9876543210</p>
                        <h6>Secondary</h6>
                        <p>Name: Karen Wills</p>
                        <p>Relationship: Brother</p>
                        <p>Phone: 9876543210, 9876543210</p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Family Informations</h5>
                    </div>
                    <div class="card-body">
                        <p>Name: Leo</p>
                        <p>Relationship: Brother</p>
                        <p>Date of Birth: Feb 16th, 2019</p>
                        <p>Phone: 9876543210</p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Experience</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Web Designer at Zen Corporation</strong><br>Jan 2013 - Present (6 years 2 months)</p>
                        <p><strong>Web Designer at Ron-tech</strong><br>Jan 2013 - Present (6 years 2 months)</p>
                        <p><strong>Web Designer at Dalt Technology</strong><br>Jan 2013 - Present (6 years 2 months)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection
