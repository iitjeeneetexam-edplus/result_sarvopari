<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">




                    <!-- Student List Table -->
                    <div class="row">
                        <div class="col-12">

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
                                                    <a href="#" class="btn btn-sm edit-btn btn-success" data-student-id="{{ $student->id }}">Edit</a>
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
                    <input type="hidden" name="division_id" id="division_id">
                        <input type="hidden" name="standard_id" id="standard_id">

                        
                        <input type="hidden" name="studentid" id="studentid" require>
                        <div class="form-group">
                            <label for="editRollNo">Roll Number</label>
                            <input type="text" class="form-control" id="editRollNo" name="roll_no" required>
                            @error('roll_no')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="editGRNo">GR Number</label>
                            <input type="text" class="form-control" id="editGRNo" name="GR_no" required>
                            @error('GR_no')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="editUID">UID</label>
                            <input type="text" class="form-control" id="editUID" name="uid" required>
                            @error('uid')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="editDivision">Division</label>
                            <select class="form-control" id="editDivision" name="editDivision" required>
                                <option value="">Select Division</option>
                                @foreach($divisiions as $division)
                                    <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                                @endforeach
                            </select>
                            @error('editDivision')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

        $('.edit-btn').click(function(event) {  
            event.preventDefault();   
            var student_id = $(this).data('student-id');
            $.ajax({
                url: '{{ url("/students/edit") }}/' + student_id,
                type: 'GET',
                success: function(data) {
                    // console.log(data);
                     $("#division_id").val(data.division_id);
                     $("#standard_id").val(data.standard_id);

                    if (data.studentdetail.id) {  
                            $('#editForm').attr('action', '{{ url("/students/update") }}');
                            $('#editRollNo').val(data.studentdetail.roll_no);
                            $('#editName').val(data.studentdetail.name);
                            $('#editGRNo').val(data.studentdetail.GR_no);
                            $('#editUID').val(data.studentdetail.uid);
                            $('#editDivision').val(data.studentdetail.division_id);
                            $('#studentid').val(data.studentdetail.id);
                            $('#editModal').modal('show');
                            // location.reload();

                    } else {
                        alert('No student details found.');
                    }
                },
                error: function() {
                    alert('Error fetching student data.');
                }
            });

            
            
        });
        $('#editForm').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    // Submit the form via AJAX
    $.ajax({
        url: $(this).attr('action'),    // Use the form's action attribute URL
        type: 'POST',
        data: $(this).serialize(),       // Serialize the form data
        success: function(response) {
            // Handle the successful response, then reload the page
            console.log("Form submitted successfully.");
            location.reload();           // Refresh the page
        },
        error: function(xhr) {
            // Handle any errors
            console.log("Error submitting form:", xhr.responseText);
        }
    });
});
    </script>
</x-app-layout>