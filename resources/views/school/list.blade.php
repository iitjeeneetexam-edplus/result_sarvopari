<!-- resources/views/list_schools.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
    
           
        <div class="col-12 col-sm-8 col-md-8 col-lg-10">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">

                    <h1>List of School</h1>
                   
                    <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                            <a href="{{ url('schools/create') }}" class="btn btn-success">Add New School</a>
                        </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>School Name</th>
                                <th>School Index</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Contact No</th>
                                <th>Status</th>
                                <th>Action</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($schools as $school)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $school->school_name }}</td>
                                <td>{{ $school->school_index }}</td>
                                <td>{{ $school->address }}</td>
                                <td>{{ $school->email }}</td>
                                <td>{{ $school->contact_no }}</td>
                                <td>{{ $school->status }}</td>
                                <td><a href="{{url('schools/view/'.$school->id)}}" class="btn btn-warning">View</a>&nbsp;&nbsp;<a href="{{url('schools/edit/'.$school->id)}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="confirmDelete({{ $school->id }})" class="btn btn-danger">Delete</a></td>
                            </tr>
                            @php $i++ @endphp

                            @endforeach

                        </tbody>
                        <!-- For Bootstrap 4 -->

                    </table>
                    </div>
                    <div style="float:right"> {{ $schools->links('pagination::bootstrap-4') }} </div>
                </div>

</x-app-layout>
<style>
    
</style>
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
        text: "Are you sure want to delete school!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.get(`{{ url('schools/delete') }}/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            })
            .then(response => {
                 swalWithBootstrapButtons.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                }).then(() => {
                   window.location.href = "{{ route('schools') }}";
                });
            })
            .catch(error => {
                swalWithBootstrapButtons.fire(
                    "Error!",
                    "There was a problem deleting the file.",
                    "error"
                );
            });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Your School is safe ",
                icon: "error"
            });
        }
    });
}

</Script>