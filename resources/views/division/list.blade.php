<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Divisions List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-8 col-lg-7">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">

                    <h1>List of Division</h1>
                    <div class="table-responsive">
                <div class="d-flex justify-content-end mb-3">
                    
                    <a href="{{ route('division.create') }}" class="btn btn-success mb-3" style="float: right;">Add New Division</a>
                </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Division Name</th>
                                <th>Standard</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($division as $value)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                    {{ !empty($value->division_name)? $value->division_name : 'N/A' ; }}<br>
                                 </td>
                                <td>{{ !empty($value->standard_name)? $value->standard_name : 'N/A' ;  }}</td>

                                <td>{{ $value->status ? 'Active' : 'Inactive' }}</td>
                                <td><a href="{{url('division/edit/'.$value->id)}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;<a href="{{url('division/delete/'.$value->id)}}" onclick="return confirm('Are you sure you want to Delete Division?')" class="btn btn-danger">Delete</a></td>
                         
                            </tr>
                            @php $i++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div style="float:right"> {{ $standard->links('pagination::bootstrap-4') }} </div>
                </div>
            </div>
        </div>
</x-app-layout>