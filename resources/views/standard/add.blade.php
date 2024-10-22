<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Add New Standard</h1>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

        <form action="{{ route('standards.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="school_id">School</label>
                <select name="school_id" id="school_id" class="form-control" required>
                    <option value="">Select a School</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                    @endforeach
                </select>
                @error('school_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="standard_name">Standard Name</label>
                <input type="text" name="standard_name" id="standard_name" class="form-control" required>
                @error('standard_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="">Select option</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Add Standard</button>
        </form>

        <a href="{{ route('standards.index') }}" class="btn btn-secondary mt-3">Back to Standards</a>
    </div>
</body>
</html>
</x-app-layout>