@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Exam') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-sm-6 col-md-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900 dark:text-gray-100 ">
                <h1>Edit Exam</h1>


                <form action="{{ route('exam.update') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="hidden" name="id" value="{{ $data->id}}">
                        <label for="exam_name">Exam Name</label>
                        <input type="text" class="form-control" id="exam_name" name="exam_name" required value="{{ $data->exam_name}}" placeholder="Enter Exam Name">
                    </div>
                    @error('exam_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-group">
                                <input type="hidden" name="school_id" id="school_id" value="{{ $schools->id}}">
                            </div>
                    <div class="form-group mb-3">
                        <label for="standard_id">Select Standard</label>
                        <select class="form-control" id="standard_id" name="standard_id" required>
                            <option value="">select option</option>
                        </select>
                        @error('standard_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="date">Exam Date</label>
                        <!-- <input type="text" id="date-placeholder" class="form-control" placeholder="{{ !empty($data->date) ? date('d-m-20y', strtotime($data->date)) : 'Enter Exam Date' }}" 
       onfocus="this.style.display='none'; document.getElementById('date').style.display='block'; document.getElementById('date').focus();" /> -->

                    <input type="date" class="form-control" id="date" name="date" required 
                        value="{{ old('date', \Carbon\Carbon::parse($data->date)->toDateString()) }}" >
                       <span id="date-error" class="text-danger" style="display:none;"></span>
                        @error('date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="form-group mb-3">
                        <label for="result_date">Result Date</label>
                        <input type="date" class="form-control" id="result_date" name="result_date" required value="{{ old('result_date', \Carbon\Carbon::parse($data->result_date)->toDateString()) }}">
                        <span id="date-error" class="text-danger" style="display:none;"></span>
                        @error('result_date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>



                    <button type="submit" class="btn btn-success">Update Exam</button>
                    <a href="{{ route('exam.index') }}" class="btn btn-secondary " style="float:right">Back to Exam </a>
                </form>
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
        var preSelectedStandardId = "{{ old('standard_id', $data->standard_id ?? '') }}"; 

        function loadStandards(schoolId) {
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
                    console.log(data);
                    $('#standard_id').empty().append('<option value="">Select a Standard</option>');
                    $.each(data, function(key, value) {
                        var isSelected = (preSelectedStandardId == value.id) ? 'selected' : '';
                        $('#standard_id').append('<option value="' + value.id + '" ' + isSelected + '>' + value.standard_name + '</option>');
                
                    });
                }
            });
        } else {
            $('#standard_id').empty().append('<option value="">Select a Standard</option>');
        }
    }

    // Call the function on page load if there is a pre-selected school ID
    var preSelectedSchoolId = $('#school_id').val();
    if (preSelectedSchoolId) {
        loadStandards(preSelectedSchoolId);
    }

    // Call the function when the school dropdown value changes
    $('#school').change(function() {
        var schoolId = $(this).val();
        loadStandards(schoolId);
    });

    });
    
</script>