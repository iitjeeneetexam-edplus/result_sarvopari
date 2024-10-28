<!-- resources/views/list_schools.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
    
           
        <div class="col-12 col-sm-8 col-md-8 col-lg-10">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">

                    <h1>List of School</h1>
                   
                    <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                            <a href="{{ url('schools/create') }}" class="btn btn-success">Add New School</a>
                        </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>School Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Contact No</th>
                                <th>Status</th>
                                <th>Action</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($schools as $school)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $school->school_name }}</td>
                                <td>{{ $school->address }}</td>
                                <td>{{ $school->email }}</td>
                                <td>{{ $school->contact_no }}</td>
                                <td>{{ $school->status }}</td>
                                <td><a href="{{url('schools/view/'.$school->id)}}" class="btn btn-warning">View</a>&nbsp;&nbsp;<a href="{{url('schools/edit/'.$school->id)}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;<a href="{{url('schools/delete/'.$school->id)}}" onclick="return confirm('Are you sure you want to Delete School?')" class="btn btn-danger">Delete</a></td>
                            </tr>
                            @php $i++ @endphp

                            @endforeach

                        </tbody>
                        <!-- For Bootstrap 4 -->

                    </table>
                    </div>
                    <div style="float:right"> {{ $schools->links('pagination::bootstrap-4') }} </div>
                </div>

</x-app-layout>