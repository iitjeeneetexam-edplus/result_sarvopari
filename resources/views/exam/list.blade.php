@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exams List') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-sm-6 col-md-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">

                    <h1>List of Exam</h1>
                    <div class="table-responsive">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('exam.create') }}" class="btn btn-success mb-3" style="float:right">Add New Exam</a>
                </div>
                <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3"><input type="text" id="searchInput" class="form-control mb-3" placeholder="Search..."></div>
                </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Exam Name</th>
                                <th>Exam Type</th>
                                <th>Standard</th>
                                <th>Exam Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @php $i = 1; @endphp
                            @foreach ($exams as $exam)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $exam->exam_name }}</td>
                                <td>{{ ucfirst($exam->is_practical) }}</td>
                                <td>{{ $exam->standard_name }}</td> 
                                <td>{{ date('d-m-20y',strtotime($exam->date)) }}</td>
                                <td><a href="{{url('exam/edit/'.$exam->id)}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="confirmDelete({{ $exam->id }})" class="btn btn-danger">Delete</a></td>
                         
                            </tr>
                            @php $i++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');
        
        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
<Script>
 function confirmDelete(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: true
    });

    swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "Are you sure want to delete exam!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.get(`{{ url('exam/delete') }}/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            })
            .then(response => {
                 swalWithBootstrapButtons.fire({
                    title: "Deleted!",
                    text: "Your exam has been deleted.",
                    icon: "success"
                }).then(() => {
                   window.location.href = "{{ route('exam.index') }}";
                });
            })
            .catch(error => {
                swalWithBootstrapButtons.fire(
                    "Error!",
                    "There was a problem deleting the exam.",
                    "error"
                );
            });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Your exam is safe ",
                icon: "error"
            });
        }
    });
}

</Script>