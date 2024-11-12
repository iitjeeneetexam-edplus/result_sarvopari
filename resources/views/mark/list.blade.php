<!-- resources/views/list_schools.blade.php -->
@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-sm-8 col-md-8">
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
                            <div class="col-md-4">
                                <label for="exam_id">Select Exam:</label>
                                <select name="exam_id" id="exam" class="form-control">
                                    <option value="">Select a Exam</option>
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
                        $('#standard').change(function() {
                            var standardId = $(this).val();
                            if (standardId) {
                                $.ajax({
                                    url: '{{ url("/get-exam") }}/' + standardId,
                                    type: 'GET',
                                    success: function(data) {
                                        $('#exam').empty().append('<option value="">Select a Exam</option>');
                                        $.each(data, function(key, value) {
                                            $('#exam').append('<option value="' + value.id + '">' + value.exam_name + '</option>');
                                        });
                                    }
                                });
                            } else {
                                $('#exam').empty().append('<option value="">Select a Exam</option>');
                            }
                        });
                        $('form').submit(function(event) {
                            event.preventDefault(); 
                            $.ajax({
                                url: '/students/getstudentformarks',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: function(data) {
                                    if(data.student !=null){

                                    $('table tbody').empty();
                                    $.each(data.student, function(key, value) {
                                        var studentRow = `<tr class="student-row" data-id="${value.id}">`+
                                            '<td><input type="checkbox" class="student-checkbox" data-id="' + value.id + '"></td>' +
                                            '<td>' + value.id + '</td>' +
                                            '<td>' + value.name + '</td>' +
                                            '<td>' + value.roll_no + '</td>' +
                                            '<td>' + value.GR_no + '</td>';
                                           
                                        if (data.subject != null) {
                                            var subjectsArray = data.subject.split(',');
                                            $.each(subjectsArray, function(index, subjectName) {
                                                studentRow += `<td>
                                                                <input type="hidden" name="is_optional[]" value="${value.is_optional[subjectName.trim()] || ''}">
                                                                <input type="hidden" name="mark_id[]" value="${value.mark_id[subjectName.trim()] || ''}">
                                                                ${value.marks[subjectName.trim()] || ''}
                                                            </td>`;
                                                            });
                                        }
                                        studentRow += `<td><button class="openModalBtn btn btn-success" data-id="${value.id}" data-division-id="${value.division_id}" >Edit</button>
                                        </td>`;
                                        //&nbsp&nbsp<button class="openBtndelete btn btn-danger" data-id="${value.id}">Delete</button>
                                        studentRow += '</tr>';
                                        $('#studentdata tbody').append(studentRow);
                                       
                                    });
                                }else{
                                    // $('#studentdata tr').append('<th>No data Found!</th>');
                                }
                               
                                       
                                    // Header section
                                    $('#studentdata thead tr').empty();
                                    $('#studentdata thead tr').append('<th style="width:140px"><input type="checkbox" id="selectAll">&nbsp;&nbsp;&nbsp;&nbsp;<button id="" class="btn btn-success generateResultButton">Result</button></th>');
                                    // Static headers
                                    $('#studentdata thead tr').append('<th>No</th>');
                                    $('#studentdata thead tr').append('<th>Student Name</th>');
                                    $('#studentdata thead tr').append('<th>Roll No</th>');
                                    $('#studentdata thead tr').append('<th>GR No</th>');
                                    $('#selectAll').on('click', function() {
                                            var isChecked = this.checked;
                                            $('.student-checkbox').prop('checked', isChecked);
                                        });

                                        // Individual Student Checkbox Click
                                        $('#studentdata').on('click', '.student-checkbox', function() {
                                            if ($('.student-checkbox:checked').length === $('.student-checkbox').length) {
                                                $('#selectAll').prop('checked', true);
                                            } else {
                                                $('#selectAll').prop('checked', false);
                                            }
                                        });
                                  
                                    if (data.subject != null) {
                                        var subjectsArray = data.subject.split(',');
                                        var totalmarks = data.total_marks;

                                        $.each(subjectsArray, function(index, subjectName) {
                                            var marks = (totalmarks[index] && totalmarks[index].total_marks !== undefined && totalmarks[index].total_marks !== '') 
                                                        ? totalmarks[index].total_marks 
                                                        : '';

                                            $('#studentdata thead tr').append('<th>' + subjectName.trim() + ' (' + marks + ')</th>');
                                        });
                                    }
                                    $('#studentdata thead tr').append('<th>Action</th>');
                                },
                                error: function(xhr, status, error) {
                                    $('#studentdata thead tr').append('<th>No data Found!</th>');
                                }
                            });
                        });
                    });
                    $(document).on('click', '.openBtndelete', function() {
                        var studentId  = $(this).data('id');
                        var row = $(this).closest('.student-row');
                            if (confirm('Are you sure you want to delete this?')) {
                            $.ajax({
                                    url: '/marks/delete/' + studentId ,
                                    type: 'GET',
                                    success: function(data){
                                        $('#studentdata tbody').empty();
                                        $('form').submit();  
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error fetching data:', error);
                                    }
                        });
                      }
                    });
                    $(document).on('click', '.openModalBtn', function() {
                            var studentId  = $(this).data('id');
                            localStorage.setItem('studentId', JSON.stringify(studentId));

                            var divisionId = $(this).data('division-id');
                            var row = $(this).closest('.student-row');
                            // let tdElement = row.find('td').eq(5);
                            // let isOptionalValues = [];

                            // tdElement.find('input[name="is_optional[]"]').each(function(_, input) {
                            //     isOptionalValues.push(input.value);
                            // });

                            // console.log(isOptionalValues);

                            $.ajax({
                                url: '/marks/edit/' + studentId + '/' + divisionId, 
                                type: 'GET',
                                success: function(data) {
                                    row.hide();
                                    var editRow = `<tr class="edit-row" data-id="${studentId}">
                                        <td>${studentId}</td>
                                        <td><button class="btn btn-success">Result</button></td>
                                        <td><label>${row.find('td').eq(2).text()}</label></td>
                                        <td><label>${row.find('td').eq(3).text()}</label></td>
                                        <td><label>${row.find('td').eq(4).text()}<label></td>`;
                                        // alert(data.subject_ids);
                                      // Split main and optional subject IDs
                                      var is_optional = row.find('input[name="is_optional[]"]').map(function() {
                                            return $(this).val(); // Collect each value into an array
                                        }).get();
                                        var mark_id = row.find('input[name="mark_id[]"]').map(function() {
                                            return $(this).val(); // Collect each value into an array
                                        }).get();
                                        // console.log(marks_id);

                                        console.log(is_optional);
                                    var optional_subject_ids_get = data.optional_subject_ids.split(',');
                                    var main_subject_ids_get = data.main_subject_ids.split(',');

                                    // Define the arrays for subjects and marks
                                    var subjectsArray = data.optional_subject != null ? data.optional_subject.split(',') : [];
                                    var mainIndex = 0, optionalIndex = 0;

                                    // Loop through each subject
                                    $.each(subjectsArray, function(index, subjectName) {
                                        // Check if the cell has text
                                        var cellText = row.find('td').eq(5 + index).text().trim();
                                        // editRow += `<tr>`; // Wrap each set in a new table row for alignment

                                        // Add Main Subject ID

                                        if (optionalIndex < optional_subject_ids_get.length) {
                                            editRow += `<input type="hidden" class="form-control" value="${optional_subject_ids_get[optionalIndex]}" name="optional_subject_id[]">`;
                                            optionalIndex++;
                                        }

                                        if (mainIndex < main_subject_ids_get.length) {
                                            editRow += `<input type="hidden" class="form-control" value="${main_subject_ids_get[mainIndex]}" name="main_subject_id[]">`;
                                            mainIndex++;
                                        }
                                         editRow +=`<input type="hidden"  style="width:150px; justify-self:center" class="form-control" value="${is_optional[index]}" name="is_optional[]" />`;
                                        // Add Optional Subject ID
                                        editRow += `<input type="hidden"  style="width:150px; justify-self:center" class="form-control" value="${mark_id[index]}" name="marks_id[]" />`;
                                        // Add Marks input field or blank value if cellText is empty
                                        editRow += `<td>`;
                                        if (cellText != '') {
                                            
                                            editRow += `<input type="text" id="myTextbox" data-mark_index="${index}" style="width:150px; justify-self:center" class="form-control" value="${cellText}" name="marks[]" />`;
                                        } 
                                        // else {
                                        //     editRow += `<input type="text" id="myTextbox" style="width:150px; justify-self:center" class="form-control" name="marks[]" />`;
                                        // }
                                        editRow += `</td>`;
                                    });

                                    // Add error message outside the loop for all fields
                                    editRow += `<span id="errorMessage" style="color: red; display: none;">This field is required.</span>`;

                                            // Arrays to store main subject IDs, optional subject IDs, and marks
                                       
                                           

                                    editRow += `<td><div class="d-flex"><button type="button" class="btn btn-warning cancelEditBtn">Back</button>&nbsp&nbsp<button type="button" class="btn btn-success saveEditBtn">Update</button></div></td></tr>`;
                                    
                                    row.after(editRow);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error fetching data:', error);
                                    alert('Error fetching data');
                                }
                            });
                        });

                                $(document).on('click', '.cancelEditBtn', function() {
                                    var editRow = $(this).closest('.edit-row');
                                    var row = editRow.prev('.student-row'); 

                                    editRow.toggle();
                                    row.toggle();
                                });

                                                                
                                $(document).on('click', '.saveEditBtn', function() {
                                    //validation 
                                    event.preventDefault(); 
                                    // var markIndex = $(this).attr('data-mark_index');

                                    

                                    var regex = /^[0-9]+$/;
                                    var textboxValue = $('#myTextbox').val();
                                    if (textboxValue === '') {
                                       
                                        $('#myTextbox').css('border', '2px solid red');
                                        $('#myTextbox').addClass('invalid');
                                        $('#errorMessage').show(); 
                                        setTimeout(function() {
                                            $('#myTextbox').removeClass('invalid');
                                        }, 500);
                                        return; // Show error message
                                    } else if (!regex.test(textboxValue)) {
                                            $('#myTextbox').addClass('invalid');
                                            $('#errorMessage').text('Please enter only integer values (no decimals or symbols).').show();
                                            return;
                                        } else if (parseInt(textboxValue) < 0 || parseInt(textboxValue) > 100) {
                                            $('#myTextbox').addClass('invalid');
                                            $('#errorMessage').text('Please enter a value between 0 and 100.').show();
                                            return;
                                        } else {
                                        // Remove red border if textbox is not empty
                                        $('#myTextbox').removeClass('invalid');
                                        $('#errorMessage').hide();
                                        $('#myTextbox').css('border', ''); // Reset border style
                                    }

                                    
                                    var editRow = $(this).closest('.edit-row'); // Store reference to the edit row
                                    var row = editRow.prev('.student-row');     
                                    var studentId = localStorage.getItem('studentId');
                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                                    var row = $(this).closest('.student-row');

                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        }
                                    });
                            
                                    // let mainSubjectIds = $('input[name="main_subject_id[]"]').map(function() {
                                    //     return $(this).val();
                                    // }).get();
                                    // let optional_subject_id = $('input[name="optional_subject_id[]"]').map(function() {
                                    //     return $(this).val();
                                    // }).get();
                                    // let is_optional = $('input[name="is_optional[]"]').map(function() {
                                    //     return $(this).val();
                                    // }).get();
                                    let marks = $('input[name="marks[]"]').map(function() {
                                        return $(this).val();
                                    }).get();
                                    let mark_id = $('input[name="marks_id[]"]').map(function() {
                                        return $(this).val();
                                    }).get();
                                    // console.log(mainSubjectIds);
                                    // console.log(optional_subject_id);
                                    // console.log(is_optional);
                                    console.log(marks);
                                    console.log(mark_id);
                                    let formData = {
                                        'mark_id[]': mark_id,
                                        'marks[]': marks,
                                        // You can add other fields if needed
                                    };
                                      
                                    
                                 
                                    $.ajax({
                                        url: '/marks/update', // The URL for updating marks
                                        type: 'POST',
                                        data: formData,
                                        success: function(response) {
                                            if(response == '1'){
                                                $('form').submit();  
                                            }
                                            // row.hide();
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error saving data:', error);
                                        }
                                    });
                                });


                //    function generatepdf(studentId,examId){
                //      $.ajax({
                //         url: "{{ route('students.marksheet') }}",
                //         type: 'POST',
                //         data: {
                //             _token: '{{ csrf_token() }}',
                //             exam_id: examId,
                //             student_id: studentId
                //         },
                //         success: function (response) {
                //             console.log(response);
                //             if (response.pdfUrl) {
                //                 window.open(response.pdfUrl, '_blank');
                //             } else {
                //                 alert('Failed to generate PDF.');
                //             }
                //         },
                //         error: function () {
                //             alert('Error while processing request.');
                //         }
                //     });
                //    }
                $('#studentdata').on('click', '.generateResultButton', function() {
                            var selectedStudentIds = [];

                            $('.student-checkbox:checked').each(function() {
                                selectedStudentIds.push($(this).data('id'));
                            });

                            if (selectedStudentIds.length === 0) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Result",
                                    text: "Please select one or more student!",
                                    });
                                return;
                            }

                            $.ajax({
                                url: '/student/marksheet',  
                                method: 'POST',
                                data: {
                                    student_id: selectedStudentIds,
                                    _token: '{{ csrf_token() }}',  
                                },
                                success: function(response) {
                                    console.log(response.pdfUrl);
                                    window.open(response.pdfUrl, '_blank');
                                },
                                error: function() {
                                    Swal.fire({
                                    icon: "error",
                                    title: "Result",
                                    text: "An error occurred while generating results!",
                                    });
                                return;
                                }
                            });
                        });
                </script>
</x-app-layout>
<style>
    /* Basic styling for the textbox */
input.form-control {
    width: 100%;
    padding: 10px;
    margin: 20px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    position: relative;
}

/* Red border and shaking effect when invalid */
input.form-control.invalid {
    border: 2px solid red;
    animation: shake 0.5s ease; /* Apply the shake animation */
}

/* Error message styling */
#errorMessage {
    color: red;
    font-size: 12px;
    display: none; /* Hidden by default */
    position: absolute;
    top: -20px; /* Move above the textbox */
    left: 0;
    width: 100%;
    text-align: left;
}

/* Shaking animation */
@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(5px); }
}

/* Move input label up on focus (for a floating label effect) */
input.form-control:focus + #errorMessage {
    display: block; /* Show the error message when the input is focused */
}

</style>