@include('sidebar_display')
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
    <style>
        .hidden {
            display: none;
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-8 col-lg-7">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
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

                        <form action="{{url('subjects/store')}}" method="POST" id="subjectForm" enctype='multipart/form-data'>
                            @csrf
                            <div class="form-group mb-3" id="main_subject">
                                <label for="subject_name">Subject Name </label>
                                <input type="text" class="form-control" id="subject_name" name="subject_name[]" placeholder="Subject Name">
                                @error('subject_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="optionalDiv" class="hidden ">
                                <div class="row ">
                                    <div class="col-md-12 is_addmore_subject">
                                        <button class="btn btn-success " style="float:right" id="addmore_subject" type="button">plus</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="is_optional_added">
                                        <div class="row">
                                            <div class="col-md-10 ">
                                                <label for="subject_name">Subject Name </label>
                                                <input type="text" class="form-control" id="subject_sub_name" name="subject_sub_name[0][]" placeholder="Subject Name">
                                                @error('subject_name')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-10 ">
                                                <label for="subject_name">Subject Name </label>
                                                <input type="text" class="form-control" id="subject_name" name="subject_sub_name[0][]" placeholder="Subject Name">
                                                @error('subject_name')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Content to be shown when 'Yes' is selected -->

                            </div>
                            <div class="form-group mb-3 is_optional_content">
                                <label for="is_optional">Is Optional?</label>
                                <select class="form-control" id="is_optional" name="is_optional[]" onchange="toggleDiv()">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                @error('is_optional')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="add-chapter-btn">
                                <a class="btn" id="addmore">
                                    &nbsp;&nbsp; Add More Subject

                                </a>
                            </div>
                            <div class="form-group mb-3">
                                <label for="standard_id">Select School</label>
                                <select name="school_id" id="school" class="form-control">
                                    <option value="">All Schools</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="standard_id">Select Standard</label>
                                <select class="form-control" id="standard_id" name="standard_id" required>
                                    <option value="">select option</option>
                                </select>
                                @error('standard_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <!-- <label for="status">Status</label> -->
                                <input type="hidden" name="status" value="1">
                                <!-- <select class="form-control" id="status" name="status" required>
                                    <option value="">select option</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror -->
                            </div>

                            <button type="submit" class="btn btn-primary">Add Subject</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
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
                        $('#standard_id').empty().append('<option value="">Select a Standard</option>');
                        $.each(data, function(key, value) {
                            $('#standard_id').append('<option value="' + value.id + '">' + value.standard_name + '</option>');
                        });
                    }
                });
            } else {
                $('#standard_id').empty().append('<option value="">Select a Standard</option>');
            }
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        let idCounter = 2;
        let subjectCounter = 1;
        $('#addmore').click(function() {

            var chapterHtml = `
                    <div class="row  added-chapter">
                    <div style="text-align:end" >
                        <button class="btn btn-danger remove-chapter" style="width:100px" >cancel</button>
                     </div>
               <div class="form-group mb-3" id="main_subject${idCounter}">
                        <label for="subject_name">Subject Name </label>
                        <input type="text" class="form-control" id="subject_name${idCounter}" name="subject_name[]" placeholder="Subject Name">
                       
                    </div>
                    <input type="hidden" id="currentID" value="${idCounter}">
                   
                     <div id="optionalDiv${idCounter}" class="hidden ">
                      <br>
                                <div class="row ">
                                <div class="col-md-12 is_addmore_subject">
                                    
                                        <button class="btn btn-success " style="float:right" id="" type="button" onclick="is_optional_added_last(${idCounter})">plus</button>
                                        <div class="is_optional_added_last${idCounter}"></div> 

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="is_optional_added_second${idCounter}">
                                    <div class="row">
                                    <div class="col-md-10 ">
                                    <label for="subject_name">Subject Name</label>    
                                        
                                        <input type="text" class="form-control" id="subject_sub_name${idCounter}" name="subject_sub_name[${subjectCounter}][]" placeholder="Subject Name" >
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-10 ">
                                    <label for="subject_name">Subject Name</label>    
                                        
                                        <input type="text" class="form-control" id="subject_sub_name${idCounter}" name="subject_sub_name[${subjectCounter}][]"  placeholder="Subject Name">
                                    </div>
                                  </div>
                                    </div>
                                </div>
                            </div>
                    <div class="form-group mb-3">
                        <label for="is_optional">Is Optional?</label>
                        <select class="form-control" id="is_optional${idCounter}" name="is_optional[]" onchange="toggleDiv2(${idCounter})">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>`;

            idCounter++;
            subjectCounter++;

            $('.add-chapter-btn').before(chapterHtml);
            $('.remove-chapter').click(function() {
                $(this).closest('.added-chapter').remove();
            });
            $('#imageInput').change(function() {
                readURL(this);
            });
        });

    });
    document.getElementById('addmore_subject').addEventListener('click', function() {
        let i = document.querySelectorAll('.is_optional_added').length + 1;

        let chapterHtml2 = `<br>
        <div>
            <div class="row is_optional_added${i}">
                <div class="col-md-10">
                    <label for="subject_name">Subject Name</label>    
                    <input type="text" class="form-control" id="subject_name" name="subject_sub_name[0][]" placeholder="Subject Name">
                    <div class="text-danger" id="error_subject_name"></div> 
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger remove-option-second mt-3" data-index="${i}" style="width:100px" type="button">cancel</button>
                </div> 
            </div>
        </div>`;

        i++;

        document.querySelector('.is_optional_added').insertAdjacentHTML('beforeend', chapterHtml2);
    });
    let j = 1;

    function is_optional_added_last(id) {
        let data = id - 1;
        let chapterContainer = $(`.is_optional_added_last${id}`);
        let i = chapterContainer.find('.is_optional_added_second').length + 1; // Increment the index

        // New HTML for the subject input
        let chapterHtml2 = `<br>
    <div>
        <div class="row is_optional_added_second${i}">
          <div class="row">
            <div class="col-md-10">
                <label for="subject_name_${i}">Subject Name</label>    
                <input type="text" class="form-control" id="subject_name_${i}" name="subject_sub_name[${data}][]" placeholder="Subject Name" >
                <div class="text-danger" id="error_subject_name_${i}"></div> 
            </div>
            <div class="col-md-2">
                <button class="btn btn-danger remove-option-second mt-3" data-index="${i}" style="width:100px" type="button">cancel</button>
            </div> 
        </div>
        </div>
    </div>`;

        document.querySelector('.is_optional_added_second' + id).insertAdjacentHTML('beforeend', chapterHtml2);
        j++;
    }

    // Use event delegation for removing options
    $(document).on('click', '.remove-option-second', function() {
        $(this).closest('.row').remove(); // Remove the corresponding input row
    });
</script>
<script>
    function toggleDiv() {
        const isOptional = document.getElementById('is_optional').value;
        const optionalDiv = document.getElementById('optionalDiv');

        if (isOptional == '1') {
            optionalDiv.classList.remove('hidden');
            $('#main_subject').hide();
            $('#subject_name').val('');
        } else {
            $('#main_subject').show();
            optionalDiv.classList.add('hidden');
            $('#subject_sub_name').val('');
        }
    }

    function toggleDiv2(id) {
        const isOptional2 = document.getElementById('is_optional' + id).value;
        const optionalDiv2 = document.getElementById('optionalDiv' + id);

        if (isOptional2 == '1') {

            optionalDiv2.classList.remove('hidden');
            $('#main_subject' + id).hide();
            $('#subject_name' + id).val('');
        } else {
            optionalDiv2.classList.add('hidden');
            $('#main_subject' + id).hide();
            $('#subject_sub_name' + id).val('');
        }
    }
    $('.remove-option').click(function() {
        $(this).closest('.is_optional_added').hide();

    });

    $(document).on('click', '.remove-option-second', function() {
        let i = $(this).data('index'); // Get the index from the data attribute
        $(this).closest('.is_optional_added_second' + i).remove();
    });
</script>