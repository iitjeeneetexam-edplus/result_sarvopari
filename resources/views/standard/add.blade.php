@include('sidebar_display')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-8 col-lg-7">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1>Add Standard</h1>



                        <form action="{{ route('standards.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="school_id">School</label>
                               
                                <input type="text"  value="{{ $schools->school_name}}" class="form-control" disabled>
                                <input type="hidden" name="school_id" value="{{ $schools->id}}">
                            </div>
                            <div class="form-group">
                                <label for="standard_name">Standard Name</label>
                                <input type="text" name="standard_name" id="standard_name" class="form-control" placeholder="Enter Standard name" value="{{ old('standard_name')}}">
                                @error('standard_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="school_index">School Index</label>
                                <input type="text" class="form-control" id="school_index" name="school_index" value="{{ old('school_index')}}" placeholder="School Index">
                                @error('school_index')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="status" value="1">
                             </div>
                            <br>
                            <button type="submit" class="btn btn-success">Add Standard</button>
                            <a href="{{ route('standards.index') }}" class="btn btn-secondary " style="float:right">Back to Create Standard</a>
                        </form>


                    </div>
                    </body>

                    </html>
</x-app-layout>