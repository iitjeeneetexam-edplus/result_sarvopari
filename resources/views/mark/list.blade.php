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

        <h1>List of Mark</h1>
        <a href="{{ url('marks/create') }}" class="btn btn-success mb-3" style="float: right;">Add New Mark</a>
        

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Subject Name</th>
                    <th>Exam Name</th>
                    <th>Is_optional</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
               
              
            </tbody>
            <!-- For Bootstrap 4 -->

        </table>
      
    </div>

</x-app-layout>