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
                <h2>Add Performance & Grace</h2>
                <form action="{{ url('performance-grace-add') }}" method="POST">
    @csrf
    <div class="form-group">
        <!-- Hidden Input for ID -->
        <input type="hidden" name="id" value="{{ $performance->id ?? '' }}">

        <!-- Performance Input -->
        <label for="Performance">Performance:</label>
        <input 
            type="number" 
            min="0" 
            max="100" 
            class="form-control" 
            id="Performance" 
            placeholder="Enter Performance" 
            name="performance" 
            value="{{ old('performance', $performance->performance ?? '') }}" 
            required>
    </div>

    <div class="form-group">
        <!-- Grace Input -->
        <label for="Grace">Grace:</label>
        <input 
            type="number" 
            min="0" 
            max="100" 
            class="form-control" 
            id="Grace" 
            placeholder="Enter Grace" 
            name="grace" 
            value="{{ old('grace', $performance->grace ?? '') }}" 
            required>
    </div>

    <button type="submit" class="btn btn-success mt-3">Submit</button>
</form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>