 
<div id="pdfContent">
@foreach($data['student'] as $student_value)
    <div style="width: 170mm; height: 260mm; padding: 20px; box-sizing: border-box; border: 3px solid black; border-radius: 4px; font-family: Calibri, sans-serif;">
        <div style="width: 100%; margin-bottom: 10pt;">
            <div style="border-radius: 4px; border: 2pt solid black; padding: 15pt; height: 67pt;">
                <h1 style="text-align: center; font-size: 40pt; font-weight: bold; margin: 0; padding: 0;">{{ $student_value['school_name'] }}</h1>  
                <br/>
                <p style="text-align: center; font-size: 14pt; padding: 0; margin: 0;">{{ $student_value['address'] }}</p>
            </div>
        </div>
<br>
        <div style="width: 100%; margin-bottom: 10pt;">
            <table style="width: 100%; border-bottom: 1pt solid black; font-size: 24pt;">
                <tr>
                    <td style="width: 33%; font-size: 14pt; vertical-align: top;">
                        <p style="margin: 0;">Index No. <b>{{ $student_value['school_index'] }}</b></p>
                        <p style="margin: 0;margin-top:10px;">Exam :<b>{{ $student_value['exam_name'] }}</b> </p>
                    </td>
                    <td style="width: 34%; text-align: center; padding: 5px; background-color: black; color: white; font-size: 24pt; font-weight: bold; border-radius: 4px;">
                        Result Sheet
                    </td>
                    <td style="width: 33%; text-align: right; font-size: 16pt; vertical-align: top;">
                        <p style="margin: 0;">Year – <b>{{ $student_value['exam_year'] }}</b></p>
                        <p style="margin: 0;margin-top:10px;">UID :<b>{{ $student_value['uid'] }}</b> </p>
                    </td>
                </tr>
                <br>
            </table>
        </div>


        <div style="width: 100%; margin-top: 10px;">
    <table style="width: 100%; font-size: 14pt; border-collapse: collapse;">
        <tr>
            <td style="width: 33%; text-align: left;">
                G R No - <b>{{ $student_value['GR_no'] }}</b>
            </td>
            <td style="width: 34%; text-align: center; font-size: 18pt; font-weight: bold;">
                Std – <b>{{ $student_value['standard_name'] }}</b>
            </td>
            <td style="width: 33%; text-align: right;">
                Roll No – <b>{{ $student_value['roll_no'] }}</b>
            </td>
        </tr>
    </table>
</div>


        <p style="font-size: 16pt; margin: 0; padding: 0px; margin-top: 80px;">Student Name – {{ $student_value['name'] }} <b></b></p>

        <table style="border-collapse: collapse; width: 100%; margin-top: 10pt;">
            <tr style="height: 31pt; background-color: black; color: white; font-size: 12pt; font-weight: bold;">
                <td style="width: 10%; border: 1pt solid black; text-align: center;">No</td>
                <td style="width: 35%; border: 1pt solid black; text-align: center;">Subject</td>
                <td style="width: 22%; border: 1pt solid black; text-align: center;">Total Marks</td>
                <td style="width: 22%; border: 1pt solid black; text-align: center;">Obtain Marks</td>
                <td style="width: 15%; border: 1pt solid black; text-align: center;">Grade</td>
            </tr>
            @php
        $totalMarksSum = 0;
        $stdmarks = 0;
        $subsubjtotalMarksSum = 0;
        $stdmarkssub = 0;
        $passfail = 0;
        @endphp
        @foreach($data['subjects'] as $no => $subjectslist)
            @if($subjectslist['student_id'] == $student_value['id'])
            <tr style="height: 29pt;">
                <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ ++$no }}</td>
                <td style="width: 35%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $subjectslist['subject_name'] }}</td>
                <td style="width: 22%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $subjectslist['total_marks'] }}</td>
                <td style="width: 22%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $subjectslist['marks'] ?? ' ' }}</td>

                @php 
                if($subjectslist['marks'] < $subjectslist['passing_marks']){
                    $passfail += $passfail+1;
                }
                $grade = match (true) {
                    $subjectslist['marks'] >= 90 => 'A+',
                    $subjectslist['marks'] >= 80 => 'A',
                    $subjectslist['marks'] >= 70 => 'B+',
                    $subjectslist['marks'] >= 60 => 'B',
                    $subjectslist['marks'] >= 50 => 'C',
                    $subjectslist['marks'] >= 40 => 'D',
                    default => 'F'
                };
                @endphp
                <td style="width: 15%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $grade }}</td>
                @php
            $totalMarksSum += $subjectslist['total_marks'];
            $stdmarks += $subjectslist['marks'];
            @endphp
            </tr>
            @endif
            @endforeach
         
        @foreach($data['optional_subjects'] as $no2=> $value_optional)
        @if($value_optional['student_id'] == $student_value['id'])
        <tr style="height: 29pt;">
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ ++$no2 }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $value_optional['subject_name'] }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $value_optional['total_marks'] }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $value_optional['marks'] ?? ' ' }}</td>
            @php 
                if($value_optional['marks'] < $value_optional['passing_marks']){
                    $passfail += $passfail+1;
                }
                $grade = match (true) {
                    $value_optional['marks'] >= 90 => 'A+',
                    $value_optional['marks'] >= 80 => 'A',
                    $value_optional['marks'] >= 70 => 'B+',
                    $value_optional['marks'] >= 60 => 'B',
                    $value_optional['marks'] >= 50 => 'C',
                    $value_optional['marks'] >= 40 => 'D',
                    default => 'F'
                };
                @endphp
                <td style="width: 15%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $grade }}</td>
            @php
            $subsubjtotalMarksSum += $value_optional['total_marks'];
            $stdmarkssub += $value_optional['marks'];
            @endphp
            
        </tr>
        @endif
        @endforeach
        @php
        $totalmarks_total = $subsubjtotalMarksSum + $totalMarksSum;
        $stdmark = $stdmarkssub + $stdmarks;
        $percentage = $totalmarks_total ? ($stdmark / $totalmarks_total) * 100 : 0;
        @endphp
            <!-- Additional rows can be added here -->
            <tr style="height: 30pt; color: white; font-size: 12pt; font-weight: bold;">
                <td colspan="3" style="background-color: black; border: 1pt solid black; text-align: center;">Total Obtain Marks</td>
                <td style="background-color: black; border: 1pt solid black; text-align: center;">{{ $stdmark }}</td>
                <td style="border: 1pt solid black; background-color: black;"></td>

            </tr>
        </table>

        <div style="width: 100%; margin-top: 15pt;">
        <table style="width: 100%; font-size: 16pt; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; padding: 10px;">
                    Percentage – <b>{{ $percentage }}</b>
                </td>
                <td style="text-align: right; padding: 10px;">
                    Result – <b>@php
                        if($passfail >= 1) {
                            $porf = 'Fail';
                        }else{
                            $porf = 'Pass';
                        }   
                    @endphp {{$porf}}</b>
                </td>
            </tr>
        </table>
</div>


        
        <div style="width: 100%; margin-top: 120px;">
    <table style="width: 100%; font-size: 16pt; border-collapse: collapse;">
        <tr>
            <td style="text-align: left; padding: 10px;">
                Class Teacher Sign
            </td>
            <td style="text-align: right; padding: 10px;">
                Principal Sign
            </td>
        </tr>

    </table>
    <p style="padding-left: 10px; font-size: 16pt;">Date – <b>{{ $student_value['result_date'] }}</b></p>



           
       
</div>

    </div>
    @endforeach
</div>
 