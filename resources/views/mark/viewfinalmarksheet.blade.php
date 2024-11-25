
@foreach($student as $student_value)
<div style="box-sizing: border-box;"> 
<div style="font-family: system-ui, sans-serif; ">
           
          
            <div style="width: 190mm; height: 260mm; padding: 10px; border: 3px solid black; border-radius: 4px; font-family: Calibri, sans-serif;">
                <div style="width: 100%; margin-bottom: 10pt;">
                    <div style="border-radius: 4px; border: 2pt solid black; padding: 15pt; height: 67pt;">
                        <h1 style="text-align: center; font-size: 40pt; font-weight: bold; margin: 0; padding: 0;">{{ ucfirst($student_value['school_name']) }}
                        </h1>
                        <br />
                        <p style="text-align: center; font-size: 14pt; padding: 0; margin: 0;">{{ $student_value['address'] }}</p>
                    </div>
                </div>
                <br>
                <div style="width: 100%; margin-bottom: 10pt;">
                    <table style="width: 100%; border-bottom: 1pt solid black; font-size: 24pt;">
                        <tr>
                            <td style="width: 33%; font-size: 14pt; vertical-align: top;">
                                <p style="margin: 0;">Index No - <b>{{ ucfirst($student_value['school_index']) }}</b></p>
                            </td>
                            <td style="width: 34%; text-align: center; padding: 5px; background-color: black; color: white; font-size: 24pt; font-weight: bold; border-radius: 4px;">
                                Result Sheet
                            </td>
                            <td style="width: 33%; text-align: right; font-size: 14pt; vertical-align: top;">
                                <p style="margin: 0;margin-top:10px;"> <b>{{$student_value['medium']}}</b></p>
                            </td>

                        </tr>
                        <br>
                    </table>
                </div>


                <div style="width: 100%; margin-top: 10px;">
                    <table style="width: 100%; font-size: 14pt; border-collapse: collapse;">
                        <tr>
                            <td style="width: 33%; text-align: left;">
                                G R No - <b>{{ $student_value['gr_no'] }}</b>
                            </td>
                            <td style="width: 80%; text-align: center; font-size: 16pt; ">
                                Standard - <b>{{ ucfirst($student_value['standard_name']) }}-{{$student_value['division_name']}}</b>
                            </td>
                            <td style="width: 33%; text-align: right;">
                                Roll No - <b>{{ $student_value['roll_no'] }}</b>
                            </td>
                        </tr>
                    </table>
                </div>
                <p style="margin: 0;margin-top:10px;">UID - <b>{{ $student_value['uid'] }}</b> </p>
                <p style="font-size: 16pt; margin: 0; padding: 0px; margin-top: 20px;">Student Name - <b>{{ $student_value['student_name'] }}</b> </p>

               <br>
            
              
       
    
   <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; text-align: center;">
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
                                                $obtainmarks += $marks; // Add to total
                                                $totalMarks += $mark_value['total_marks'];
                                                $marksFound = true;
                                            @endphp
                                        @endforeach
                                        @else
                                        @php
                                            $totalMarks += $mark_value['total_marks'];
                                        @endphp
                                    @endif
                                    @endif
                                @endforeach
                            @endif

                            @if(!$marksFound)
                                <td></td>
                            @endif
                        @endforeach

                        <td><strong>{{ $obtainmarks }}</strong></td>
                        <td><strong>@php 
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
                            @endphp
                            {{ $btnmks }}
                        </strong></td>
                        <td></td>
                        <td></td>
                        <td>@php
                            $percentage=$btnmks ? ($btnmks / 100) * 100 : 0;
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
                        <td></td>
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
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;">@php $percentages =$maintotalobtn ? ($maintotalobtn / $hundradtotal) * 100 : 0; @endphp {{round($percentages,2)}}%</td>
            </tr>
        </tfoot>
    </table>


    <table style="width: 100%; margin-top: 110px;">
    <tr>
        <td style="text-align: left;">Teacher Signature:</td>
        <td style="text-align: right;">Principal Signature:</td>
    </tr>
</table>
</div>
<div style="position: absolute; margin-top: -70px; left: 20px; font-size: 12pt;">
<p >Date: 04-05-2020</p>


</div>


@if(!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif
@endforeach
</div>
