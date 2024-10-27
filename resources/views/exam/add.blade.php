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
                <h1>Add New Exam</h1>


                <form action="{{ route('exam.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="exam_name">Exam Name</label>
                        <input type="text" class="form-control" id="exam_name" name="exam_name" required value="{{ old('exam_name')}}" placeholder="Enter Exam Name">
                    </div>
                    @error('exam_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-group mb-3">
                        <label for="standard_id">Select School</label>
                        <select name="school_id" id="school" class="form-control">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }}
                            </option>
                            @endforeach
                        </select>
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
                        <input type="text" id="date-placeholder" class="form-control" placeholder="Enter Exam Date" onfocus="this.style.display='none'; document.getElementById('date').style.display='block';" />
                        <input type="date" class="form-control" id="date" name="date" required value="{{ old('date') }}" min="{{ \Carbon\Carbon::today()->toDateString() }}" style="display:none;">
                        <span id="date-error" class="text-danger" style="display:none;"></span>
                        @error('date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>




                    <button type="submit" class="btn btn-primary">Add Exam</button>
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
        $('#school').change(function() {
            var schoolId = $(this).val();
            if (schoolId) {
                $.ajax({
                    url: '{{ url("/get-standards") }}/' + schoolId,
                    type: 'GET',
                    success: function(data) {
                        $('#standard_id').empty().append('<option value="">Select a Standard</option>');
                        $.each(data, function(key, value) {
                            $('#standard_id').append('<option value="' + value.id + '">' + value.standard_name + '</option>');
                        });
                    }
                });
            } else {
                $('#standard_id').empty().append('<option value="">Select a Standard</option>');
            }
        });

    });
    document.addEventListener("DOMContentLoaded", function() {
        const dateInput = document.getElementById("date");
        const dateError = document.getElementById("date-error");

        // Function to check the selected date
        dateInput.addEventListener("input", function() {
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set hours to 0 for comparison

            if (selectedDate < today) {
                dateError.textContent = "Please select a date today or in the future.";
                dateError.style.display = "block"; // Show error message
                dateInput.value = ""; // Clear the invalid date
            } else {
                dateError.style.display = "none"; // Hide error message
            }
        });
    });
</script>