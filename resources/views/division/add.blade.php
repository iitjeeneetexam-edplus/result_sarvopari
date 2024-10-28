<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Division') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-sm-6 col-md-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900 dark:text-gray-100 ">
                <h1>Add Division</h1>


                <form action="{{ route('division.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="standard_id">Standard</label>
                        <select class="form-control" id="standard_id" name="standard_id" required>
                            <option value="">Select standard</option>
                            @foreach ($standards as $standard)
                            <option value="{{ $standard->id }}" {{ old('standard_id') ==  $standard->id  ? 'selected' : '' }}>{{ $standard->standard_name }}</option> <!-- Assuming the standard has a name field -->
                            @endforeach
                        </select>
                        @error('standard_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="division_name">Division Name</label>
                        <input type="text" class="form-control" id="division_name" name="division_name" required value="{{ old('division_name')}}" placeholder="Enter Division Name">
                        @error('division_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <input type="hidden" name="status" value="1">
                        <!-- <label for="status">Status</label>
               
                        <select class="form-control" id="status" name="status">
                            <option value="">Select option</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror -->
                    </div>

                    <button type="submit" class="btn btn-primary">Add Division</button>
                    <a href="{{ route('standards.index') }}" class="btn btn-secondary " style="float:right">Back to Create Division</a>
                </form>
            </div>
        </div>
    </div>
        </div></div>
</x-app-layout>