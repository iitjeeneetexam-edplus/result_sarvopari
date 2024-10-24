<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Exam') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-7 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-5">
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
                        <label for="subject_id">Subject</label>
                        <select class="form-control" id="subject_id" name="subject_id" required>
                            <option value="">Select a Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') ==  $subject->id  ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
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

                    <div class="form-group mb-3">
                        <label for="total_marks">Exam Mark</label>
                        <input type="number" class="form-control" id="total_marks" name="total_marks" required value="{{ old('total_marks')}}" placeholder="Enter Exam Mark">
                    </div>
                    @error('total_marks')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    
                    

                    <button type="submit" class="btn btn-primary">Add Exam</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
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