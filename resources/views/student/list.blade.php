<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-sm-6 col-md-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">



                    <!-- Student List Table -->
                   
                            <form method="POST" action="{{ route('assign.subject') }}">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="select-all">
                                                </th>
                                                <th>Roll Number</th>
                                                <th>Name</th>
                                                <th>GR Number</th>
                                                <th>UID</th>
                                                <th>Division</th>
                                                @if(!empty($subjects))
                                                @foreach($subjects as $optionls) <!-- Reverse the subjects collection -->
                                                <th>{{ $optionls->subject_name }}</th>
                                                @endforeach
                                                @endif
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($students->isNotEmpty())
                                            @foreach($students as $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="student_ids[]" class="student-checkbox" value="{{ $student->id }}" @if($student->subject_id) disabled @endif>
                                                </td>
                                                <td>{{ $student->roll_no }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->GR_no }}</td>
                                                <td>{{ $student->uid }}</td>
                                                <td>{{ $student->division->division_name }}</td>
                                                @php
                                                $subjectNames = explode(',', $student->subject_name); // Split the subject names
                                                @endphp
                                                @if(!empty($subjectNames))
                                                @foreach($subjectNames as $subjectName)
                                                <td>{{ trim($subjectName) }} </td>
                                                @endforeach
                                                @else
                                                <span>No subjects assigned</span>
                                                @endif
                                                
                                                <td>
                                                    <a href="#" class="btn btn-sm edit-btn btn-success">Edit</a>
                                                    <form action="#" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="7" class="text-center">No students found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @foreach($subjects as $optionls)
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="subject">Select Subject:</label>
                                        <select name="subject_ids[]" id="subject" class="form-control" required>
                                            <option value="">Select a {{ $optionls->subject_name }}</option>
                                            @foreach($subject_subs as $subject_subsoptions)
                                            @foreach($subject_subsoptions as $subjectSub)
                                            @if($optionls->id == $subjectSub->subject_id)
                                            <option value="{{ $subjectSub->id }}">{{ $subjectSub->subject_name }}</option>
                                            @endif
                                            @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endforeach
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary mb-3" style="float:right">Assign Subject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
       
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editRollNo">Roll Number</label>
                            <input type="text" class="form-control" id="editRollNo" name="roll_no" required>
                        </div>
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editGRNo">GR Number</label>
                            <input type="text" class="form-control" id="editGRNo" name="GR_no" required>
                        </div>
                        <div class="form-group">
                            <label for="editUID">UID</label>
                            <input type="text" class="form-control" id="editUID" name="uid" required>
                        </div>
                        <div class="form-group">
                            <label for="editDivision">Division</label>
                            <input type="text" class="form-control" id="editDivision" name="division_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Select/Deselect All Checkboxes
        $('#select-all').click(function() {
            $('.student-checkbox:enabled').prop('checked', this.checked);
        });

        // Deselect "Select All" if any individual checkbox is unchecked
        $('.student-checkbox').click(function() {
            if (!$(this).is(':checked')) {
                $('#select-all').prop('checked', false);
            }
        });

        $('.edit-btn').click(function() {
            const data = $(this).data('students');
            $.each(data.students, function(index, student) {
                alert(student);
            });
            alert(data.students);
            $('#editForm').attr('action', '/students/' + student.id);
            $('#editRollNo').val(student.roll_no);
            $('#editName').val(student.name);
            $('#editGRNo').val(student.GR_no);
            $('#editUID').val(student.uid);
            $('#editDivision').val(student.division.division_name);
            $('#editModal').modal('show');
        });
    </script>
</x-app-layout>