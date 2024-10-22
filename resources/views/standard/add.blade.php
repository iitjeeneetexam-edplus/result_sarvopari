<!-- resources/views/standards/create.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Standard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Optional: Link your CSS -->
</head>
<body>
    <div class="container">
        <h1>Add New Standard</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('standards.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="school_id">School</label>
                <select name="school_id" id="school_id" class="form-control" required>
                    <option value="">Select a School</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                    @endforeach
                </select>
                @error('school_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="standard_name">Standard Name</label>
                <input type="text" name="standard_name" id="standard_name" class="form-control" required>
                @error('standard_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Add Standard</button>
        </form>

        <a href="{{ route('standards.index') }}" class="btn btn-secondary mt-3">Back to Standards</a>
    </div>
</body>
</html>
