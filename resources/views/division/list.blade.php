@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Divisions List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-8 col-lg-7">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">

                    <h1>List of Division</h1>
                    <div class="table-responsive">
                <div class="d-flex justify-content-end mb-3">
                    
                    <a href="{{ route('division.create') }}" class="btn btn-success mb-3" style="float: right;">Add New Division</a>
                </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Division Name</th>
                                <th>Standard</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($division as $value)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                    {{ !empty($value->division_name)? $value->division_name : 'N/A' ; }}<br>
                                 </td>
                                <td>{{ !empty($value->standard_name)? $value->standard_name : 'N/A' ;  }}</td>

                                <td>@if($value->status == 1)
                                        <button class="btn btn-success">Active</button>
                                    @else
                                        <button class="btn btn-danger">Inactive</button>
                                    @endif</td>
                                <td><a href="{{url('division/edit/'.$value->id)}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="confirmDelete({{ $value->id }})" class="btn btn-danger">Delete</a></td>
                         
                            </tr>
                            @php $i++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div style="float:right"> {{ $standard->links('pagination::bootstrap-4') }} </div>
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
        text: "Are you sure want to delete division!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.get(`{{ url('division/delete') }}/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            })
            .then(response => {
                 swalWithBootstrapButtons.fire({
                    title: "Deleted!",
                    text: "Your division has been deleted.",
                    icon: "success"
                }).then(() => {
                   window.location.href = "{{ route('division.index') }}";
                });
            })
            .catch(error => {
                swalWithBootstrapButtons.fire(
                    "Error!",
                    "There was a problem deleting the division.",
                    "error"
                );
            });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Your division is safe ",
                icon: "error"
            });
        }
    });
}

</Script>