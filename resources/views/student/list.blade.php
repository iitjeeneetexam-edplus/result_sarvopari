<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student List') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
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
                                @if($subjects)
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
                                    <td colspan="5" class="text-center">No students found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
