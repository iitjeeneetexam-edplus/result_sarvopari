
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <div class="container mt-5">
                <a href="{{url('standards/create')}}" class="text-end">Create</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Name</th>
                    <th>Standard Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($standards as $standard)
                <tr>
                    <td>{{ $standard->id }}</td>
                    <td>{{ $standard->school->school_name }}</td> <!-- Accessing school name via relationship -->
                    <td>{{ $standard->standard_name }}</td>
                    <td>{{ $standard->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div>
            {{ $standards->links() }} <!-- Display pagination links -->
        </div>
    </div>
    </div>
    </div>
    </div>
  
</x-app-layout>