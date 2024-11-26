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

                    <h1>List of Student Marks</h1>
                   
                    <div class="table-responsive">
                    @foreach($data as $student_value) 
                    <p style="font-size: 16pt; margin: 0; padding: 0px; margin-top: 20px;">Student Name - <b>{{ $student_value['student_name'] }}</b> </p>

<br>
    
            <table class="table table-bordered" border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; text-align: center;">
                <thead>
                    <tr>
                        <th style="background-color: #f0f0f0;" >Subjects</th>
                        @if(isset($student_value['exam']))
                        @foreach($student_value['exam'] as $exam_value)
                        <th style="background-color: #f0f0f0;" >{{$exam_value['exam_name']}}</th>
                        @endforeach
                        @endif
                        <th style="background-color: #f0f0f0;" >Total Marks</th>
                        <th style="background-color: #f0f0f0;" >Obtain Marks</th>
                        <th style="background-color: #f0f0f0;" >Performance</th>
                        <th style="background-color: #f0f0f0;" >Grace</th>
               
                        <th style="background-color: #f0f0f0;">Grade</th>
                        <th style="background-color: #f0f0f0;">Percentage</th>
                    </tr>
                
                    
                
                </thead>
                <tbody>
                    @php
                        $printedSubjects = [];
                        $mainobtainmarks = 0;
                        $maintotalobtn = 0;
                        $maintotalMarks = 0;
                        $hundradtotal = 0;
                        $pasingmarks = 0;
                        $needmark = 0;
                        $pasorfl = 0;
                        $perform = $student_value['performance_mark'];
                        $grace = $student_value['grace_mark'];
                    @endphp
                    <input type="hidden" id="grace_get" value="{{$grace}}">
                    <input type="hidden" id="grace_get_second" value="{{$grace}}">

                    @if(isset($student_value['exam']))
                    @foreach($student_value['exam'] as $exam_value)
                        @if(isset($exam_value['subject_Data']))
                            @foreach($exam_value['subject_Data'] as $subject_value)
                                @if(!in_array($subject_value['subject_id'], $printedSubjects))
                                    <tr>
                                        <td>{{ $subject_value['subject_name'] }}</td>
                                        @php
                                            $totalMarks = 0; 
                                            $obtainmarks = 0;                            
                                        @endphp

                                        @foreach($student_value['exam'] as $exam_loop)
                                            @php
                                                $marksFound = false;
                                            @endphp
                                            
                                            @if(isset($exam_loop['subject_Data']))
                                                @foreach($exam_loop['subject_Data'] as $exam_subject_value)
                                                    @if($exam_subject_value['subject_id'] == $subject_value['subject_id'])
                                                    @if(isset($exam_subject_value['marks']) && count($exam_subject_value['marks']) > 0)
         
                                                        @foreach($exam_subject_value['marks'] as $mark_value)
                                                            <td>{{ $mark_value['marks'] }}</td>
                                                            @php
                                                                if($mark_value['marks'] == 'AB'){
                                                                    $marks = 0;
                                                                }else{
                                                                    $marks =$mark_value['marks'];
                                                                }
                                                                $obtainmarks += $marks; 
                                                                $totalMarks += $mark_value['total_marks'];
                                                                $marksFound = true;
                                                                if (isset($mark_value['passing_marks'])) {
                                                                    $pasingmarks= $mark_value['passing_marks'];
                                                                }
                                                            @endphp
                                                        @endforeach
                                                        @else
                                                        
                                                    @endif
                                                    @endif
                                                @endforeach
                                            @endif

                                            @if(!$marksFound)
                                                <td></td>
                                            @endif
                                        @endforeach

                                        <td><strong>{{ $obtainmarks }}</strong></td>
                                        <td>@php 
                                            if($totalMarks > 100){
                                                $obtainmks = $totalMarks ? ($obtainmarks * 100) / $totalMarks : 0; 
                                                $btnmks = round($obtainmks);
                                                $hundradtotal += 100;
                                            } else{
                                                $btnmks = $obtainmarks;
                                                $hundradtotal += $totalMarks;
                                            }
                                            
                                            $mainobtainmarks += $obtainmarks;
                                            $maintotalobtn += $btnmks;
                                            $maintotalMarks += $totalMarks;

                                            if( $pasingmarks > $btnmks){
                                                $pasorfl += 1;
                                                $needmark += $pasingmarks - $btnmks;
                                                $ned = $pasingmarks - $btnmks;
                                                $perform = $perform - $ned;
                                            }else{
                                                $ned = 0;
                                                $perform = $perform - 0;
                                            }

                                            @endphp
                                            {{ $btnmks }}
                                        </strong></td>
                                        <form method="post" id="siddhiGunForm" action="{{ url('/siddhi_gun/store') }}">
                                            @csrf
                                            <td>
                                            @if($ned)
                                                <input type="text" name="performance_mark" id="performance_mark{{$subject_value['subject_id']}}" style="display: none;" value="{{$ned}}" class="form-control">
                                                
                                            @else  
                                            @endif
                                            <!-- <input type="hidden" name="student_id" value="{{$student_value['id']}}" class="form-control">
                                            <input type="hidden" name="subject_id" value="{{$subject_value['subject_id']}}" class="form-control"> 
                                            <input type="hidden" name="exam_id" value="{{$exam_loop['exam_id']}}" class="form-control">  
                                            <input type="hidden" name="is_optional" value="{{$subject_value['is_optional']}}" class="form-control">  -->
                                            </td>
                                        </form>
                                        <td>@if($ned && $perform < 0 )    
                                                <input type="text" name="grace" id="grace{{$subject_value['subject_id']}}" class="form-control">
                                            @else
                                            @endif
                                        </td>
                                        <td>@php
                                            $percn = $btnmks+$ned;
                                            $percentage=$percn ? ($percn / 100) * 100 : 0;
                                            $grade=match (true) {
                                            $percentage>= 91 => 'A1',
                                            $percentage >= 81 => 'A2',
                                            $percentage >= 71 => 'B1',
                                            $percentage >= 61 => 'B2',
                                            $percentage >= 51 => 'C1',
                                            $percentage >= 41 => 'C2',
                                            $percentage >= 33 => 'D',
                                            $percentage >= 21 => 'E1',
                                            $percentage <= 20=> 'E2',
                                            };
                                            @endphp
                                        {{$grade}}</td>
                                    </tr>

                                    @php
                                        $printedSubjects[] = $subject_value['subject_id']; 
                                    @endphp
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td style="font-weight: bold;">Total Obtain Marks</td>
                        <td colspan="{{ count($student_value['exam'])}}">{{$maintotalMarks}}</td>
                        <td style="font-weight: bold;">{{$mainobtainmarks}}</td>
                        <td style="font-weight: bold;">{{$maintotalobtn}}</td>
                        <td style="font-weight: bold;">
                            @php 
                                $nedadorno = $student_value['performance_mark']+$student_value['grace_mark'];
                            @endphp
                        </td>
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;">@if($needmark < $nedadorno || $pasorfl == 0 ) Pass @else Fail @endif </td>
                        <td style="font-weight: bold;">@php $percentages =$maintotalobtn ? ($maintotalobtn / $hundradtotal) * 100 : 0; @endphp {{round($percentages,2)}}%</td>
                    </tr>
                </tfoot>
    </table>

@endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidhiGunInput = document.querySelector('input[name="sidhi_gun"]');
        const siddhiGunForm = document.getElementById('siddhiGunForm');

        if (sidhiGunInput && siddhiGunForm) {
            sidhiGunInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); 
                    siddhiGunForm.submit(); 
                }
            });
        } else {
            console.error('Form or input not found.');
        }
    });
    
</script>
<script>
  $(document).ready(function () {
    $('input[name="grace"]').on('keyup', function (e) {
    if (e.key === 'Enter') {
        const subjectId = $(this).attr('id').replace('grace', ''); 
        const graceMark = $('#grace_get').val();
        if(graceMark == '0'){

            const graceMark = $("#grace_get_second").val() - parseFloat($(this).val());
        }
        
        const performanceMark = parseFloat($('#performance_mark' + subjectId).val()) || 0;
        const graceMarks = parseFloat($(this).val()) || 0;
        alert(graceMarks);
        if (graceMark < graceMarks) {
            Swal.fire({
                icon: "error",
                text: "Please enter a valid grace mark",
                });
            e.preventDefault();
        } else {
            const totalMarks = performanceMark - graceMarks;
            const totalgrace = graceMark - graceMarks;
            if(totalgrace == '0'){
                const graceMark = $('#grace_get').val();
            }
            $("#grace_get").val(totalgrace);
            $('#performance_mark' + subjectId).show();
            $('#performance_mark' + subjectId).val(totalMarks);
        
        }
    }
});

    });
</script>
