<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Division') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-7 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-5">
                <h1>Edit Division</h1>


                <form action="{{ route('division.update') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="standard_id">Standard</label>
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <select class="form-control" id="standard_id" name="standard_id" required>
                        <option value="" disabled {{ empty($data->standard_id) ? 'selected' : '' }}>Select a Standard</option>
                                @foreach ($standards as $standard)
                                    <option value="{{ $standard->id }}" {{ $data->standard_id == $standard->id ? 'selected' : '' }}>
                                        {{ $standard->standard_name }}
                                    </option>
                                @endforeach
                        </select>
                        @error('standard_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="division_name">Division Name</label>
                        <input type="text" class="form-control" id="division_name" name="division_name" required value="{{ $data->division_name }}" placeholder="Enter Division Name">
                        @error('division_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>

                        <select class="form-control" id="status" name="status">
                            <option value="">Select option</option>
                            <option value="1" {{ $data->status == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $data->status == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Update Division</button>
                    <a href="{{ route('standards.index') }}" class="btn btn-secondary " style="float:right">Back to Division</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>