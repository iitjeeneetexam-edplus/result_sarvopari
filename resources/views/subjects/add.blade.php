<style>
    .add-chapter-btn {
    border: 1.5px solid gray;
    border-radius: 5px;
    text-align: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    width: -webkit-fit-content;
    width: -moz-fit-content;
    width: fit-content;
    margin: 0 auto;
    padding-right: 7px;
    margin-top: 20px;
    font-weight: 600;
    color: var(--primary-color);
}

.add-chapter-btn #addmore i {
    border: 1.5px solid var(--primary-color);
    padding: 3px;
    border-radius: 5px;
    cursor: pointer;
}

.add-chapter-btn label {
    cursor: pointer;
    margin-top: 5px;
}
</style>
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
                    <h1>Add New Subject</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('subjects.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="subject_name">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                        @error('subject_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="is_optional">Is Optional?</label>
                        <select class="form-control" id="is_optional" name="is_optional">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        @error('is_optional')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="add-chapter-btn">
                            <a class="btn" id="addmore">
                            <i class="fa fa-plus" aria-hidden="true"></i>

                            </a>
                            <label for="exampleInputEmail1">Add More Chapter</label>
                        </div>

                    <div class="form-group mb-3">
                        <label for="standard_id">Select Standard</label>
                        <select class="form-control" id="standard_id" name="standard_id" required>
                            <option value="">select option</option>
                            @foreach ($standards as $standard)
                                <option value="{{ $standard->id }}">{{ $standard->standard_name }}</option>
                                <option value="{{ $standard->id }}">{{ $standard->standard_name }}</option>
                            @endforeach
                        </select>
                        @error('standard_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">select option</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </form>
            </div>
        </div>
    </div>
        </div>
</x-app-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
      $(document).ready(function() {
            $('#addmore').click(function() {
                var chapterHtml = `
                <br>
               <div class="form-group mb-3">
                        <label for="subject_name">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                       
                    </div>

                    <div class="form-group mb-3">
                        <label for="is_optional">Is Optional?</label>
                        <select class="form-control" id="is_optional" name="is_optional">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>`;
                $('.add-chapter-btn').before(chapterHtml);
                $('.remove-chapter').click(function() {
                    $(this).closest('.added-chapter').remove();
                });
                $('#imageInput').change(function() {
                    readURL(this);
                });
            });
      });
</script>