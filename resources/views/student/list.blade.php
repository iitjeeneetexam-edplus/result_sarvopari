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
                            <a href="{{ url('/students/add') }}" class="btn btn-primary" style="float: right;">Add New Students</a>
                         </div>
      
        <!-- Filter Form -->
        <form method="GET" action="{{ route('students.index') }}">
      
            @csrf
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="school">School</label>
                    <select name="school_id" id="school" class="form-control">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="standard">Select Standard:</label>
                    <select name="standard_id" id="standard" class="form-control">
                        <option value="">Select a Standard</option>
                        <!-- Populated via AJAX -->
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="division">Select Division:</label>
                    <select name="division_id" id="division" class="form-control">
                        <option value="">Select a Division</option>
                        <!-- Populated via AJAX -->
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary justify-center">Filter</button>
                </div>
                </div>
            </div>
        </form>

        <!-- Student List Table -->
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>PRoll Number</th>
                                <th>Name</th>
                                <th>GR Number</th>
                                <th>UID</th>
                                <th>Division</th>
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
                </div>
            </div></div>
        

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // School change event for fetching standards
            $('#school').change(function() {
                var schoolId = $(this).val();
                if (schoolId) {
                    $.ajax({
                        url: '{{ url("/get-standards") }}/' + schoolId,
                        type: 'GET',
                        success: function(data) {
                            $('#standard').empty().append('<option value="">Select a Standard</option>');
                            $.each(data, function(key, value) {
                                $('#standard').append('<option value="'+ value.id +'">'+ value.standard_name +'</option>');
                            });
                        }
                    });
                } else {
                    $('#standard').empty().append('<option value="">Select a Standard</option>');
                    $('#division').empty().append('<option value="">Select a Division</option>');
                }
            });

            // Standard change event for fetching divisions
            $('#standard').change(function() {
                var standardId = $(this).val();
                if (standardId) {
                    $.ajax({
                        url: '{{ url("/get-divisions") }}/' + standardId,
                        type: 'GET',
                        success: function(data) {
                            $('#division').empty().append('<option value="">Select a Division</option>');
                            $.each(data, function(key, value) {
                                $('#division').append('<option value="'+ value.id +'">'+ value.division_name +'</option>');
                            });
                        }
                    });
                } else {
                    $('#division').empty().append('<option value="">Select a Division</option>');
                }
            });
        });
    </script>
</x-app-layout>
