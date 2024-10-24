<!-- resources/views/list_schools.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">

        <h1>List of School</h1>
        <a href="{{ url('schools/create') }}" class="btn btn-primary mb-3" style="float: right;">Add New School</a>
        

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                    <tr>
                        <td>{{ $school->id }}</td>
                        <td>{{ $school->school_name }}</td>
                        <td>{{ $school->address }}</td>
                        <td>{{ $school->email }}</td>
                        <td>{{ $school->contact_no }}</td>
                        <td>{{ $school->status }}</td>
                    </tr>
                @endforeach
              
            </tbody>
            <!-- For Bootstrap 4 -->

        </table>
       <div style="float:right"> {{ $schools->links('pagination::bootstrap-4') }} </div>
    </div>

</x-app-layout>