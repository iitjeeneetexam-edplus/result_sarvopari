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
                                        <form method="post" id='somelink' action="{{ url('/siddhi_gun/store') }}">
                                        @csrf

                                        <input type="hidden" name="student_id" value="{{$student_value['id']}}" class="form-control">
                                            <input type="hidden" name="subject_id[]" id="subject_id" value="{{$subject_value['subject_id']}}" class="form-control"> 
                                            <input type="hidden" name="exam_id" value="{{$exam_loop['exam_id']}}" class="form-control">  
                                            <input type="hidden" name="is_optional[]" value="{{$subject_value['is_optional']}}" class="form-control"> 


                                        <td>@if($ned) <input type="text" name="performance_mark[]" id="performance_mark{{$subject_value['subject_id']}}" value="{{$ned}}" class="form-control" readonly disabled>@endif</td>
                                        <td>
                                           
                                           
                                            @if($ned)
                                           <input type="text" name="performance[]" id="performance_mark_label_hidden{{$subject_value['subject_id']}}" value="{{(!empty($performmark))?$performmark:''}}">  
                                           <!-- <label id="performance_mark_label{{$subject_value['subject_id']}}" readonly disabled  >{{$performmark}}<label>  -->
                                           @else
                                           <input type="hidden" name="performance[]" value="0"  class="form-control">                                           
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
                                                <input type="number"min="0" 
               step="1" 
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"  name="grace[]" id="grace_input{{$subject_value['subject_id']}}" value=""  class="form-control grace-input">
                                                &nbsp;&nbsp;
                                                <button type="button" class="btn btn-success submit_grace" data-subject-id="{{$subject_value['subject_id']}}">Submit</button>
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
                        <td>@if($finalTotal <= $nedadorno) Pass @else Fail @endif </td>
                        <td style="font-weight: bold;">@php $percentages =$maintotalobtn ? ($maintotalobtn / $hundradtotal) * 100 : 0; @endphp {{round($percentages,2)}}%</td>
                    </tr>
                </tfoot>
        </table>

                @endforeach

                @if($finalTotal < $nedadorno || $pasorfl == 0 )  
                <button class="btn btn-success mb-3" style="float:right">Submit</button>
                 @else  
                 <button class="btn btn-success mb-3" style="float:right" disabled>Submit</button> @endif
                   
                    </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>

<script>
   
    
</script>
<script>

//grace calculation code
    





       
    $(document).on('click', '.submit_grace', function (e) {
        e.preventDefault();
        
        const subjectId = $(this).data('subject-id');
        const graceMark = parseFloat($('#grace_get').val()) || 0; 
        
        const ned_mark = parseFloat($('#ned_mark'+ subjectId).val()) || 0; 
        const performanceMark = parseFloat($('#performance_mark' + subjectId).val()) || 0; 
        const graceInput = $('#grace_input' + subjectId).val(); 
        const prc = $('#prc' + subjectId).val();
        let totalGrace = 0;
        const percn = (graceInput)+(prc);
        const percentage = percn ? (percn / 100) * 100 : 0;
            let grade = '';
        
            
            
        getgrade(percentage,grade,subjectId);

        $('input[id^="grace_input"]').each(function() {
            const graceInput = $(this).val(); 
            totalGrace += parseFloat(graceInput) || 0;
          
        });
         if(totalGrace > graceMark)
         {
            $('#grace_input'+subjectId).val(0);
            Swal.fire({
                icon: "error",
                text: "Please enter a valid grace mark .",
            });
            return; 
         }
        

        
         if (graceMark === 0 || graceInput > graceMark) {
            $('#grace_input'+subjectId).val(0);
            Swal.fire({
                icon: "error",
                text: "Please enter a valid grace mark.",
            });
            return; 
        }
        if(ned_mark < graceInput){
            Swal.fire({
                icon: "error",
                text: "Please enter a valid grace marks.maximum enter grace:-"+graceMark,
            });
            return; 
        }
         const remainingGrace = ned_mark - graceInput;
         const updatedPerformanceMark = ned_mark - graceInput;
        //  $('#grace_get').val(graceInput);
        // $('#performance_mark_label'+subjectId).show();
        // $('#performance_mark_label_hidden' + subjectId).val(updatedPerformanceMark).show();
        // $('#performance_mark_label' + subjectId).text(updatedPerformanceMark).show();
        $('#grace_input' + subjectId).text(2).show();
      


            Swal.fire({
                icon: "success",
                text: "Grace mark and performance mark updated successfully.",
            });
    });

   



</script>
<Script>
    $(document).ready(function () {
      
       
        const performance = parseFloat($('#perform_get').val()) || 0; 
        const grace_get = parseFloat($('#grace_get').val()) || 0; 
        const totalAssign =performance+grace_get;
        const TotalNeeded = parseFloat($('#total_need_mark').val()) || 0;
        
        
        const total_need_mark = parseFloat($('#total_need_mark').val()) || 0; 
            // Get all subject IDs
        const subjectInputs = document.querySelectorAll('#subject_id');
        const subjectIds = Array.from(subjectInputs).map(input => input.value);

        // Get all performance values
        const performanceInputs = document.querySelectorAll('input[name="performance[]"]');
        const performances = Array.from(performanceInputs).map(input => input.value);

        // Combine subject IDs and performances into a single array
        const mergedArray = subjectIds.map((subjectId, index) => ({
            subjectId: subjectId,
            performance: performances[index]
        }));

        // Iterate over the merged array
        mergedArray.forEach(item => {
            const { subjectId, performance } = item;

            // Show form based on some condition
            if (total_need_mark <= totalAssign) {
                $("#form_show" + subjectId).show();
            }
            const prc = $('#prc' + subjectId).val();
            const percn = Number(performance) + Number(prc); // Ensure numeric addition
            const percentage = percn ? (percn / 100) * 100 : 0;

            let grade = '';
            getgrade(percentage, grade,subjectId);
        });


       

        // console.log(TotalNeeded+"<"+totalAssign);
        if(totalAssign < TotalNeeded) 
        {
          
        }
        else if(TotalNeeded <= totalAssign)
        {
            // console.log("hi"); 
            let previousResult  = performance; 
            let previousResult2  = performance; 
            let graceresult2 = grace_get;
            let finalResult = previousResult; 
            let finalResult2 = previousResult2; 
            const subjectId = $(this).data('subject-id');
             $('input[id^="performance_mark"]').each(function (index) {
                const currentInput = $(this);
                
                const nedValue = parseFloat(currentInput.val()) || 0; 
               
                if (index === 0 ) {
                    if(performance<nedValue){
                        previousResult = performance - nedValue;
                        currentInput.val(performance);
            
                    }else{
                        previousResult = performance - nedValue;
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
                
                const nedValue2 = parseFloat(performanceMarks[index].value) || 0; 
                
                
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

                $('input[id^="grace_input"]').each(function (index) {
                      
                const currentInputHidden = $(this); 
                const performanceMarkInput = $('input[id^="performance_mark_label_hidden"]').eq(index); 
                const nedValue = parseFloat(performanceMarkInput.val()) || 0; 
                const performark = $('input[id^="performance_mark"]').eq(index); 
                const performarkneed = parseFloat(performark.val()) || 0;



                const performanceMarks = document.querySelectorAll('input[name="performance_mark[]"]');
                
                const nedValue2 = parseFloat(performanceMarks[index].value) || 0; 
              



                // $('input[id^="performance_mark"]').each(function (outerIndex) {
                //     const currentInput = $(this).val();
                //     const currentInput2 = $(`input[id^="performance_mark_label_hidden"]:eq(${outerIndex})`).val();
                    //  console.log(`Comparing: ${currentInput2}`);
               


                // const performanceMarks = document.querySelectorAll('input[name="performance_mark[]"]');
                
                // const nedValue2 = parseFloat(performanceMarks[index].value) || 0;


                
                if(performarkneed!=nedValue)
                {
                    
                    
                    if (index === 0) {
                    // graceresult2 = grace_get - nedValue; 
                    graceresult2 = nedValue2 - nedValue; 
                    currentInputHidden.val(graceresult2); 
                    
                
                } else {
                   
                    if (graceresult2 == null || graceresult2 === '') {
                        return; 
                    } else {
                        const initialPreviousResult = graceresult2;
                        // const calculatedResult = Math.abs(graceresult2 - nedValue); 
                        // const calculatedResult = Math.abs(graceresult2 - nedValue); 
                        const calculatedResult = Math.abs(nedValue2 - nedValue); 
                          
                        currentInputHidden.val(calculatedResult); 
                        graceresult2 = calculatedResult; 
                    }
                    
                }
                    if(performarkneed==nedValue){
                        currentInputHidden.val("");
                    }
                }
                
            });
                
            

                finalResult = graceresult2; 

        }
        else 
        {
            const grasLimite=TotalNeeded-performance;
        }
       
});
    function getgrade(percentage,grade,subjectId){
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


    $(document).ready(function() {
    const graceLimit = 10; // Set the maximum allowed sum
    let currentTotal = 0; // Track the current sum

    const totalAssign =performance+grace_get;
    const TotalNeeded = parseFloat($('#total_need_mark').val()) || 0;
    if(TotalNeeded < totalAssign)
    {
        // let previousResult  = performance; 
        // let finalResult = previousResult; 
        // const subjectId = $(this).data('subject-id');
    }
    
    function updateGraceInputs() {
        let currentTotal = 0;
        $(".grace-input").each(function () {
            const value = parseInt($(this).val()) || 0;
            currentTotal += value;
        });

        if (currentTotal >= graceLimit) {
            const subjectInputs = document.querySelectorAll('.grace-input');

    subjectInputs.forEach(input => {
        input.addEventListener("input", function () {
            const subjectId = this.id.replace('grace_input', ''); // Extract subject ID from the ID
            const submitButton = document.querySelector(`.submit_grace[data-subject-id="${subjectId}"]`);

            // If the input value is empty, disable the button
            if (!this.value.trim()) {
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        });
    });

    // On page load, disable all buttons where inputs are empty
    subjectInputs.forEach(input => {
        const subjectId = input.id.replace('grace_input', '');
        const submitButton = document.querySelector(`.submit_grace[data-subject-id="${subjectId}"]`);

        if (!input.value.trim()) {
            submitButton.disabled = true;
        }
    });
            
            
        } else {
            const subjectInputs = document.querySelectorAll('.grace-input');

subjectInputs.forEach(input => {
    input.addEventListener("input", function () {
        const subjectId = this.id.replace('grace_input', ''); // Extract subject ID from the ID
        const submitButton = document.querySelector(`.submit_grace[data-subject-id="${subjectId}"]`);

        // If the input value is empty, disable the button
        if (!this.value.trim()) {
            submitButton.disabled = true;
        } else {
            submitButton.disabled = false;
        }
    });
});

// On page load, disable all buttons where inputs are empty
subjectInputs.forEach(input => {
    const subjectId = input.id.replace('grace_input', '');
    const submitButton = document.querySelector(`.submit_grace[data-subject-id="${subjectId}"]`);

    if (!input.value.trim()) {
        submitButton.disabled = true;
    }
});
        }
    }

    // Bind the event listener for input
    $(".grace-input").on("input", function () {
        updateGraceInputs();
    });

    // Call the function on page load
    updateGraceInputs();
});



</Script>