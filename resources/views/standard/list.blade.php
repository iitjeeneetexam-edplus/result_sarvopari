<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-8 col-lg-7">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">
                <h1>List of Standard</h1>
                <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                    
                    <a href="{{ url('standards/create') }}" class="btn btn-success mb-3" style="float: right;">Add New Standard</a>
                </div>
                
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>School Name</th>
                                <th>Standard Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($standards))
                            @foreach ($standards as $standard)
                            <tr>
                                <td>{{ $standard->id }}</td>
                                <td>{{ (!empty($standard->school->school_name))?$standard->school->school_name:''; }}</td> <!-- Accessing school name via relationship -->
                                <td>{{ $standard->standard_name }}</td>
                                <td>{{ $standard->status }}</td>
                                <td><a href="{{url('standards/edit/'.$standard->id)}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;<a href="{{url('standards/delete/'.$standard->id)}}" onclick="return confirm('Are you sure you want to Delete Standard?')" class="btn btn-danger">Delete</a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                    <!-- Pagination Links -->
                    <div style="float:right"> {{ $standards->links('pagination::bootstrap-4') }} </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>