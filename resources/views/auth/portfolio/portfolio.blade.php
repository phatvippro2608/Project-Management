@extends('auth.main')
@section('head')
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>Portfolifo</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Portfolifo</li>
            </ol>
        </nav>
    </div>
    <div class="content col">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        Employee Code
                    </th>
                    <th>
                        Photo
                    </th>
                    <th>
                        Full Name
                    </th>
                    <th>
                        English Name
                    </th>
                    <th>
                        Completed Projects
                    </th>
                    <th>
                        Issues
                    </th>
                    <th>
                        Compliments
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sql as $item)
                <tr>
                    <td>
                        {{ $item->employee_code }}
                    </td>
                    <td>
                        <img src="{{ asset($item->photo) }}" style="width: 72px;height: 75px;border-radius: 50%"
                            alt="">
                    </td>
                    <td>
                        {{ $item->last_name.' '.$item->first_name  }}
                    </td>
                    <td>
                        {{ $item->en_name }}
                    </td>
                    <td>
                        5
                    </td>
                    <td>
                        3
                    </td>
                    <td>
                        2
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
