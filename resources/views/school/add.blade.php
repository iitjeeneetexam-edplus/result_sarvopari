<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add School</title>
    <!-- Add your CSS or Bootstrap link here -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New School</h2>
        <form action="{{ route('school.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="school_name">School Name</label>
                <input type="text" class="form-control" id="school_name" name="school_name" required>
            </div>

            <div class="form-group mb-3">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group mb-3">
                <label for="contact_no">Contact No</label>
                <input type="text" class="form-control" id="contact_no" name="contact_no" required>
            </div>

            <div class="form-group mb-3">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add School</button>
        </form>
    </div>

    <!-- Add your JS or Bootstrap script here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
