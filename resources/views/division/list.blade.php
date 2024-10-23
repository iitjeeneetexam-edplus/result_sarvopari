<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Divisions List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <h1>List of Division</h1>
                <a href="{{ route('division.create') }}" class="btn btn-primary mb-3" style="float: right;">Add New Division</a>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Division Name</th>
                            <th>Status</th>
                            <th>Standard</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($divisions as $division)
                            <tr>
                                <td>{{ $division->id }}</td>
                                <td>{{ $division->division_name }}</td>
                                <td>{{ $division->status ? 'Active' : 'Inactive' }}</td>
                                <td>{{ $division->standard_name ? $division->standard_name : 'N/A' ; }}</td> <!-- Display associated standard -->
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
