<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="container mt-5">

                     <h1 class="p-4 ">Dashboard</h1>
                     <hr>
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="row">
                        <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body fw-bold">
                                Count School : {{$school_count}}
                            </div>
                         </div>
                    
                        </div>
                        <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body fw-bold">
                                Count Standard : {{$Standard_count}}
                            </div>
                         </div>
                    
                        </div>
                        <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body fw-bold">
                                Count Subject : {{$Subject_count}}
                            </div>
                          </div>
                    
                         </div>
                      </div>
                    </div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="row">
                        <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body fw-bold">
                                Count Division : {{$Division_count}}
                            </div>
                         </div>
                    
                        </div>
                        <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body fw-bold">
                                Count Exam : {{$Exam_count}}
                            </div>
                         </div>
                    
                        </div>
                        <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body fw-bold">
                                Count Student : {{$Student_count}}
                            </div>
                          </div>
                    
                         </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>


    
</x-app-layout>
