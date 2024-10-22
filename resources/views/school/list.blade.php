<!-- resources/views/list_schools.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Schools</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Optional: Link your CSS -->
</head>
<body>
    <div class="container">
        <h1>List of Schools</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                    <tr>
                        <td>{{ $school->id }}</td>
                        <td>{{ $school->school_name }}</td>
                        <td>{{ $school->address }}</td>
                        <td>{{ $school->email }}</td>
                        <td>{{ $school->contact_no }}</td>
                        <td>{{ $school->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
