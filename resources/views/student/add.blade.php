<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Add New Standard</h1>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="school">Select School:</label>
                        <select name="school_id" id="school" class="form-control" required>
                            <option value="">Select a School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="standard">Select Standard:</label>
                        <select name="standard_id" id="standard" class="form-control" required>
                            <option value="">Select a Standard</option>
                        </select>
                    </div>

                    <div>
                        <label for="division">Select Division:</label>
                        <select name="division_id" id="division" class="form-control" required>
                            <option value="">Select a Division</option>
                        </select>
                    </div>

                    <div>
                        <label for="csv_file">Upload CSV:</label>
                        <input type="file" name="csv_file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Upload CSV</button>
                    </form>

                    <a href="{{ route('students.index') }}" class="btn btn-secondary mt-3">Back to Student List</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#school').change(function() {
                var schoolId = $(this).val();
                if(schoolId) {
                    $.ajax({
                        url: '{{ url("/get-standards") }}/' + schoolId,
                        type: 'GET',
                        success: function(data) {
                            $('#standard').empty();
                            $('#standard').append('<option value="">Select a Standard</option>');
                            $.each(data, function(key, value) {
                                $('#standard').append('<option value="'+ value.id +'">'+ value.standard_name +'</option>');
                            });
                        }
                    });
                } else {
                    $('#standard').empty();
                    $('#standard').append('<option value="">Select a Standard</option>');
                }
            });

            $('#standard').change(function() {
                var standardId = $(this).val();
                if(standardId) {
                    $.ajax({
                        url: '{{ url("/get-divisions") }}/' + standardId,
                        type: 'GET',
                        success: function(data) {
                            $('#division').empty();
                            $('#division').append('<option value="">Select a Division</option>');
                            $.each(data, function(key, value) {
                                $('#division').append('<option value="'+ value.id +'">'+ value.division_name +'</option>');
                            });
                        }
                    });
                } else {
                    $('#division').empty();
                    $('#division').append('<option value="">Select a Division</option>');
                }
            });
        });
    </script>
</x-app-layout>
