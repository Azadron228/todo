<!-- upload.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('image.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <!-- Add any additional form fields if needed -->

            <div class="form-group">
                <label for="image">Choose Image:</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <div class="form-group">
                <label for="id">Task ID:</label>
                <input type="text" name="id" id="id" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
    </div>
@endsection
