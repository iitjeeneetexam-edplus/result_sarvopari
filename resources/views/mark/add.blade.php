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
                                        <option value="">All Schools</option>
                                        @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->school_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="standard">Select Standard:</label>
                                    <select name="standard_id" id="standard" class="form-control">
                                        <option value="">Select a Standard</option>
                                        <!-- Populated via AJAX -->
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="division">Select Division:</label>
                                    <select name="division_id" id="division" class="form-control">
                                        <option value="">Select a Division</option>
                                        <!-- Populated via AJAX -->
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="subject">Select Subject:</label>
                                    <select name="subject_id" id="subject" class="form-control">
                                        <option value="">Select a Subject</option>
                                        <!-- Populated via AJAX -->
                                    </select>
                                </div>
                                <div class="col-md-12" style="display: none;" id="subject_sub_display">
                                    <label for="subject_sub">Select option Subject:</label>
                                    <select name="subject_sub_id" id="subject_sub" class="form-control">
                                        <option value="">Select a option Subject</option>
                                        <!-- Populated via AJAX -->
                                    </select>
                                </div>
                                <div class="col-md-12" >
                                    <label for="subject_sub">Total Marks</label>
                                    <input type="text" name="total_mark" class="form-control" placeholder="Enter Total Marks">   
                                </div>
                            
                          

                            <button type="submit" class="btn btn-primary mb-3" style="float:right">Add Mark</button>
                        </form>

                        <!-- Add your JS or Bootstrap script here -->
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // School change event for fetching standards
            $('#school').change(function() {
                var schoolId = $(this).val();
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
            });

            // Standard change event for fetching divisions
            $('#standard').change(function() {
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
                                    $('#division').append('<option value="' + value.id + '">' + value.division_name + '</option>');
                                });
                            } else {
                                $('#division').append('<option value="">No divisions found</option>');
                            }

                            // Clear the subject dropdown and add the default option
                            $('#subject').empty().append('<option value="">Select a Subject</option>');

                            // Populate the subject dropdown
                            if (data.subjects && Array.isArray(data.subjects) && data.subjects.length) {
                                $.each(data.subjects, function(key, value) {
                                    $('#subject').append('<option value="' + value.id + '">' + value.subject_name + '</option>');
                                });
                            } else {
                                $('#subject').append('<option value="">No subjects found</option>');
                            }
                        }
                    });
                } else {
                    $('#division').empty().append('<option value="">Select a Division</option>');
                }
            });
            $('#subject').change(function() {
                 // Show the content

                var subject_id = $(this).val();
                if (subject_id) {
                    $.ajax({
                        url: '{{ url("/get-subjects-sub") }}/' + subject_id,
                        type: 'GET',
                        success: function(data) {
                            if(data.optional){
                                    if(data.optional.is_optional=='1'){
                                        $('#subject_sub_display').show();
                                        $('#subject_sub').empty().append('<option value="">Select a subject_sub</option>');
                              
                                            if (data.subject_sub && Array.isArray(data.subject_sub) && data.subject_sub.length) {
                                                $.each(data.subject_sub, function(key, value) {
                                                    $('#subject_sub').append('<option value="' + value.id + '">' + value.subject_name + '</option>');
                                                });
                                            }
                             
                                    }else{
                                        $('#subject_sub_display').hide();
                                    }
                            }
                        }
                    });
                } else {
                    $('#subject_sub').empty().append('<option value="">Select a Standard</option>');
                }
            });
        });
    </script>