@include('sidebar_display')
<style>
input[readonly] {
    background-color: #e9ecef; 
    color: #6c757d;         
    border-color: #ced4da;   
    cursor: not-allowed;     
}
                                   </style>
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
                        <th style="background-color: #f0f0f0;" >Obtain Marks</th>
                        <th style="background-color: #f0f0f0;" >Out of 100</th>
                        <th style="background-color: #f0f0f0;" >Passing Mark</th>
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
                        $performm = $student_value['performance_mark'];
                        $perform = $student_value['performance_mark'];
                        $grace = $student_value['grace_mark'];
                    @endphp
                    <input type="hidden" id="grace_get" value="{{$grace}}">
                    <input type="hidden" id="perform_get" value="{{$perform}}">
                    @php
    $finalTotal = 0; // Initialize the total sum variable
@endphp
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
                                        @php $get_total = 0; @endphp
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
                                                                $performmark = $mark_value['performance_mark'];
                                                                $gracemmark = $mark_value['grace_mark'];
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
                                                $ned = $pasingmarks - $btnmks;
                                                $perform = $perform - $ned;
                                            }else{
                                                $ned = 0;
                                                $perform = $perform - 0;
                                            }
                                            $get_total = $get_total + $ned;
                                            $finalTotal += $get_total;
                                            @endphp
                                            
                                            {{ $btnmks }}
                                        </strong></td>
                                        <form method="post" id='marksForm' action="{{ url('/siddhi_gun/store') }}">
                                        @csrf

                                        <input type="hidden" name="student_id" value="{{$student_value['id']}}" class="form-control">
                                            <input type="hidden" name="subject_id[]" id="subject_id" value="{{$subject_value['subject_id']}}" class="form-control"> 
                                            <input type="hidden" name="exam_id" value="{{$exam_loop['exam_id']}}" class="form-control">  
                                            <input type="hidden" name="is_optional[]" value="{{$subject_value['is_optional']}}" class="form-control"> 
                                            <input type="hidden" name="outofmarks[]" value="{{$btnmks}}" id="outofmarks{{$subject_value['subject_id']}}">

                                        <td>@if($ned) <input type="text" name="performance_mark[]" id="performance_mark{{$subject_value['subject_id']}}" value="{{$ned}}" class="form-control" readonly disabled>@else
                                        <input type="text" name="performance_mark[]" id="performance_mark{{$subject_value['subject_id']}}" value="0" class="form-control" readonly disabled>@endif</td>
                                        <td>
                                           
                                       
                                            @if($ned)

                                           <input type="text" name="performance_get[]" id="performance_mark_label_hidden{{$subject_value['subject_id']}}" value="{{$performmark}}"  readonly class="form-control">  
                                           <!-- below code controller ma pass karva mate  -->
                                           <!-- <input type="text" name="performance_get[]" id="performance_mark_label_hidden{{$subject_value['subject_id']}}"    class="form-control">   -->
                                           @else
                                           <input type="hidden" name="performance_get[]" id="performance_mark_label_hidden{{$subject_value['subject_id']}}" value="0"  class="form-control">                                           
                                           @endif
                                           </td>
                                       
                                           
                                            @if($ned)
                                                <input type="hidden" id="ned_mark{{$subject_value['subject_id']}}" value="{{$ned}}">
                                                 
                                            @else 
                                            <!-- <input type="text" name="performance_mark[]" id="performance_mark{{$subject_value['subject_id']}}" style="display: none;" value="" class="form-control"> -->
                                                
                                            @endif
                                           
                                        </td>
                                        
                                        <td>
                                             <div style="display: none;" id="form_show{{$subject_value['subject_id']}}">
                                             <div class="d-flex subject-grace" id="subject{{$subject_value['subject_id']}}">
                                             @if($ned)
                                              <div class="d-flex grace-disable{{$subject_value['subject_id']}}">
                                              <input 
                                                    type="number" 
                                                    min="0" 
                                                    step="1" 
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                                    name="grace[]" 
                                                    id="grace_input{{$subject_value['subject_id']}}" 
                                                    onchange="handleKeyDown(this, {{$subject_value['subject_id']}},{{$ned}})" 
                                                    value="{{$gracemmark}}"
                                                    class="form-control grace-input">
                                                <!-- <button type="button" class="btn btn-success submit_grace" data-subject-id="{{$subject_value['subject_id']}}">Submit</button> -->
                                              </div></div
                                             >
                                             <p id="result{{$subject_value['subject_id']}}"></p>

                                             @else
                                             <input type="hidden" name="grace[]" value="0"  class="form-control">
                                            
                                             @endif
                                             </div>
                                        </td>
                                        
                                        <td>@if($ned == 0)
                                        @php
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
                                        {{$grade}}
                                        @else
                                        @php
                                        $percn = $btnmks;
                                        @endphp
                                        <p id="grade_display_{{$subject_value['subject_id']}}"></p>
                                        @endif
                                        <input type="hidden" name="prc" value="{{$percn}}" id="prc{{$subject_value['subject_id']}}"></td>
                                    </tr>

                                    @php
                                        $printedSubjects[] = $subject_value['subject_id']; 
                                    @endphp
                                @endif
                            @endforeach
                                        
                        @endif
                      
                    @endforeach
                    <input type="hidden" value="{{$finalTotal}}" id="total_need_mark">
                    @endif
                  
                </tbody>
                <tfoot>
                    <tr>
                        <td style="font-weight: bold;">Total Obtain Marks</td>
                        <td colspan="{{ count($student_value['exam'])}}"></td>
                        <td style="font-weight: bold;">{{$mainobtainmarks}}</td>
                        <td style="font-weight: bold;">{{$maintotalobtn}}</td>
                        <td style="font-weight: bold;">
                            @php 
                                $nedadorno = $student_value['performance_mark']+$student_value['grace_mark'];
                            @endphp
                        </td>
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;"></td>
                        <td><b id="passskfail">@if($finalTotal <= $nedadorno) Pass @else Fail @endif </b></td>
                        <td style="font-weight: bold;">@php $percentages =$maintotalobtn ? ($maintotalobtn / $hundradtotal) * 100 : 0; @endphp {{round($percentages,2)}}%</td>
                    </tr>
                </tfoot>
        </table>

                @endforeach
                
                @if($finalTotal <= $nedadorno)  
                <button type="submit" class="btn btn-success"  style="float:right"  name="submit" >submit</button>
                 @else  
                 <button type="submit" class="btn btn-success"  style="float:right"  name="submit" disabled>submit</button> 
                  @endif
                   
        </form> 
                       <button class="btn btn-success mb-3" style="margin-left: 50vh;" onclick="calculatePerformance()">Calculate Performance</button> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>

<script>
   
   $(document).ready(function () {
    calculatePerformance();
    const graceMark = parseFloat($('#grace_get').val()) || 0; 
    const perform_get = parseFloat($('#perform_get').val()) || 0; 
    const total=graceMark+perform_get;
    const requirement_mark = parseFloat($('#total_need_mark').val()) || 0;
  
    if(requirement_mark <= perform_get){
        autoset()

    } 
    else if(requirement_mark > total){
        console.log('fail');
    }
    else if(requirement_mark > perform_get && requirement_mark <= total){
        document.querySelectorAll('input[name="subject_id[]"]').forEach(function(input) {
            const subjectId = input.value; 
            
            $("#form_show" + subjectId).show();// Log the value
        });

        


        
    }


    function autoset(){
        $('input[id^="performance_mark"]').each(function (index) {
                const currentInput = $(this);
                const nedValue = parseFloat(currentInput.val()) || 0; 
                
                if (index === 0 ) {
                    if(perform_get<nedValue){
                        previousResult = perform_get - nedValue;
                        
                        currentInput.val(performance);
            
                    }else{
                        previousResult = perform_get - nedValue;
                        currentInput.val(nedValue);
                    }
                } else {
                    if(previousResult==null || previousResult==''){
                        return;     
                    }else{
                        const initialPreviousResult = previousResult;
                        const calculatedResult = Math.abs(previousResult - nedValue);
                        const increase = previousResult - initialPreviousResult;
                        currentInput.val(nedValue);
                        previousResult = increase; 
                    }
                }  
            });  
                finalResult = previousResult;


                $('input[id^="performance_mark_label_hidden"]').each(function(index) {
                const currentInputHidden = $(this); 
                const performanceMarks = document.querySelectorAll('input[name="performance_mark[]"]');
                //console.log(index);
                const nedValue2 = parseFloat(performanceMarks[index].value); 
                if(nedValue2 == '0'){

                }
                
                // $('input[id^="grace_input"]').each(function(index) {
                // const grace_input = $(this); 
                // const performanceMarks = document.querySelectorAll('input[name="performance_mark[]"]');
                
                // const nedValue2 = parseFloat(performanceMarks[index].value) || 0; 
                // console.log(performance+""+nedValue2);
                if (index === 0) {
                    
                    if(performance>nedValue2){
                        previousResult2 = performance - nedValue2; 
                        currentInputHidden.val(nedValue2);
                    }else{
                        previousResult2 = performance - nedValue2; 
                        currentInputHidden.val(nedValue2);
                       
           
                    }
                } else {
                    if (previousResult2 == null || previousResult2 === '') {
                        return; 
                    } else {
                        const initialPreviousResult = previousResult2;
                        const calculatedResult = Math.abs(previousResult2 - nedValue2);
                        if(previousResult2<nedValue2){
                            currentInputHidden.val(previousResult2); 
                            previousResult2 = calculatedResult;
                        }else{
                            currentInputHidden.val(nedValue2); 
                            previousResult2 = calculatedResult;
                        }
                        
                    }
                }
              
        
        });
                finalResult2 = previousResult2; 
    }

  

   });
   
let totalsetvalue = 0;
   function handleKeyDown(element,subject_id,need_mark) {
    
    const graceMark = parseFloat($('#grace_get').val()) || 0; 
    const perform_get = parseFloat($('#perform_get').val()) || 0; 
    let forremperform_get = parseFloat($('#perform_get').val()) || 0; 
    const total=graceMark+perform_get;
    const requirement_mark = parseFloat($('#total_need_mark').val()) || 0;
    const current_input= $(element).val();
    var grace_limit=requirement_mark-perform_get;
    let global_graceset = 0; 
    let global_performance_set = 0; 
    
    
    const set_performance = Math.abs(need_mark - current_input);
    $("#performance_mark_label_hidden"+subject_id).val(set_performance);
    performance_calculation(set_performance,perform_get,subject_id);
    $('input[name="grace[]"]').each(function (index) {
        
        const current_input = parseFloat($(this).val()) || 0; 
        global_graceset += current_input; 
        //
        

        //forremperform_get -= totalsetvalue;
        
    });
  
//   console.log(totalsetvalue+"<"+perform_get);
      

    


    // console.log(totalsetvalue);
    if(need_mark < current_input){
        
        $("#grace_input"+subject_id).val(0);
        $("#performance_mark_label_hidden"+subject_id).val(0);
        
    }
    
    if(grace_limit < current_input){
        global_graceset +=grace_limit;
        $("#performance_mark_label_hidden"+subject_id).val(0);
        $("#grace_input"+subject_id).val(0);
       

    }else{
        
        if(global_graceset > grace_limit){
            
             $("#grace_input"+subject_id).val(0);
             $("#performance_mark_label_hidden"+subject_id).val(0);
        }else
        {
           
            // if(perform_get >= set_performance)
        //    {
            
        // const difference = Math.abs(parseFloat(need_mark) - parseFloat(current_input)) || 0;
        // totalsetvalue += difference; 
        // // console.log(totalsetvalue+'<='+ perform_get);
            // if(totalsetvalue <= perform_get)
            //     {
                    
                    
        // $("#performance_mark_label_hidden"+subject_id).val(set_performance);
                    // totalsetvalue -= difference; 
                   
                    // $("#performance_mark_label_hidden"+subject_id).val(0);
                // }
                // else{
                //      totalsetvalue -= difference; 
                //      $("#performance_mark_label_hidden"+subject_id).val(0);
                // }
               
               
                

        //     // $("#performance_mark_label_hidden"+subject_id).val(set_performance);
        // //    }else{
        // //     $("#performance_mark_label_hidden"+subject_id).val(0);

        // //    }
        }
        
    }
   
    sessionStorage.setItem('grace_limit', grace_limit);

    
    



   
}
function performance_calculation(set_performance,perform_get,subject_id){
       
    const performance = document.querySelectorAll('input[name="performance[]"]'); 
    const performance_get = Array.from(performance).map(input => parseFloat(input.value) || 0); 
    const total = performance_get.reduce((sum, value) => sum + value, 0); 
    if(total > perform_get){
        $("#performance_mark_label_hidden"+subject_id).val(0);
    }


    
}

function calculatePerformance() {
        
    const subjectInputs = document.querySelectorAll('#subject_id'); 
        const subjectIds = Array.from(subjectInputs).map(input => input.value);
        var pasfl = 0;
        
        subjectIds.forEach((id, index) => {
            const gracemarks = document.querySelectorAll('input[name="grace[]"]');

            const graceValue = parseFloat(gracemarks[index]?.value) || 0;

            const passingmark_value = document.querySelectorAll('input[name="performance_mark[]"]');

            const passing_marks = parseFloat(passingmark_value[index]?.value) || 0;
            var grace_limit = sessionStorage.getItem('grace_limit');
            let global_graceset = 0; 
            $('input[name="grace[]"]').each(function (index) {
        
                const current_input = parseFloat($(this).val()) || 0; 
                global_graceset += current_input; 
            });
            
            if(graceValue=='0' && passing_marks!='0' && grace_limit == global_graceset){ 
                 // console.log(id);
                $("#grace_input"+id).val(0);
                $("#performance_mark_label_hidden"+id).val(passing_marks);
            }

            var graceInput = parseFloat($("#grace_input" + id).val()) || 0;
            var performanceMark = parseFloat($("#performance_mark_label_hidden" + id).val()) || 0;
            var outOfMarks = parseFloat($("#outofmarks" + id).val()) || 0;

            var percn = graceInput + performanceMark + outOfMarks;

            var  percentage = percn ? (percn / 100) * 100 : 0;
            let grade = '';
            getgrade(percentage,grade,id);
            if(percentage < 33 && percentage >= 21){
                pasfl = pasfl+1;
            }else{
                pasfl = pasfl+0;
            }
        });
        if(pasfl > 0){
            $('#passskfail').html('Fail');
        }else{
            $('#passskfail').html('Pass');
        }

    }
    function getgrade(percentage, grade, subjectId) {
                
                if (percentage >= 91) grade = 'A1';
                else if (percentage >= 81) grade = 'A2';
                else if (percentage >= 71) grade = 'B1';
                else if (percentage >= 61) grade = 'B2';
                else if (percentage >= 51) grade = 'C1';
                else if (percentage >= 41) grade = 'C2';
                else if (percentage >= 33) grade = 'D';
                else if (percentage >= 21) grade = 'E1';
                else grade = 'E2';
            
                $('#grade_display_' + subjectId).text(grade);
}

</script>
<script>


    $(document).on('submit', '#marksForm', function (e) {
        e.preventDefault();

        // Collect form data
        let formData = $(this).serialize();

        // AJAX request
        $.ajax({
            url: "{{ route('siddhi_gun.store') }}",
            method: "POST",
            data: formData,
            success: function (response) {
                if (response.success) {
                    Swal.fire(response.message);

                    setTimeout(function () {
                        location.reload();
                    }, 3000); 
                } else {
                    alert('Failed to update marks.');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert('Something went wrong!');
            }
        });
    });
</script>

