@include('sidebar_display')
<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>Gujarati PDF Example</title>
    <style>
        body {
            font-family: 'Noto Sans Gujarati', sans-serif;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati&display=swap" rel="stylesheet">
</head>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<style> 
</style>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-8 col-lg-7">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">
                    <h1>Filter Marksheet</h1>
                    <div class="border-div">
                        
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
                                    <select name="division" id="division" class="form-control">
                                        <option value="">Select a Division</option>
                                        <!-- Populated via AJAX -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="exam">Select exam:</label>
                                    <!-- <select name="exam_id[]" id="exam" class="form-control" multiple>
                                    </select> -->
                                    <div id="examd" class="form-control checkbox-container">
                                
                                    </div>
                                </div>

                               


                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-success">Get StudentList</button> 
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>
                    

                    <div class="table-container">
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
                    <div class="col-md-8"></div>
                    <div class="col-md-2"><input type="text" id="searchInput" class="form-control mb-3" placeholder="Search..." style="display: none;"></div>
                    <div class="col-md-2"> <div class="button-div" style="display: none;"><button type="button" style="float: right;" class="btn btn-success btn-result  mb-2" >Generate Final Result</button></div>
                    </div>
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
                    </x-app-layout>
                    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati&display=swap" rel="stylesheet">
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
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
                        $('#standard').change(function() {
                            $('#examd').empty();
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
                                        let selectAllCheckbox = document.createElement('input');
                                        selectAllCheckbox.type = 'checkbox';
                                        selectAllCheckbox.className = 'form-check-input';
                                        selectAllCheckbox.id = 'selectAll';

                                        let selectAllLabel = document.createElement('label');
                                        selectAllLabel.className = 'form-check-label';
                                        selectAllLabel.htmlFor = 'selectAll';
                                        selectAllLabel.innerText = 'Select All';

                                        // Append the checkbox and label to a container
                                        let container = document.getElementById('examd'); // Replace with your container ID
                                        container.appendChild(selectAllCheckbox);
                                        container.appendChild(selectAllLabel);
                                        $.each(data, function(key, value) {
                                            
                                            $('#examd').append('<div class="form-check">' +
                                                '<input class="form-check-input" type="checkbox" name="exam_id[]" id="exam" value="' + value.id + '">' +
                                                '<label class="form-check-label" for="exam_' + value.id + '">' + value.exam_name + '</label>' +
                                            '</div>');
                                        });
                                        // Dynamically create the "Select All" checkbox


                                        $('#selectAll').on('change', function () {
                                            const isChecked = $(this).is(':checked');
                                            $('input[name="exam_id[]"]').prop('checked', isChecked);
                                        });
                                    }
                                });
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
                            } 
                        });
                        $('form').submit(function(event) {
                            $("#searchInput").css('display', 'block');

                            event.preventDefault(); 
                            var standardValue= $('#standard').val();
                            var divisionValue= $('#division').val();
                            var examValue= $('#exam').val(); 
                            sessionStorage.setItem('standard', standardValue);
                            sessionStorage.setItem('division', divisionValue);
                            sessionStorage.setItem('exam', examValue);
                            let errors="";
                            $("#validationErrors").html("");
                            var standardValue= $('#standard').val();
                            var divisionValue= $('#division').val();
                           

                            // var examValue= $('#exam').val(); 
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
                                var exam_id = [];
                                $('input[type="checkbox"]:checked').each(function () {
                                    exam_id.push($(this).val());
                                });

                                $.ajax({
                                    url: `/students/getfinalstudent?page=${page}`, // Append page parameter to URL
                                    type: 'POST',
                                    data: {
                                        standard: standardValue,
                                        division: divisionValue,
                                        exam: examValue,
                                        exam_ids: exam_id,
                                        _token: '{{ csrf_token() }}',
                                    },
                                    beforeSend: function () {
                                        $("#dev-loader").show();
                                    },
                                    complete: function () {
                                        $("#dev-loader").hide();
                                    },
                                    success: function (data) {
                                        // Clear the table
                                        $('table tbody').html("");

                                        if (data.students && data.students.length > 0) {
                                            $.each(data.students, function (key, value) {
                                                var baseUrl = "{{ url('marksheet/sidhi_gun') }}";
                                                var studentRow = `
                                                    <tr class="student-row" data-id="${value.id}">
                                                        <td><input type="checkbox" class="student-checkbox" data-id="${value.id}"></td>
                                                        <td>${value.name}</td>
                                                        <td>
                                                            <form action="${baseUrl}" method="post">
                                                                @csrf
                                                                <input type="hidden" value="${value.id}" name="id">
                                                                <input type="hidden" value="${exam_id}" name="exam_id">
                                                                <input type="submit" name="submit" value="View" class="btn btn-success">
                                                            </form>
                                                        </td>
                                                    </tr>
                                                `;
                                                $('#studentdata tbody').append(studentRow);
                                            });

                                            // Render Pagination
                                            renderPagination(data.pagination, data.divisionId, data.exam_id);
                                        } else {
                                            $('#studentdata tbody').html('<tr><td colspan="3">No data found!</td></tr>');
                                        }

                                        // Table Header Setup
                                        $('#studentdata thead tr').html(`
                                            <th style="width:49px;"><input type="checkbox" id="selectAllstd">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th style="width:375px">Student Name</th>
                                            <th style="width:49px;">Action</th>
                                        `);
                                        $('.button-div').show();  
                                        // Select All Checkbox
                                        $('#selectAllstd').on('click', function () {
                                            var isChecked = this.checked;
                                            $('.student-checkbox').prop('checked', isChecked);
                                        });

                                        // Individual Checkbox
                                        $('#studentdata').on('click', '.student-checkbox', function () {
                                            $('#selectAllstd').prop(
                                                'checked',
                                                $('.student-checkbox:checked').length === $('.student-checkbox').length
                                            );
                                        });

                                    
                                                                            




                                        
                                    },
                                    error: function () {
                                        $('#studentdata tbody').html('<tr><td colspan="3">No data found!</td></tr>');
                                    },
                                });
                            }

                            function renderPagination(pagination, division, exam_id) {
                                let paginationHtml = '';

                                if (pagination.prev_page_url) {
                                    paginationHtml += `
                                        <button class="pagination-button btn btn-success"  data-page="${pagination.current_page - 1}">&laquo; Prev</button>
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

                            // Initial Fetch
                            fetchStudents();
                        });

                        $('.generateResultButton').on('click' , function() { 
                            var selectedStudentIds = []; 
                            $('.student-checkbox:checked').each(function() {
                                selectedStudentIds.push($(this).data('id'));
                            });
                            var standard = sessionStorage.getItem('standard');
                            var division = sessionStorage.getItem('division');
                            // var exam = sessionStorage.getItem('exam');
                            var exam_id = [];
                            $('input[type="checkbox"]:checked').each(function () {
                                exam_id.push($(this).val());
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
                                url: '/student/final-marksheet',  
                                method: 'POST',
                                data: {
                                    student_id: selectedStudentIds,
                                    standard : standard,
                                    division : division,
                                    exam : exam_id,
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
                            // var exam = sessionStorage.getItem('exam');
                            var exam_id = [];
                            $('input[type="checkbox"]:checked').each(function () {
                                exam_id.push($(this).val());
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
                                url: '/student/final-marksheet-guj',  
                                method: 'POST',
                                data: {
                                    student_id: selectedStudentIds,
                                    standard : standard,
                                    division : division,
                                    exam : exam_id,
                                    _token: '{{ csrf_token() }}',  
                                },
                                beforeSend: function() { 
                                    $("#dev-loader").show();
                                },
                                complete: function() { 
                                    $("#dev-loader").hide();
                                },
                                success: function(response) {

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
                    });


                        </script>
                        
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

<script>
            const { jsPDF } = window.jspdf;

   async function generatePDF(response) {
    console.log(response.student);
    const content = document.getElementById("content");
    content.innerHTML = response.student;

                const options = {
                    filename: 'student_report.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 3 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };
                html2pdf().from(content).set(options).save();
   }

</script>