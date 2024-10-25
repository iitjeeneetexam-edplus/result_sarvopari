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
                            <div class="form-group mb-3">
                                <label for="school_name">Student Name</label>
                                <select class="form-control" id="student_id" name="student_id" required>
                                    <option value="">Select</option>
                                    @foreach ($student as $value)
                                        <option value="{{ $value->id }}" {{ old('student_id') ==  $value->id  ? 'selected' : '' }}>{{ $value->name }}</option> <!-- Assuming the standard has a name field -->
                                    @endforeach
                                </select>
                                @error('school_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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
