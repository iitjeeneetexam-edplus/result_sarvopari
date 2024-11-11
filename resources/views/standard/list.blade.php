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
                <div class="container mt-5">
                <h1>List of Standard</h1>
                <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                    
                    <a href="{{ url('standards/create') }}" class="btn btn-success mb-3" style="float: right;">Add New Standard</a>
                </div>
                
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <!-- <th>School Name</th> -->
                                <th>Standard Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i=1 @endphp
                            @if(!empty($standards))
                            @foreach ($standards as $standard)
                            <tr>
                                <td>{{ $i }}</td>
                                <!-- <td>{{ (!empty($standard->school->school_name))?$standard->school->school_name:''; }}</td> Accessing school name via relationship -->
                                <td>{{ $standard->standard_name }}</td>
                                <td>@if($standard->status == 1)
                                        <button class="btn btn-success">Active</button>
                                    @else
                                        <button class="btn btn-danger">Inactive</button>
                                    @endif</td>
                                <td><a href="{{url('standards/edit/'.$standard->id)}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="confirmDelete({{ $standard->id }})"  class="btn btn-danger">Delete</a></td>
                            </tr>
                            @php $i++ @endphp
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                    <!-- Pagination Links -->
                    <div style="float:right"> {{ $standards->links('pagination::bootstrap-4') }} </div>
                </div>
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
        text: "Are you sure want to delete standard!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.get(`{{ url('standards/delete') }}/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            })
            .then(response => {
                 swalWithBootstrapButtons.fire({
                    title: "Deleted!",
                    text: "Your standard has been deleted.",
                    icon: "success"
                }).then(() => {
                   window.location.href = "{{ route('standards.index') }}";
                });
            })
            .catch(error => {
                swalWithBootstrapButtons.fire(
                    "Error!",
                    "There was a problem deleting the standard.",
                    "error"
                );
            });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Your standard is safe ",
                icon: "error"
            });
        }
    });
}

</Script>