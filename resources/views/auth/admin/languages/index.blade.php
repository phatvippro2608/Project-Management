@extends('auth.main')

@section('content')
    <div class="container">
        <h1>Manage Languages</h1>
        <a href="{{ route('languages.create') }}" class="btn btn-primary">Add Language</a>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($languages as $language)
                <tr>
                    <td>{{ $language->id }}</td>
                    <td>{{ $language->code }}</td>
                    <td>{{ $language->name }}</td>
                    <td>{{ $language->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('languages.edit', $language->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('languages.destroy', $language->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
