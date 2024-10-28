<!-- resources/views/list_schools.blade.php -->
@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-8 col-lg-7">
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
                                <label for="school">Select School</label>
                                <select name="school_id" id="school" class="form-control">
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
                                <button type="submit" class="btn btn-success">Get Student List</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered" id="studentdata">
                        <thead>
                            <tr>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div></div>
    </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        // School change event for fetching standards
                        function loadStandards(schoolId) {
                            // var schoolId = $(this).val();
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
                        }
                        var preSelectedSchoolId = $('#school').val();
                        if (preSelectedSchoolId) {
                            
                            loadStandards(preSelectedSchoolId);
                        }
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
                                        var studentRow = '<tr>' +
                                            '<td>' + value.id + '</td>' +
                                            '<td>' + value.name + '</td>' +
                                            '<td>' + value.roll_no + '</td>' +
                                            '<td>' + value.GR_no + '</td>' +
                                            '<td>' + value.total_marks + '</td>';

                                        // Loop through the subjects to get the corresponding marks
                                        if (data.subject != null) {
                                            var subjectsArray = data.subject.split(',');

                                            $.each(subjectsArray, function(index, subjectName) {
                                                studentRow += '<td>' + (value.marks[subjectName.trim()] || 'Empty') + '</td>';
                                            });
                                        }

                                        studentRow += '</tr>';
                                        $('#studentdata tbody').append(studentRow);
                                    });

                                    // Header section
                                    $('#studentdata thead tr').empty();

                                    // Static headers
                                    $('#studentdata thead tr').append('<th>No</th>');
                                    $('#studentdata thead tr').append('<th>Student Name</th>');
                                    $('#studentdata thead tr').append('<th>Roll No</th>');
                                    $('#studentdata thead tr').append('<th>GR No</th>');
                                    $('#studentdata thead tr').append('<th>Total Marks</th>');

                                    // Adding dynamic subject headers
                                    if (data.subject != null) {
                                        var subjectsArray = data.subject.split(',');

                                        $.each(subjectsArray, function(index, subjectName) {
                                            $('#studentdata thead tr').append('<th>' + subjectName.trim() + '</th>');
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        });
                    });
                </script>
</x-app-layout>