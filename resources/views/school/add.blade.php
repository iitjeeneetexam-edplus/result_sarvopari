<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-sm-6 col-md-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900 dark:text-gray-100 ">

                        <h2>Add New School</h2>

                        <!-- Add your JS or Bootstrap script here -->
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                        <!-- Display Validation Errors -->

                        <form action="{{ url('schools/store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="school_name">School Name</label>
                                <input type="text" class="form-control" id="school_name" name="school_name" value="{{ old('school_name') }}" placeholder="School Name">
                                @error('school_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter Address">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Email">
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="contact_no">Contact No</label>
                                <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ old('contact_no') }}" placeholder="Enter Contact No">
                                @error('contact_no')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Select option</option>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Add School</button>
                        </form>

                        <!-- Add your JS or Bootstrap script here -->
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>