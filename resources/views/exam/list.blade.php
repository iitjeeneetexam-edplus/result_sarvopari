<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exams List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">
               
                <h1>List of Exam</h1>
                <a href="{{ route('exam.create') }}" class="btn btn-success mb-3" style="float:right">Add New Exam</a>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Exam Name</th>
                            <th>Subject</th>
                            <th>Exam Date</th>
                            <th>Total Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $exam)
                            <tr>
                                <td>{{ $exam->id }}</td>
                                <td>{{ $exam->exam_name }}</td>
                                <td>{{ $exam->subject->subject_name }}</td> <!-- Display associated standard -->
                                <td>{{ $exam->date }}</td>
                                <td>{{ $exam->total_marks }}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="float:right"> {{ $exams->links('pagination::bootstrap-4') }} </div>
            </div></div>
        </div>
    </div>
</x-app-layout>
