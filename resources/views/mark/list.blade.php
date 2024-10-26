<!-- resources/views/list_schools.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">

        <h1>List of Mark</h1>
        <div class="row">
          <div class="col-sm-2 offset-sm-10"><a href="{{ url('marks/create') }}" class="btn btn-success mb-3" style="float: right;">Add New Mark</a>
        </div>
        </div>
        <form method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="school">School</label>
                                    <select name="school_id" id="school" class="form-control">
                                        <option value="">All Schools</option>
                                        @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->school_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="standard">Select Standard:</label>
                                    <select name="standard_id" id="standard" class="form-control">
                                        <option value="">Select a Standard</option>
                                        <!-- Populated via AJAX -->
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="division">Select Division:</label>
                                    <select name="division_id" id="division" class="form-control">
                                        <option value="">Select a Division</option>
                                        <!-- Populated via AJAX -->
                                    </select>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Get Student List</button>
                                </div>
                            </div>
                        </form>

                        <table class="table table-bordered" id="studentdata">
                            <thead>
                                <tr>
                                    <th>no</th>
                                    <th>Student Name</th>
                                    <th>Roll No</th>
                                    <th>GR No</th>
                                    <th>Mark</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                            </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // School change event for fetching standards
            $('#school').change(function() {
                var schoolId = $(this).val();
                if (schoolId) {
                    $.ajax({
                        url: '{{ url("/get-standards") }}/' + schoolId,
                        type: 'GET',
                        success: function(data) {
                            $('#standard').empty().append('<option value="">Select a Standard</option>');
                            $.each(data, function(key, value) {
                                $('#standard').append('<option value="' + value.id + '">' + value.standard_name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#standard').empty().append('<option value="">Select a Standard</option>');
                    $('#division').empty().append('<option value="">Select a Division</option>');
                }
            });

            // Standard change event for fetching divisions
            $('#standard').change(function() {
                var standardId = $(this).val();
                if (standardId) {
                    $.ajax({
                        url: '{{ url("/get-divisions") }}/' + standardId,
                        type: 'GET',
                        success: function(data) {
                            $('#division').empty().append('<option value="">Select a Division</option>');
                            $.each(data, function(key, value) {
                                $('#division').append('<option value="' + value.id + '">' + value.division_name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#division').empty().append('<option value="">Select a Division</option>');
                }
            });
            $('form').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally

            $.ajax({
                url: '/students/getstudentformarks',
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    $.each(data.student, function(key, value) {

                        $('#studentdata tbody').append(
                            '<tr>' +
                                '<td>' + value.id + '</td>' +
                                '<td>' + value.name + '</td>' +
                                '<td>' + value.roll_no + '</td>' +
                                '<td>' + value.GR_no + '</td>' +
                                '<td>' + value.mark + '</td>' +
                            '</tr>'
                        );
                    });
                    $.each(data.subject, function(key, value) {

                        $('#studentdata thead tr').append(
                                    '<th>' + value.subject_name + '</th>'
                                );
                    });
                                    
                },
                error: function(xhr, status, error) {
                    console.error(error); // Handle errors if they occur
                }
            });
        });
        });
       
    </script>
</x-app-layout>