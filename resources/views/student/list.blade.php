@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-sm-6 col-md-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">
                    <h2>Student Assign Subject</h2>
                <br>   <form method="POST" action="{{ route('assign.subject') }}">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="select-all">
                                                </th>
                                                <th>No</th>
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
                                            @php $i = 1; @endphp
                                            @if($students->isNotEmpty())
                                            @foreach($students as $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="student_ids[]" class="student-checkbox" value="{{ $student->id }}"> 
                                                </td>
                                                <td>{{ $i }}</td>
                                                <td>{{ $student->roll_no }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->GR_no }}</td>
                                                <td>{{ $student->uid }}</td>
                                                <td>{{ $student->division->division_name }}</td>
                                                @php
                                                $subjectNames = explode(',', $student->subject_id); // Split the subject names
                                                
                                                @endphp
                                                @if(!empty($subjectNames))
                                                    @foreach($subjects as $optionls) 
                                                    @foreach($subject_subs as $subject_subsoptions)
                                                        @foreach($subject_subsoptions as $subjectSub)
                                                        
                                                        @if($optionls->id == $subjectSub->subject_id && in_array($subjectSub->id,$subjectNames))
                                                        <td>{{ $subjectSub->subject_name }}</td>
                                                       
                                                        @endif
                                                        @endforeach
                                                        @endforeach
                                                    
                                                    @endforeach
                                                    @for ($j = count($subjectNames); $j < count($subjects); $j++)
                                                    <td></td> 
                                                @endfor
                                                @else
                                                <span>No subjects assigned</span>
                                                @endif
                                                <td>
                                                    <a href="#" class="btn btn-sm edit-btn btn-success" data-student-id="{{ $student->id }}">Edit</a>
                                                    <a  class="btn btn-sm delete-btn btn-danger" href="javascript:void(0);" onclick="confirmDelete({{ $student->id }})">Delete</a>
                                                </td> 
                                            </tr>
                                            @php $i++; @endphp
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
                                        <select name="subject_ids[]" id="subject" class="form-control">
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
                                @php $i++; @endphp
                                @endforeach
                           
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success mb-3" style="float:right" id="submitBtn">Assign Subject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                
                document.getElementById('submitBtn').onclick = function(event) {
                    let isValid = false; // Flag to track validation
                    const selects = document.querySelectorAll('select[name="subject_ids[]"]'); // Select all subject select elements

                    // Check if at least one select has a value
                    selects.forEach((select) => {
                        if (select.value !== '') {
                            isValid = true; // If one select has a value, set isValid to true
                        }
                    });

                    // If no option is selected, prevent form submission
                    if (!isValid) {
                        event.preventDefault(); // Prevent form submission
                        Swal.fire({
                            icon: "error",
                            title: "Result",
                            text: "Please select one Option!",
                            });
                        return;
                    }
                };
            </script>
       
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                       <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
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
                    <button type="submit" class="btn btn-success">Save</button>
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
            $('.student-checkbox').prop('checked', this.checked);
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
                beforeSend: function() { 
                            $("#dev-loader").show();
                },
                complete: function() { 
                    $("#dev-loader").hide();
                },
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

        //delete
        function confirmDelete(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: true
    });

    swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "Are you sure want to delete student!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.get(`{{ url('students/delete') }}/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            })
            .then(response => {
                 swalWithBootstrapButtons.fire({
                    title: "Deleted!",
                    text: "Your student has been deleted.",
                    icon: "success"
                }).then(() => {
                   window.location.href = "{{ route('students.index') }}";
                });
            })
            .catch(error => {
                swalWithBootstrapButtons.fire(
                    "Error!",
                    "There was a problem deleting the student.",
                    "error"
                );
            });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Your student is safe ",
                icon: "error"
            });
        }
    });
}
        

        $('#editForm').on('submit', function(e) {
        e.preventDefault(); 

        $.ajax({
            url: $(this).attr('action'),  
            type: 'POST',
            data: $(this).serialize(), 
            beforeSend: function() { 
                $("#dev-loader").show();
            },
            complete: function() { 
                $("#dev-loader").hide();
            },      
            success: function(response) {
                console.log("Form submitted successfully.");
                location.reload();          
            },
            error: function(xhr) {
                console.log("Error submitting form:", xhr.responseText);
            }
        });

        
        
});
    </script>
</x-app-layout>