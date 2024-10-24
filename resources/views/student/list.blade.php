<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">
                    <div class="row"> 
                         <div class="col-md-2 offset-10">
                            <a href="{{ url('/students/add') }}" class="btn btn-success" >Add New Students</a>
                         </div>
                    </div><br>
      
        <!-- Filter Form -->
       

        <!-- Student List Table -->
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Roll Number</th>
                                <th>Name</th>
                                <th>GR Number</th>
                                <th>UID</th>
                                <th>Division</th>
                                @if(!empty($subjects))
                                    @foreach($subjects as $optionls)
                                    <th>{{$optionls->subject}}</th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($students->isNotEmpty())
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->roll_no }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->GR_no }}</td>
                                        <td>{{ $student->uid }}</td>
                                        <td>{{ $student->division->division_name }}</td>
                                        @if($subjects)
                                        @foreach($subjects as $optionls)
                                            <td>{{$optionls->sub_subject}}</td>
                                            @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">No students found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
                </div></div></div></div>

</x-app-layout>
