@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-8 col-lg-7">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <div class="container mt-5">
                    <h1>List of Subject</h1>
                    <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                    <a href="{{ url('subjects/create') }}" class="btn btn-success mb-3">Add New Subject</a>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Standard Name</th>
                                <th>Subject Name</th>
                                <th>Optioal Name</th>

                                <th>Is Optional</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php $i = 1; @endphp
                            @foreach ($subjects as $subject)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ !empty($subject->standard_name)? $subject->standard_name : '' ;  }}</td>
                                <td>{{ !empty($subject->subject_name)? $subject->subject_name : '' ;  }}</td>
                                <td>
                                    @if(isset($subject_subs[$subject->id]) && $subject_subs[$subject->id]->count() > 0)
                                    @foreach ($subject_subs[$subject->id] as $subject2)
                                    {{ !empty($subject2->subject_name)? $subject2->subject_name : '' ; }}<br>
                                    @endforeach
                                    @else
                                    
                                    @endif
                                </td>

                                <td>{{ $subject->is_optional == '1' ? 'Yes' : 'No' }}</td>
                                <td>{{ $subject->status ? 'Active' : 'Inactive' }}</td>
                                <td><a href="{{url('subjects/edit/'.$subject->id)}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;
                                <a href="javascript:void(0);" onclick="confirmDelete({{ $subject->id }})" class="btn btn-danger">Delete</a></td>
                            </tr>
                            @php $i++; @endphp
                            @endforeach

                        </tbody>
                    </table>
                    </div>
                    <div style="float:right"> {{ $subjects->links('pagination::bootstrap-4') }} </div>
                </div>
            </div>
        </div>
</x-app-layout>
<Script>
 function confirmDelete(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "Are you sure want to delete subject!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.get(`{{ url('subjects/delete') }}/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            })
            .then(response => {
                 swalWithBootstrapButtons.fire({
                    title: "Deleted!",
                    text: "Your subject has been deleted.",
                    icon: "success"
                }).then(() => {
                   window.location.href = "{{ route('subjects.index') }}";
                });
            })
            .catch(error => {
                swalWithBootstrapButtons.fire(
                    "Error!",
                    "There was a problem deleting the subject.",
                    "error"
                );
            });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Your subject is safe ",
                icon: "error"
            });
        }
    });
}

</Script>