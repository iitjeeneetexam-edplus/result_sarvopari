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
                <div class="table-container">
                    <table class="table table-bordered" id="studentdata">
                        <thead class="thead-dark">
                            <tr>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div id="pagination-links"></div>
                </div>
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
                                        var studentRow = `<tr class="student-row" data-id="${value.id}">`+
                                            '<td>' + value.id + '</td>' +
                                            '<td><button class="btn btn-success">Result</button></td>' +
                                            '<td>' + value.name + '</td>' +
                                            '<td>' + value.roll_no + '</td>' +
                                            '<td>' + value.GR_no + '</td>';
                                           
                                        if (data.subject != null) {
                                            var subjectsArray = data.subject.split(',');
                                            

                                            $.each(subjectsArray, function(index, subjectName) {
                                                studentRow += '<td>' + (value.marks[subjectName.trim()]  || '') + '</td>';
                                            });
                                        }
                                        studentRow += `<td><button class="openModalBtn btn btn-success" data-id="${value.id}" data-division-id="${value.division_id}" >Edit</button></td>`;
                                        studentRow += '</tr>';
                                        $('#studentdata tbody').append(studentRow);
                                    });

                                    // Header section
                                    $('#studentdata thead tr').empty();

                                    // Static headers
                                    $('#studentdata thead tr').append('<th>No</th>');
                                    $('#studentdata thead tr').append('<th>Results</th>');
                                    $('#studentdata thead tr').append('<th>Student Name</th>');
                                    $('#studentdata thead tr').append('<th>Roll No</th>');
                                    $('#studentdata thead tr').append('<th>GR No</th>');
                                   
                                  
                                    // Adding dynamic subject headers
                                    if (data.subject != null) {
                                        var subjectsArray = data.subject.split(',');
                                        var totalmarks = data.total_marks;

                                        $.each(subjectsArray, function(index, subjectName) {
                                            var marks = totalmarks[index] !== undefined && totalmarks[index] !== '' ? totalmarks[index] : ''; // default to 'N/A' if undefined or empty
                                            $('#studentdata thead tr').append('<th>' + subjectName.trim() + '(' + marks + ')</th>');
                                        });
                                    }
                                    $('#studentdata thead tr').append('<th>Action</th>');
                                    // $('#pagination-links').html(data.subject.pagination);
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        });
                    });
                    $(document).on('click', '.openModalBtn', function() {
                            var studentId  = $(this).data('id');
                            var divisionId = $(this).data('division-id');
                            var row = $(this).closest('.student-row');

                            $.ajax({
                                url: '/marks/edit/' + studentId + '/' + divisionId, // include both IDs in the URL
                                type: 'GET',
                                success: function(data) {
                                    // Hide the current row
                                    row.hide();

                                    // Generate the editable row
                                    var editRow = `<tr class="edit-row" data-id="${studentId}">
                                        <td>${studentId}</td>
                                        <td><button class="btn btn-success">Result</button></td>
                                        <td><input type="text" class="form-control" value="${row.find('td').eq(2).text()}" name="name" disabled></td>
                                        <td><input type="text" class="form-control" value="${row.find('td').eq(3).text()}" name="roll_no" disabled></td>
                                        <td><input type="text" class="form-control" value="${row.find('td').eq(4).text()}" name="GR_no" disabled></td>`;

                                    // Add subject marks as editable inputs
                                    if (data.optional_subject != null) {
                                        var subjectsArray = data.optional_subject.split(',');
                                        console.log(data.optional_subject);
                                        $.each(subjectsArray, function(index, subjectName) {
                                            // var subjectId = data.subject_ids[index]; // Assuming you have an array of subject IDs in 'data.subject_ids'

                                        // Add hidden input for subject_id and text input for marks
                                            // editRow += `<td><input type="text" class="form-control" value="${index}" name="subject_id[]"></td>`; // Hidden field for subject_id
                            
                                            editRow += `<td><input type="text" class="form-control" value="${row.find('td').eq(5 + index).text()}" name="marks[${subjectName.trim()}]"></td>`;
                                        });
                                    }

                                    editRow += `<td><div class="d-flex"><button type="button" class="btn btn-danger cancelEditBtn">Cancel</button><button type="button" class="btn btn-success saveEditBtn">Update</button></div></td></tr>`;
                                    
                                    // Insert the editable row right after the hidden row
                                    row.after(editRow);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error fetching data:', error);
                                    alert('Error fetching data');
                                }
                            });
                        });

// Event delegation for cancel edit button
                                $(document).on('click', '.cancelEditBtn', function() {
                                    var row = $(this).closest('.student-row');
                                    var editRow = $(this).closest('.edit-row');
                                    
                                    // Show the original row again and remove the edit row
                                    row.show();
                                    editRow.remove();
                                });

                                // Event delegation for save edit button (if you want to handle saving logic)
                                $(document).on('click', '.saveEditBtn', function() {
                                    var editRow = $(this).closest('.edit-row');
                                    var studentId = editRow.data('id');
                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

// Set the token in the AJAX request headers
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        }
                                    });
                                    // Collect the form data from the edit row
                                    var formData = {
                                        name: editRow.find('input[name="name"]').val(),
                                        roll_no: editRow.find('input[name="roll_no"]').val(),
                                        GR_no: editRow.find('input[name="GR_no"]').val()
                                    };
                                    
                                    // Add marks data for each subject
                                    editRow.find('input[name^="marks"]').each(function() {
                                        formData[$(this).attr('name')] = $(this).val();
                                    });

                                    $.ajax({
                                        url: '/marks/update/' + studentId, // The URL for updating marks
                                        type: 'POST',
                                        data: formData,
                                        success: function(response) {
                                            console.log('Data updated successfully', response);
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error saving data:', error);
                                            alert('Error saving data');
                                        }
                                    });
                                });


                   
                   
                </script>
</x-app-layout>
<!-- 