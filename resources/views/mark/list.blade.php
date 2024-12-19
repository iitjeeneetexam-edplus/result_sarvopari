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
                    <div class="border-div">
                        
                        <div class="row">
                            <div class="col-sm-2 offset-sm-10"><a href="{{ url('marks/create') }}" class="btn btn-success mb-3" style="float: right;">Add New Mark</a>
                            </div>
                        </div>
                        <form method="POST">
                        <ul id="validationErrors" style="list-style: disc;   color: red;"></ul>
                            @csrf
                            <div class="row mb-4">
                            <div class="form-group">
                                <input type="hidden" name="school_id" id="school_id" value="{{ $schools->id}}">
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
                    </div>
                    <hr/>
                <div class="table-container">
                    <div class="button-div" style="display: none;"><button type="button" class="btn btn-success btn-result mb-2" >Generate Results</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-warning generatePdfButton mb-2" style="float:right">Generate Pdf</button></div>
                    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="resultModalLabel">Generate Final Result</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    
                                   <div class="d-flex">
                                   <label><b>Select Lunguage : </b></label>&nbsp;&nbsp;
                                    <a href="#" class="btn btn-success generateResultButton_guj">Gujarati</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="#" class="btn btn-success generateResultButton">English</a>
                                   </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3"><input type="text" id="searchInput" class="form-control mb-3" placeholder="Search..." style="display: none;"></div>
                </div>

                    <table class="table table-bordered" id="studentdata">
                        <thead class="thead-dark">
                            <tr>

                            </tr>
                        </thead>
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                    <div id="pagination-links" style="float:right"></div>
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                </div>
                </div>
            </div></div>
    </div>
    <script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');
        
        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                        $(document).ready(function () {
                    $('.btn-result').on('click', function (e) {
                        e.preventDefault();

                        if ($('.student-checkbox:checked').length > 0) {
                            const resultModal = new bootstrap.Modal($('#resultModal'));
                            resultModal.show();
                        } else {
                            alert('Please select at least one exam to Student.');
                        }
                    });

                });
                    $(document).ready(function() {
                        // School change event for fetching standards
                        function loadStandards(schoolId) {
                            // var schoolId = $(this).val();
                            if (schoolId) {
                                $.ajax({
                                    url: '{{ url("/get-standards") }}/' + schoolId,
                                    type: 'GET',
                                    beforeSend: function() { 
                                        $("#dev-loader").show();
                                    },
                                    complete: function() { 
                                        $("#dev-loader").hide();
                                    },   
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
                        var preSelectedSchoolId = $('#school_id').val();
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
                                    beforeSend: function() { 
                                        $("#dev-loader").show();
                                    },
                                    complete: function() { 
                                        $("#dev-loader").hide();
                                    },

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
                                    beforeSend: function() { 
                                        $("#dev-loader").show();
                                    },
                                    complete: function() { 
                                        $("#dev-loader").hide();
                                    },
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
                            $("#searchInput").css('display', 'block');

                            event.preventDefault(); 
                            let errors="";
                            $("#validationErrors").html("");
                            var standardValue= $('#standard').val();
                            var divisionValue= $('#division').val();
                            

                            var examValue= $('#exam').val(); 
                            sessionStorage.setItem('standard', standardValue);
                            sessionStorage.setItem('division', divisionValue);
                            sessionStorage.setItem('exam', examValue);
                            if(!$.trim(standardValue))
                                errors+="<li>Please select standard.</li>"
                            if(!$.trim(divisionValue))
                                errors+="<li>Please select division.</li>"
                            if(!$.trim(examValue))
                                errors+="<li>Please select exam.</li>"
                            if($.trim(errors))
                        {
                            $("#validationErrors").html(errors);
                            return;
                        }
                            
                          
                        function fetchStudents(page = 1) {
                            $.ajax({
                                url: `/students/getstudentformarks?page=${page}`,
                                type: 'POST',
                                data: {
                                    standard_id: standardValue,
                                    division_id: divisionValue,
                                        exam_id: examValue,
                                        _token: '{{ csrf_token() }}',
                               
                                },
                                   beforeSend: function() { 
                                        $("#dev-loader").show();
                                    },
                                    complete: function() { 
                                        $("#dev-loader").hide();
                                    },
                                    success: function (data) {
                                        

    // Optional: Pass the processed data to your UI
    // renderUI(subjectTotals, data.student2);



                                    if(data.student !=null){

                                    $('table tbody').html("");
                                    $.each(data.student, function(key, value) {
                                        var studentRow = `<tr class="student-row" data-id="${value.id}">`+
                                            '<td ><input type="checkbox" class="student-checkbox" data-id="' + value.id + '" ></td>' +
                                            '<td>' + value.roll_no + '</td>' +
                                            '<td>' + value.name + '</td>' +
                                            '<td>' + value.GR_no + '</td>';
                                           
                                        if (data.subject != null) {
                                            var subjectsArray = data.subject.split(',');
                                            $.each(subjectsArray, function(index, subjectName) {
                                                studentRow += `<td>
                                                                <input type="hidden" name="is_optional[]" value="${value.is_optional[subjectName.trim()] || ''}">
                                                                <input type="hidden" name="mark_id[]" value="${value.mark_id[subjectName.trim()] || ''}">${value.marks[subjectName.trim()] || ''}
                                                               </td>`;
                                                            });
                                        }
                                        studentRow += `<td><button class="openModalBtn btn btn-success" data-id="${value.id}" data-division-id="${value.division_id}">Edit</button>
                                        </td>`;
                                        //&nbsp&nbsp<button class="openBtndelete btn btn-danger" data-id="${value.id}">Delete</button>
                                        studentRow += '</tr>';
                                        var standard=sessionStorage.getItem('standard');
                                        var division=sessionStorage.getItem('division');
                                        var exam=sessionStorage.getItem('exam');
                                       
                                        $('#studentdata tbody').append(studentRow);
                                        renderPagination(data.pagination, division, exam);
                                       
                                    });
                                }else{
                                    // $('#studentdata tr').append('<th>No data Found!</th>');
                                }
                               
                                   
                                    // Header section
                                    $('#studentdata thead tr').empty();
                                    $('.button-div').show();   
                                    $('#studentdata thead tr').append('<th style="width:49px;"><input type="checkbox" id="selectAll">&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                    // Static headers
                                    $('#studentdata thead tr').append('<th style="width:46px"><center>Roll No</center></th>');
                                    $('#studentdata thead tr').append('<th style="width:375px"><center>Student Name</center></th>');
                                   
                                    $('#studentdata thead tr').append('<th><center>GR No</center></th>');
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

                                        $.each(totalmarks, function(subjectName, marks) {
                                            marks = marks === null ? "" : marks; 

                                            let subjects = subjectName.split(','); 

                                            $.each(subjects, function(index, subject) {
                                                subject = subject.trim(); 

                                                $('#studentdata thead tr').append(
                                                    '<th>' + subject + '<center><br> (' + marks + ')</center></th>'
                                                );
                                            });
                                        });
                                    }
                                    $('#studentdata thead tr').append('<th><center>Action</center></th>');



                                   //rank generate
                                  if (data.rank_calculation_student && data.rank_calculation_student.length > 0) {
    let students = data.rank_calculation_student; // Use rank_calculation_student directly
    let subjectTotals = {};

    students.forEach(student => {
        let totalMarks = 0;
         console.log(student); 
        if (student.marks) {
            for (const [subject, marks] of Object.entries(student.marks)) {
                let numericMarks = parseInt(marks) || 0;
                totalMarks += numericMarks;

                // Update subject totals
                subjectTotals[subject] = (subjectTotals[subject] || 0) + numericMarks;
            }
        }

        student.totalMarks = totalMarks; // Assign total marks to student
    });

    // Rank students based on total marks
    let rankedStudents = students.sort((a, b) => b.totalMarks - a.totalMarks);

    let rank = 1;
    let previousMarks = null;

    rankedStudents.forEach((student, index) => {
        if (student.totalMarks === previousMarks) {
            student.rank = rank; // Same rank for equal marks
        } else {
            rank = index + 1; // Increment rank
            student.rank = rank;
        }
        previousMarks = student.totalMarks;
    });

    // AJAX to update ranks in Laravel
    $.ajax({
        url: '/student/update-student-ranks',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            students: rankedStudents.map(student => ({
                id: student.student_id, // Student ID
                rank: student.rank // Assigned rank
            }))
        },
        success: function(response) {
            console.log('Ranks updated successfully:', response);
        },
        error: function(xhr, status, error) {
            console.error('Error updating ranks:', error);
        }
    });
}},
                                error: function(xhr, status, error) {
                                    $('#studentdata thead tr').append('<th><center>No data Found!</center></th>');
                                }
                            });
                          }
                        function renderPagination(pagination, division, exam_id) {
                            let paginationHtml = '';

                            if (pagination.prev_page_url) {
                                paginationHtml += `
                                    <button class="pagination-button btn btn-success" data-page="${pagination.current_page - 1}">&laquo; Prev</button>
                                `;
                            }

                            paginationHtml += `<span>Page ${pagination.current_page} of ${pagination.last_page}</span>`;

                            if (pagination.next_page_url) {
                                paginationHtml += `
                                    <button class="pagination-button btn btn-success" data-page="${pagination.current_page + 1}">Next &raquo;</button>
                                `;
                            }

                            $('#pagination-links').html(paginationHtml);

                            // Handle Pagination Clicks
                            $('.pagination-button').on('click', function () {
                                var page = $(this).data('page');
                                fetchStudents(page); // Fetch students for the clicked page
                            });
                        }
                        fetchStudents();
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
                          
                            $.ajax({
                                url: '/marks/edit/' + studentId + '/' + divisionId, 
                                type: 'GET',
                                beforeSend: function() { 
                                        $("#dev-loader").show();
                                    },
                                    complete: function() { 
                                        $("#dev-loader").hide();
                                    },
                                success: function(data) {
                                    row.hide();
                                    var editRow = `<tr class="edit-row" data-id="${studentId}">
                                        <td></td>
                                        <td><label>${row.find('td').eq(1).text()}</label></td>
                                        <td><label>${row.find('td').eq(2).text()}</label></td>
                                        <td><label>${row.find('td').eq(3).text()}</label></td>`;
                                      var is_optional = row.find('input[name="is_optional[]"]').map(function() {
                                            return $(this).val(); // Collect each value into an array
                                        }).get();
                                        var mark_id = row.find('input[name="mark_id[]"]').map(function() {
                                            return $(this).val(); // Collect each value into an array
                                        }).get();
                                  
                                    var optional_subject_ids_get = data.optional_subject_ids.split(',');
                                    var main_subject_ids_get = data.main_subject_ids.split(',');

                                    var subjectsArray = data.optional_subject != null ? data.optional_subject.split(',') : [];
                                    var mainIndex = 0, optionalIndex = 0;
                                    $.each(subjectsArray, function(index, subjectName) {
                                        if (row && row.find('td').length > (4 + index)) {
                                            var cellText = row.find('td').eq(4 + index).text().trim();
                                        }
                                        if (optionalIndex < optional_subject_ids_get.length) {
                                            editRow += `<input type="hidden" class="form-control" value="${optional_subject_ids_get[optionalIndex]}" name="optional_subject_id[]">`;
                                            optionalIndex++;
                                        }
                                        if (mainIndex < main_subject_ids_get.length) {
                                            editRow += `<input type="hidden" class="form-control" value="${main_subject_ids_get[mainIndex]}" name="main_subject_id[]">`;
                                            mainIndex++;
                                        }
                                         editRow +=`<input type="hidden"  style="width:150px; justify-self:center" class="form-control" value="${is_optional[index]}" name="is_optional[]" />`;
                                        editRow += `<input type="hidden"  style="width:150px; justify-self:center" class="form-control" value="${mark_id[index]}" name="marks_id[]" />`;
                                        editRow += `<td>`;
                                        if (cellText != '') {
                                            editRow += `<input type="text" id="myTextbox"  style="width:150px; justify-self:center" class="form-control" value="${cellText}" name="marks[]" />`;
                                        } 
                                        // else {
                                        //     editRow += `<input type="text" id="myTextbox" style="width:150px; justify-self:center" class="form-control" name="marks[]" />`;
                                        // }
                                        editRow += `</td>`;
                                    });

                                    editRow += `<span id="errorMessage" style="color: red; display: none;">This field is required.</span>`;
                                    editRow += `<td><div class="d-flex"><button type="button" class="btn btn-warning cancelEditBtn">Back</button>&nbsp&nbsp<button type="button" class="btn btn-success saveEditBtn">Update</button></div></td></tr>`;
                                    row.after(editRow);
                                },
                                error: function(xhr, status, error) {
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

                                    

                                    var regex = /^(100|[1-9]?[0-9]|AB)$/;
                                    var textboxValue = $('#myTextbox').val();
                                    if (textboxValue === '') {
                                        $('#myTextbox').css('border', '2px solid red');
                                        $('#myTextbox').addClass('invalid');
                                        $('#errorMessage').show(); 
                                        setTimeout(function() {
                                            $('#myTextbox').removeClass('invalid');
                                        }, 500);
                                        return; 
                                    } else if (!regex.test(textboxValue)) {
                                            $('#myTextbox').addClass('invalid');
                                            $('#errorMessage').text('Please enter only integer values (no decimals or symbols).').show();
                                            return;
                                        } else if (parseInt(textboxValue) < 0 || parseInt(textboxValue) > 100) {
                                            $('#myTextbox').addClass('invalid');
                                            $('#errorMessage').text('Please enter a value between 0 and 100.').show();
                                            return;
                                        } else {
                                        $('#myTextbox').removeClass('invalid');
                                        $('#errorMessage').hide();
                                        $('#myTextbox').css('border', ''); 
                                    }
                                    var editRow = $(this).closest('.edit-row'); 
                                    var row = editRow.prev('.student-row');     
                                    var studentId = localStorage.getItem('studentId');
                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                                    var row = $(this).closest('.student-row');

                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        }
                                    });
                                    let marks = $('input[name="marks[]"]').map(function() {
                                        return $(this).val();
                                    }).get();
                                    let mark_id = $('input[name="marks_id[]"]').map(function() {
                                        return $(this).val();
                                    }).get();
                                    let formData = {
                                        'mark_id[]': mark_id,
                                        'marks[]': marks,
                                    };
                                    $.ajax({
                                        url: '/marks/update', 
                                        type: 'POST',
                                        beforeSend: function() { 
                                        $("#dev-loader").show();
                                        },
                                        complete: function() { 
                                            $("#dev-loader").hide();
                                        },
                                        data: formData,
                                        success: function(response) {
                                            if(response == '1'){
                                                $('form').submit();  
                                            }
                                        },
                                        error: function(xhr, status, error) {
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
                $('.generatePdfButton').on('click' , function (){
                    var students = [];
                    $('#studentdata tbody tr').each(function() {
                        var student = {
                            roll_no: $(this).find('td').eq(1).text(),
                            name: $(this).find('td').eq(2).text(),
                            GR_no: $(this).find('td').eq(3).text(),
                            marks: {}
                        };
                        $(this).find('td').slice(4, -1).each(function(index) {
                            var subjectName = $('#studentdata thead th').eq(index + 4).text().trim();
                            student.marks[subjectName] = $(this).text().trim();
                        });
                        students.push(student);
                    });

                    var subjects = $('#studentdata thead th').slice(4, -1).map(function() {
                        return $(this).text().trim();
                    }).get();

                    $.ajax({
                        url: "{{ route('allgenerate.pdf') }}",
                        type: "POST",
                        data: {
                            students: students,
                            subjects: subjects,
                            _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                        },
                        beforeSend: function() { 
                            $("#dev-loader").show();
                        },
                        complete: function() { 
                            $("#dev-loader").hide();
                        },
                        success: function(response) {
                            if (response) {
                                window.open(response.pdfUrl, '_blank');
                            } else {
                                console.error('Failed to receive PDF data.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error:', status, error);
                        }
                    });
                });
                $('.generateResultButton').on('click' , function() { 
                            var selectedStudentIds = []; 
                            $('.student-checkbox:checked').each(function() {
                                selectedStudentIds.push($(this).data('id'));
                            });
                            var standard = sessionStorage.getItem('standard');
                            var division = sessionStorage.getItem('division');
                            var exam = sessionStorage.getItem('exam');
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
                                    standard : standard,
                                    division : division,
                                    exam : exam,
                                    _token: '{{ csrf_token() }}',  
                                },
                                beforeSend: function() { 
                                    $("#dev-loader").show();
                                },
                                complete: function() { 
                                    $("#dev-loader").hide();
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
                        $('.generateResultButton_guj').on('click' , function() { 
                            var selectedStudentIds = []; 
                            $('.student-checkbox:checked').each(function() {
                                selectedStudentIds.push($(this).data('id'));
                            });
                            var standard = sessionStorage.getItem('standard');
                            var division = sessionStorage.getItem('division');
                            var exam = sessionStorage.getItem('exam');
                            // var exam_id = [];
                            // $('input[type="checkbox"]:checked').each(function () {
                            //     exam_id.push($(this).val());
                            // });
                           
                            
                            if (selectedStudentIds.length === 0) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Result",
                                    text: "Please select one or more student!",
                                    });
                                return;
                            }

                            $.ajax({
                                url: '/student/single-exam-marksheet-guj',  
                                method: 'POST',
                                data: {
                                    student_id: selectedStudentIds,
                                    standard : standard,
                                    division : division,
                                    exam : exam,
                                    _token: '{{ csrf_token() }}',  
                                },
                                beforeSend: function() { 
                                    $("#dev-loader").show();
                                },
                                complete: function() { 
                                    $("#dev-loader").hide();
                                },
                                success: function(response) {
                               
                                    const { jsPDF } = window.jspdf;
                                    const studentContent = response.student;
                                    const baseWidth = 580.28; 
                                    const additionalWidth = 50; 
                                    const totalWidth = baseWidth + additionalWidth; 
                                    const height = 841.89;
                                    const pdf = new jsPDF('p', 'mm', [297, 210]);
                                    const options = {
                                        filename: 'student_report.pdf',
                                        image: { type: 'jpeg', quality: 0.98 },
                                        html2pdf: { scale: 3 },
                                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                                    };
                                    html2pdf().from(studentContent).set(options).save();
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

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
