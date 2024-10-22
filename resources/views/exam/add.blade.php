<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Exam') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-7 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('exam.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="exam_name">Exam Name</label>
                        <input type="text" class="form-control" id="exam_name" name="exam_name" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="standard_id">Standard</label>
                        <select class="form-control" id="standard_id" name="standard_id" required>
                            <option value="">Select a standard</option>
                            @foreach ($standards as $standard)
                                <option value="{{ $standard->id }}">{{ $standard->standard_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="exam_date">Exam Date</label>
                        <input type="date" class="form-control" id="exam_date" name="exam_date" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="total_marks">Total Marks</label>
                        <input type="number" class="form-control" id="total_marks" name="total_marks" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    

                    <button type="submit" class="btn btn-primary">Add Exam</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
