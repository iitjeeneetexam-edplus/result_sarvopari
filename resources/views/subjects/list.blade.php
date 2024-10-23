<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <div class="container mt-5">
                <h1>List of Subject</h1>
                <a href="{{ url('subjects/create') }}" class="btn btn-primary mb-3" style="float: right;">Add New Subject</a>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject Name</th>
                            <th>Is Optional</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                            <tr>
                                <td>{{ $subject->id }}</td>
                                <td>{{ $subject->subject_name }}</td>
                                <td>{{ $subject->is_optional ? 'Yes' : 'No' }}</td>
                                <td>{{ $subject->status ? 'Active' : 'Inactive' }}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
