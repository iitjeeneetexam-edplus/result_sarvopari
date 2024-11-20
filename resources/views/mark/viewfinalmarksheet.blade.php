@php
$studentPercentages = [];
$rankList = [];
$rank = 1;

$totalMarksSum = 0;
$stdmarks = 0;
$subsubjtotalMarksSum = 0;
$stdmarkssub = 0;
$passfail = 0;
@endphp
 
        <div id="pdfContent" style="font-family: system-ui, sans-serif;">
            @foreach($student as $student_value)
            @php
            $index_no = 1; 
            @endphp
            @php
            $studentRank = 1;
            @endphp
            <div style="width: 170mm; height: 260mm; padding: 10px; box-sizing: border-box; border: 3px solid black; border-radius: 4px; font-family: Calibri, sans-serif;">
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
                                Standard – <b>{{ ucfirst($student_value['standard_name']) }}-{{$student_value['division_name']}}</b>
                            </td>
                            <td style="width: 33%; text-align: right;">
                                Roll No – <b>{{ $student_value['roll_no'] }}</b>
                            </td>
                        </tr>
                    </table>
                </div>
                <p style="margin: 0;margin-top:10px;">UID - <b>{{ $student_value['uid'] }}</b> </p>
                <p style="font-size: 16pt; margin: 0; padding: 0px; margin-top: 20px;">Student Name – <b>{{ $student_value['student_name'] }}</b> </p>

                <table style="border-collapse: collapse; width: 100%; margin-top: 10pt;">
                <div style="text-align: center; margin-bottom: 20px;">
       
    </div>

    <!-- Main Table -->
    <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; text-align: center;">
        <thead>
            <tr>
                <th style="background-color: #f0f0f0;" rowspan="2">Subjects</th>
                @if(isset($student_value['exam']))
                @foreach($student_value['exam'] as $exam_value)
                <th style="background-color: #f0f0f0;" colspan="2">{{$exam_value['exam_name']}}</th>
                @endforeach
                @endif
                <th style="background-color: #f0f0f0;">Total Marks</th>
                <th style="background-color: #f0f0f0;">Optain Marks</th>
                <th style="background-color: #f0f0f0;">sidhi gun</th>
                <th style="background-color: #f0f0f0;">krupa gun</th>
                <th style="background-color: #f0f0f0;">Grade</th>
                <th style="background-color: #f0f0f0;">Percentage</th>

            </tr>
            <tr>
           
            </tr>
        </thead>
        <tbody>
        @if(isset($student_value['exam']))
        @foreach($student_value['exam'] as $exam_value)
          @if(isset($exam_value['subject_Data']))
           @foreach($exam_value['subject_Data'] as $subject_value)
            <tr>
                <td>{{ $subject_value['subject_name']}}</td>
                    @if(isset($subject_value['marks']))
                    @foreach($subject_value['marks'] as $mark_value)
                        <td >{{ $mark_value['marks']}}</td>
                    @endforeach
                    @endif
            </tr>
            @endforeach
            @endif
            @endforeach
            @endif

        
            
        </tbody>
        <tfoot>
            <tr>
                <td style="font-weight: bold;">કુલ ગુણ</td>
                <td colspan="6"></td>
                <td style="font-weight: bold;">353</td>
                <td style="font-weight: bold;">424</td>
                <td style="font-weight: bold;">A</td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer Section -->
    <div style="margin-top: 20px; text-align: left;">
        <p style="margin: 0;">Date: 04-05-2020</p>
        <p style="margin: 0;">Signature:</p>
    </div>
            @endforeach
        </div>