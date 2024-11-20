@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<style> 
</style>
    <div class="row justify-content-center">
        <div class="col-lg-8 col-sm-8 col-md-8">
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
                                    <select name="division_id" id="division" class="form-control">
                                        <option value="">Select a Division</option>
                                        <!-- Populated via AJAX -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="exam">Select exam:</label>
                                    <select name="exam_id[]" id="exam" class="form-control" multiple>
                                    </select>
                                </div>

                               


                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-success">Get StudentList</button> 
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="table-container">
                    <div class="button-div" style="display: none;"><button type="button" style="float: right;" class="btn btn-success generateResultButton mb-2" >Generate Final Result</button></div>
                   
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
                    </x-app-layout>
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
                            $('#exam').empty();
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
                                        $.each(data, function(key, value) {
                                            $('#exam').append('<option value="' + value.id + '">' + value.exam_name + '</option>');
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
                            $.ajax({
                                url: '/students/getfinalstudent',
                                type: 'POST',
                                data: $(this).serialize(),
                                beforeSend: function() { 
                                        $("#dev-loader").show();
                                    },
                                    complete: function() { 
                                        $("#dev-loader").hide();
                                    },
                                success: function(data) {
                                    if(data.student !=null){

                                    $('table tbody').html("");
                                    $.each(data.student, function(key, value) {
                                        var studentRow = `<tr class="student-row" data-id="${value.id}">`+
                                            '<td ><input type="checkbox" class="student-checkbox" data-id="' + value.id + '" ></td>' +
                                            '<td>' + value.name + '</td>' ;
                                         studentRow += '</tr>';
                                        $('#studentdata tbody').append(studentRow);
                                       
                                    });
                                }else{
                                    $('#studentdata tr').append('<th>No data Found!</th>');
                                }
                               
                                    $('#studentdata thead tr').empty();
                                    $('.button-div').show();   
                                    $('#studentdata thead tr').append('<th style="width:49px;"><input type="checkbox" id="selectAll">&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                    $('#studentdata thead tr').append('<th style="width:375px">Student Name</th>');
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
                                  
                                },
                                error: function(xhr, status, error) {
                                    $('#studentdata thead tr').append('<th><center>No data Found!</center></th>');
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
                                url: '/student/final-marksheet',  
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
                    });

                        </script>