<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <div class="container mt-5">
                    <h1>List of Subject</h1>
                    <a href="{{ url('subjects/create') }}" class="btn btn-success mb-3" style="float: right;">Add New Subject</a>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Standard Name</th>
                                <th>Subject Name</th>
                                <th>Optioal Name</th>

                                <th>Is Optional</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php $i = 1; @endphp
                            @foreach ($subjects as $subject)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ !empty($subject->standard_name)? $subject->standard_name : 'N/A' ;  }}</td>
                                <td>{{ !empty($subject->subject_name)? $subject->subject_name : 'N/A' ;  }}</td>
                                <td>
                                    @if(isset($subject_subs[$subject->id]) && $subject_subs[$subject->id]->count() > 0)
                                    @foreach ($subject_subs[$subject->id] as $subject2)
                                    {{ !empty($subject2->subject_name)? $subject2->subject_name : 'N/A' ; }}<br>
                                    @endforeach
                                    @else
                                    'N/A'
                                    @endif
                                </td>

                                <td>{{ $subject->is_optional == '1' ? 'Yes' : 'No' }}</td>
                                <td>{{ $subject->status ? 'Active' : 'Inactive' }}</td>
                            </tr>
                            @php $i++; @endphp
                            @endforeach

                        </tbody>
                    </table>
                    <div style="float:right"> {{ $subjects->links('pagination::bootstrap-4') }} </div>
                </div>
            </div>
        </div>
</x-app-layout>