@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900 dark:text-gray-100 ">

                        <h2>Add New Mark</h2>

                        <!-- Add your JS or Bootstrap script here -->
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                        <!-- Display Validation Errors -->

                        <form action="{{ url('marks/store') }}" method="POST">
                            @csrf
                            <div class="col-md-12">
                                <label for="school">School</label>
                                <select name="school_id" id="school" class="form-control">
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('school_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="standard">Select Standard:</label>
                                <select name="standard_id" id="standard" class="form-control">
                                    <option value="">Select a Standard</option>
                                    <!-- Populated via AJAX -->
                                </select>
                                @error('standard_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="division">Select Division:</label>
                                <select name="division_id" id="division" class="form-control">
                                    <option value="">Select a Division</option>
                                    <!-- Populated via AJAX -->
                                </select>
                                @error('division_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="exam_id">Select Exam:</label>
                                <select name="exam_id" id="exam_id" class="form-control" require>
                                    <option value="">Select a Exam</option>
                                    <!-- exam list -->
                                </select>
                                @error('exam_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                           
                            <div class="col-md-12">
                                <label for="subject">Select Subject:</label>
                                <select name="subject_id" id="subject" class="form-control">
                                    <option value="">Select a Subject</option>
                                    <!-- Populated via AJAX -->
                                </select>
                                @error('subject_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12" style="display: none;" id="subject_sub_display">
                                <label for="subject_sub">Select option Subject:</label>
                                <select name="subject_sub" id="subject_sub" class="form-control">
                                    <option value="">Select a option Subject</option>
                                    <!-- Populated via AJAX -->
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="total_marks">Total Marks</label>
                                <input type="text" name="total_marks" id="total_marks" class="form-control" placeholder="Enter Total Marks" require>
                                @error('total_marks')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="passing_marks">Passing Marks</label>
                                <input type="text" name="passing_marks" id="passing_marks" class="form-control" placeholder="Enter Passing Marks" require>
                                @error('passing_marks')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <br>
                            <h4>Student List</h4>
                            <table id="studentTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Roll Number</th>
                                        <th>Name</th>
                                        <th>Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- student list for add marks -->
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success mb-3" style="float:right">Add Mark</button>
                        </form>


                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="path/to/your-script.js"></script>

<script>
    $(document).ready(function() {
        // School change event for fetching standards
        function loadStandards(schoolId) {
            var oldStatus = "{{ old('standard_id') }}";
            if (schoolId) {
                $.ajax({
                    url: '{{ url("/get-standards") }}/' + schoolId,
                    type: 'GET',
                    success: function(data) {
                        $('#standard').empty().append('<option value="">Select a Standard</option>');
                        $.each(data, function(key, value) {
                            var isSelected = (oldStatus == value.id) ? 'selected' : ''; 
                            $('#standard').append('<option value="' + value.id + '" ' + isSelected + '>' + value.standard_name + '</option>');
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
            var division_id = "{{ old('division_id') }}";
            var exam_id = "{{ old('exam_id') }}";
            var subject_id = "{{ old('subject_id') }}";
            var standardId = $(this).val();
            if (standardId) {
                $.ajax({
                    url: '{{ url("/get-divisions-subject") }}/' + standardId,
                    type: 'GET',
                    success: function(data) {

                        $('#division').empty().append('<option value="">Select a Division</option>');

                        // Populate the division dropdown
                        if (data.divisions && Array.isArray(data.divisions) && data.divisions.length) {
                            $.each(data.divisions, function(key, value) {
                                var isSelected = (division_id == value.id) ? 'selected' : ''; 
                                $('#division').append('<option value="' + value.id + '"  ' + isSelected + '>' + value.division_name + '</option>');
                            });
                        } else {
                            $('#division').append('<option value="">No divisions found</option>');
                        }

                        // Clear the subject dropdown and add the default option
                        $('#subject').empty().append('<option value="">Select a Subject</option>');

                        // Populate the subject dropdown
                        if (data.subjects && Array.isArray(data.subjects) && data.subjects.length) {
                            $.each(data.subjects, function(key, value) {
                                var isSelected = (exam_id == value.id) ? 'selected' : ''; 
                                $('#subject').append('<option value="' + value.id + '" ' + isSelected + '>' + value.subject_name + '</option>');
                            });
                        } else {
                            $('#subject').append('<option value="">No subjects found</option>');
                        }

                        //exam list
                        $('#exam_id').empty().append('<option value="">Select a Exam</option>');
                        if (data.exams && Array.isArray(data.exams) && data.exams.length) {
                            $.each(data.exams, function(key, value) {
                                var isSelected = (exam_id == value.id) ? 'selected' : ''; 
                                $('#exam_id').append('<option value="' + value.id + '" ' + isSelected + '>' + value.exam_name + '</option>');
                            });
                        } else {
                            $('#exam_id').append('<option value="">No Exam found</option>');
                        }

                    }
                });
            } else {
                $('#division').empty().append('<option value="">Select a Division</option>');
            }
        });


        $('#subject').change(function() {
            var subject_id = $(this).val();
            var division_id = $('#division').val();
            if (subject_id) {
                $.ajax({
                    url: '{{ url("/get-subjects-sub") }}/' + subject_id,
                    type: 'GET',
                    success: function(data) {
                        if (data.optional) {
                            if (data.optional.is_optional == '1') {
                                $('#subject_sub_display').show();
                                $('#subject_sub').empty().append('<option value="">Select a subject_sub</option>');

                                if (data.subject_sub && Array.isArray(data.subject_sub) && data.subject_sub.length) {
                                    $.each(data.subject_sub, function(key, value) {
                                        $('#subject_sub').append('<option value="' + value.id + '">' + value.subject_name + '</option>');
                                    });
                                }

                            } else {
                                $('#subject_sub_display').hide();
                                getstudentlist(subject_id,0); //student list function
                            }
                        }
                    }
                });
            } else {
                $('#subject_sub').empty().append('<option value="">Select a Standard</option>');
            }
        });

        //subject_sub
        $('#subject_sub').change(function() {
            var subject_id = $(this).val();
            if (subject_id) {
                getstudentlist(subject_id,1);
            }
        });


        function getstudentlist(subject_id,is_optional) {
            var division_id = $('#division').val();
            var exam_id = $('#exam_id').val();
            $.ajax({
                url: '{{ url("/students/marksaddstudentlist") }}/' + division_id + '/' + subject_id + '/' + is_optional+ '/' + exam_id,
                type: 'GET',
                success: function(data) {
                    $('#studentTable tbody').empty();
                    if (data.students.length > 0) {
                        var totalmark = $('#total_marks').val();
                        $.each(data.students, function(index, student) {
                            var row = '<tr>' +
                                '<td>' + student.roll_no + '</td>' +
                                '<td>' + student.name + '</td>' +
                                '<td><input type="number" name="marks[]" value="'+student.marks+'"  min="0" max="' + totalmark + '" step="1" require/> <input type="hidden" name="student_id[]" value="' + student.id + '" /></td>' +
                                '</tr>';
                            $('#studentTable tbody').append(row);
                        });
                    } else {
                        $('#studentTable tbody').append('<tr><td colspan="5">No students found.</td></tr>');
                    }
                },
                error: function() {
                }
            });
        }
    });
</script>