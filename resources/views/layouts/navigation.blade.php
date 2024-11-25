<meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <!-- <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div> -->

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('schools')" :active="request()->routeIs('schools')" style=" text-decoration: none;">
                        {{ __('All School') }}
                    </x-nav-link>
                </div>
            </div>
        

           
            
        @if (Request::segment(1) != 'schools' || (Request::segment(2) == 'view'))
            <?php $schools = getSchoolList(); ?>
            <div class="mt-3">
                <select 
                    class="inline-flex items-center px-5 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150" 
                    name="school_id"
                    onchange="if (this.value) window.location.href=this.value">
                    <option value="">Select School</option>
                    
                    @foreach ($schools as $school)
                        <option value="{{ url('schools/view', $school->id) }}"  {{ session('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

 
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
          
        </div>
    </div>
    <div class="sidebar"style="display:none;">
        <!-- <a class="{{ Request::segment(1) == 'dashboard' ? 'active' : '' }}" href="{{url('dashboard')}}">Dashboard</a> -->
        <!-- <a class="{{ Request::segment(1) == 'dashboard'   ? 'active' : '' }}" href="{{url('dashboard')}}">Dashboard</a> -->
        <a class="{{ Request::segment(1) == 'standards' ? 'active' : '' }}" href="{{url('standards')}}">Standard</a>
        <a class="{{ Request::segment(1) == 'subjects' ? 'active' : '' }}" href="{{url('subjects')}}">Subject</a>
        <a class="{{ Request::segment(1) == 'division' ? 'active' : '' }}" href="{{url('division')}}">Division</a>
        <a class="{{ Request::segment(1) == 'exam' ? 'active' : '' }}" href="{{url('exam')}}">Exam</a>
        <a class="{{ Request::segment(1) == 'students' ? 'active' : '' }}" href="{{url('students')}}">Student</a>
        <a class="{{ Request::segment(1) == 'marks' ? 'active' : '' }}" href="{{url('marks')}}">Mark</a>
        <a class="{{ Request::segment(1) == 'marksheet' ? 'active' : '' }}" href="{{url('marksheet')}}">Final Exam Mark Sheet</a>
        <a class="{{ Request::segment(1) == 'performance-grace' ? 'active' : '' }}" href="{{url('performance-grace')}}">Add Performance/Grace</a>
    </div>
    <!-- Responsive Navigation Menu -->
   
</nav>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            html: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `{!! session('error') !!}`, 
            showConfirmButton: true
        });
    </script>
@endif
<style>
   .row{
    --bs-gutter-x:0 !important;
   }
    .sidebar {
  margin: 0;
  padding: 0;
  width: 200px;
  background-color: #fff;
  position: fixed;
  height: 100%;
  overflow: auto;
}

.sidebar a {
  display: block;
  color: black;
  padding: 16px;
  text-decoration: none;
}
 
.sidebar a.active {
  background-color: #04AA6D;
  color: white;
}

.sidebar a:hover:not(.active) {
  background-color: #555;
  color: white;
}

div.content {
  margin-left: 200px;
  padding: 1px 16px;
  height: 1000px;
}

.table-container {
        width: 100%;
        overflow-x: auto;
    }
    
@media screen and (max-width: 700px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: relative;
  }
  .sidebar a {float: left;}
  div.content {margin-left: 0;}
}
@media screen and (max-width: 1200px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: relative;
  }
  .sidebar a {float: left;}
  div.content {margin-left: 0;}
}
@media screen and (max-width: 400px) {
  .sidebar a {
    text-align: center;
    float: none;
  }
}
</style>