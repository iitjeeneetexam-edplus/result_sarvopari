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
               
                <h1>List of Division</h1>
                <a href="{{ route('division.create') }}" class="btn btn-success mb-3" style="float: right;">Add New Division</a>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Division Name</th>
                            <th>Standard</th>
                            <th>Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp 
                    @foreach ($standard as $value)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>
                            @if(isset($division[$value->id]) && $division[$value->id]->count() > 0) 
                                @foreach ($division[$value->id] as $value2)
                                    {{ !empty($value2->division_name)? $value2->division_name : 'N/A' ; }}<br> 
                                @endforeach
                            @else
                                N/A 
                            @endif
                        </td>
                        <td>{{ !empty($value->standard_name)? $value->standard_name : 'N/A' ;  }}</td>
                       
                        <td>{{ $value->status ? 'Active' : 'Inactive' }}</td>
                        
                    </tr>
                    @php $i++; @endphp 
                @endforeach
                    </tbody>
                </table>
                <div style="float:right"> {{ $standard->links('pagination::bootstrap-4') }} </div>
            </div>
        </div>
    </div>
</x-app-layout>
