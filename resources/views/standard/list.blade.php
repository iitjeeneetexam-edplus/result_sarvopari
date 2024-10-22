<!-- resources/views/standards/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Standards</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Optional: Link your CSS -->
</head>
<body>
    <div class="container">
        <h1>List of Standards</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Name</th>
                    <th>Standard Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($standards as $standard)
                <tr>
                    <td>{{ $standard->id }}</td>
                    <td>{{ $standard->school->school_name }}</td> <!-- Accessing school name via relationship -->
                    <td>{{ $standard->standard_name }}</td>
                    <td>{{ $standard->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div>
            {{ $standards->links() }} <!-- Display pagination links -->
        </div>
    </div>
</body>
</html>
