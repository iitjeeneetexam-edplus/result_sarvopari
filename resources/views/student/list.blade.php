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
                    
       

        <!-- Student List Table -->
        <div class="row">
            <div class="col-12">

            <form method="POST" action="{{ route('assign.subject') }}">
            @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                <input type="checkbox" id="select-all">
                                </th>
                                <th>Roll Number</th>
                                <th>Name</th>
                                <th>GR Number</th>
                                <th>UID</th>
                                <th>Division</th>
                                @if(!empty($subjects))
                                    @foreach($subjects as $optionls) <!-- Reverse the subjects collection -->
                                        <th>{{ $optionls->subject_name }}</th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($students->isNotEmpty())
                                @foreach($students as $student)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="student_ids[]" class="student-checkbox" value="{{ $student->id }}" @if($student->subject_id) disabled @endif>
                                        </td>
                                        <td>{{ $student->roll_no }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->GR_no }}</td>
                                        <td>{{ $student->uid }}</td>
                                        <td>{{ $student->division->division_name }}</td>
                                        @php
                                            $subjectNames = explode(',', $student->subject_name); // Split the subject names
                                        @endphp
                                        
                                        @if(!empty($subjectNames))
                                            @foreach($subjectNames as $subjectName)
                                                <td>{{ trim($subjectName) }} </td> 
                                            @endforeach
                                        @else
                                            <span>No subjects assigned</span>
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
                @foreach($subjects as $optionls)
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="subject">Select Subject:</label>
                    <select name="subject_ids[]" id="subject" class="form-control" required>
                        <option value="">Select a {{ $optionls->subject_name }}</option>
                        @foreach($subject_subs as $subject_subsoptions)
                        @foreach($subject_subsoptions as $subjectSub)
                            @if($optionls->id == $subjectSub->subject_id)
                            <option value="{{ $subjectSub->id }}">{{ $subjectSub->subject_name }}</option>
                            @endif
                        @endforeach
                        @endforeach
                    </select>
                </div>
            </div>
            @endforeach
                <div class="mt-3">
                <button type="submit" class="btn btn-primary mb-3" style="float:right">Assign Subject</button>
            </div>
        </form>
            </div>
        </div>
    </div>
                </div></div></div></div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Select/Deselect All Checkboxes
        $('#select-all').click(function() {
            $('.student-checkbox:enabled').prop('checked', this.checked);
        });

        // Deselect "Select All" if any individual checkbox is unchecked
        $('.student-checkbox').click(function() {
            if (!$(this).is(':checked')) {
                $('#select-all').prop('checked', false);
            }
        });
    </script>
</x-app-layout>
