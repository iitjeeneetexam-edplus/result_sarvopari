<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Divisions List') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-7 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('division.create') }}" class="btn btn-primary mb-3">Add New Division</a>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Division Name</th>
                            <th>Status</th>
                            <th>Standard</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($divisions as $division)
                            <tr>
                                <td>{{ $division->id }}</td>
                                <td>{{ $division->division_name }}</td>
                                <td>{{ $division->status ? 'Active' : 'Inactive' }}</td>
                                <td>{{ $division->standard_name ? $division->standard_name : 'N/A' ; }}</td> <!-- Display associated standard -->
                                <td>
                                    <!-- Add edit/delete actions if needed -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
