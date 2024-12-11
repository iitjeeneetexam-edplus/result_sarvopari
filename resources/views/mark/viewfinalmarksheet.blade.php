

@foreach($student as $student_value)
<div style="box-sizing: border-box;"> 
<div style="font-family: system-ui, sans-serif; ">
           
          
            <div style="width: auto; height: 260mm; padding: 10px; border: 3px solid black; border-radius: 4px; font-family: Calibri, sans-serif;">
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
                                <p style="margin: 0;">(!empty($student_value['school_index'])?'Index No -':'') <b>{{ ucfirst($student_value['school_index']) }}</b></p>
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
            
              
               <style>
                    .custom-th {
                        background-color: #f0f0f0;
                        border: 1px solid black;
                        width: 80px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        text-align: center;
                        word-wrap: break-word;
                    }

                    .custom-table {
                        border-collapse: collapse;
                        table-layout: fixed;
                        width: 100%;
                        font-size:10px;
                    }

                    .custom-td {
                        border: 1px solid black;
                        text-align: center;
                    }
                 </style>

<table class="custom-table">
    <thead>
        <tr>
            <th class="custom-th">Subjects</th>
            @if(isset($student_value['exam']))
                @foreach($student_value['exam'] as $exam_value)
                    <th class="custom-th">{{ $exam_value['exam_name'] }}</th>
                @endforeach
            @endif
            <th class="custom-th">Obtain Marks</th>
            <th class="custom-th">Out of 100</th>
            <th class="custom-th">Performance</th>
            <th class="custom-th">Grace</th>
            <th class="custom-th">Grade</th>
            <th class="custom-th">Percentage</th>
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
            $pasorfl = 0;
            $performm = $student_value['performance_mark'];
            $perform = $student_value['performance_mark'];
            $grace = $student_value['grace_mark'];
            $nedadorno = $performm+$grace;
            
            
        @endphp

        @if(isset($student_value['exam']))
    @foreach($student_value['exam'] as $exam_value)
        @if(isset($exam_value['subject_Data']))
            @foreach($exam_value['subject_Data'] as $subject_value)
                @if(!in_array($subject_value['subject_id'], $printedSubjects))
                    <tr>
                        <td style="border: 1px solid black;">{{ $subject_value['subject_name'] }}</td>
                        @php
                            $totalMarks = 0; 
                            $obtainmarks = 0;                            
                        @endphp

                        @foreach($student_value['exam'] as $exam_loop)
                            @php
                                $get_total = 0;
                                $marksFound = false;
                            @endphp
                            @if(isset($exam_loop['subject_Data']))
                                @foreach($exam_loop['subject_Data'] as $exam_subject_value)
                                    @if($exam_subject_value['subject_id'] == $subject_value['subject_id'])
                                    @if(isset($exam_subject_value['marks']) && count($exam_subject_value['marks']) > 0)
                                        @foreach($exam_subject_value['marks'] as $mark_value)
                                            <td style="border: 1px solid black;">{{ $mark_value['marks'] }}</td>                                            
                                            @php
                                                if($mark_value['marks'] == 'AB'){
                                                    $marks = 0;
                                                }else{
                                                    $marks =$mark_value['marks'];
                                                }
                                                if (isset($mark_value['passing_marks'])) {
                                                    $pasingmarks= $mark_value['passing_marks'];
                                                }
                                                $performmark = $mark_value['performance_mark'];
                                                $gracemmark = $mark_value['grace_mark'];
                                                $obtainmarks += $marks; // Add to total
                                                $totalMarks += $mark_value['total_marks'];
                                                $marksFound = true;
                                            @endphp
                                        @endforeach
                                        @else
                                        
                                    @endif
                                    @endif
                                @endforeach
                            @endif

                            @if(!$marksFound)
                                <td style="border: 1px solid black;"></td>
                            @endif
                        @endforeach

                        <td style="border: 1px solid black;"><strong>{{ $obtainmarks }}</strong></td>
                        <td style="border: 1px solid black;"> <strong>@php 
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
                            $finalTotal = 0;
                            $get_total = $get_total + $ned;
                            $finalTotal += $get_total;
                            @endphp
                            {{ $btnmks }}
                        </strong></td>
                        <td style="border: 1px solid black;">{{$performmark}}</td>
                        <td style="border: 1px solid black;">{{$gracemmark}}</td>
                        <td  style="border: 1px solid black;">@php
                            $tolgrac = $performmark+$gracemmark;
                            $perct = $tolgrac+$btnmks;
                            $percentage=$perct ? ($perct / 100) * 100 : 0;
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
                <td style="font-weight: bold;border: 1px solid black;" >Total Obtain Marks</td>
                <td style="border: 1px solid black;" colspan="{{ count($student_value['exam'])}}"></td>
                <td style="font-weight: bold;border: 1px solid black;">{{$mainobtainmarks}}</td>
                <td style="font-weight: bold;border: 1px solid black;">{{$maintotalobtn}}</td>
                <td style="font-weight: bold;border: 1px solid black;"></td>
                <td style="font-weight: bold;border: 1px solid black;"></td>
                <td style="font-weight: bold;border: 1px solid black;">@if($finalTotal < $nedadorno || $pasorfl == 0 ) Pass @else Fail @endif </td>
                <td style="font-weight: bold;border: 1px solid black;">@php $percentages =$maintotalobtn ? ($maintotalobtn / $hundradtotal) * 100 : 0; @endphp {{round($percentages,2)}}%</td>
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
