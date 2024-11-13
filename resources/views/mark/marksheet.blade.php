 
<div id="pdfContent" style="font-family: system-ui, sans-serif;">
@foreach($data['student'] as $student_value)
    <div style="width: 170mm; height: 260mm; padding: 20px; box-sizing: border-box; border: 3px solid black; border-radius: 4px; font-family: Calibri, sans-serif;">
        <div style="width: 100%; margin-bottom: 10pt;">
            <div style="border-radius: 4px; border: 2pt solid black; padding: 15pt; height: 67pt;">
                <h1 style="text-align: center; font-size: 40pt; font-weight: bold; margin: 0; padding: 0;">{{ ucfirst($student_value['school_name']) }}
                </h1>  
                <br/>
                <p style="text-align: center; font-size: 14pt; padding: 0; margin: 0;">{{ $student_value['address'] }}</p>
            </div>
        </div>
<br>
        <div style="width: 100%; margin-bottom: 10pt;">
            <table style="width: 100%; border-bottom: 1pt solid black; font-size: 24pt;">
                <tr>
                    <td style="width: 33%; font-size: 14pt; vertical-align: top;">
                        <p style="margin: 0;">Index No - {{ ucfirst($student_value['school_index']) }}</p>
                        <p style="margin: 0;margin-top:10px;">Exam - {{ ucfirst($student_value['exam_name']) }} </p>
                    </td>
                    <td style="width: 34%; text-align: center; padding: 5px; background-color: black; color: white; font-size: 24pt; font-weight: bold; border-radius: 4px;">
                        Result Sheet
                    </td>
                    <td style="width: 33%; text-align: right; font-size: 14pt; vertical-align: top;">
                        <p style="margin: 0;">Year – {{ $student_value['exam_year'] }}</p>
                    </td>
                </tr>
                <br>
            </table>
        </div>


        <div style="width: 100%; margin-top: 10px;">
    <table style="width: 100%; font-size: 14pt; border-collapse: collapse;">
        <tr>
            <td style="width: 33%; text-align: left;">
                G R No - {{ $student_value['GR_no'] }}
            </td>
            <td style="width: 80%; text-align: center; font-size: 16pt; ">
                Standard – {{ ucfirst($student_value['standard_name']) }}
            </td>
            <td style="width: 33%; text-align: right;">
                Roll No – {{ $student_value['roll_no'] }}
            </td>
        </tr>
    </table>
</div>
<p style="margin: 0;margin-top:10px;">UID - {{ $student_value['uid'] }} </p>
<p style="font-size: 16pt; margin: 0; padding: 0px; margin-top: 40px;">Student Name – {{ $student_value['name'] }} </p>

<table style="border-collapse: collapse; width: 100%; margin-top: 10pt;">
    <tr style="height: 40pt; background-color: black; color: white; font-size: 12pt; font-weight: bold;">
        <td style="width: 10%; border: 1pt solid black; text-align: center;height: 20pt; ">No</td>
        <td style="width: 35%; border: 1pt solid black; text-align: center;height: 20pt;">Subject</td>
        <td style="width: 22%; border: 1pt solid black; text-align: center;height: 20pt;">Total Marks</td>
        <td style="width: 22%; border: 1pt solid black; text-align: center;height: 20pt;">Obtain Marks</td>
        <td style="width: 15%; border: 1pt solid black; text-align: center;height: 20pt;">Grade</td>
    </tr>
    @php
$totalMarksSum = 0;
$stdmarks = 0;
$subsubjtotalMarksSum = 0;
$stdmarkssub = 0;
$passfail = 0;
@endphp
@foreach($data['subjects'] as $no => $subjectslist)
   @if(!empty($subjectslist['student_id']))
    @if($subjectslist['student_id'] == $student_value['id'])
    <tr style="height: 29pt;">
        <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ ++$no }}</td>
        <td style="width: 35%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ ucfirst($subjectslist['subject_name']) }}</td>
        <td style="width: 22%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ $subjectslist['total_marks'] }}</td> 
        <td style="width: 22%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ isset($subjectslist['marks']) ? ceil($subjectslist['marks']) : ' ' }}</td>

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
        <td style="width: 15%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ $grade }}</td>
        @php
    $totalMarksSum += $subjectslist['total_marks'];
    $stdmarks += $subjectslist['marks'];
    @endphp
    </tr>
    @endif
    @endif
    @endforeach
    @foreach($data['optional_subjects'] as $no2=> $value_optional)
    @if(!empty($value_optional['student_id']))
        @if($value_optional['student_id'] == $student_value['id'])
        <tr style="height: 29pt;">
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ ++$no2 }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ ucfirst($value_optional['subject_name']) }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ $value_optional['total_marks'] }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ isset($value_optional['marks']) ? $value_optional['marks']:''  }}</td>
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
                <td style="width: 15%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ $grade }}</td>
            @php
            $subsubjtotalMarksSum += $value_optional['total_marks'];
            $stdmarkssub += $value_optional['marks'];
            @endphp
            
        </tr>
        @endif
        @endif
        @endforeach
        @php
        $totalmarks_total = $subsubjtotalMarksSum + $totalMarksSum;
        $stdmark = $stdmarkssub + $stdmarks;
        $percentage = $totalmarks_total ? ($stdmark / $totalmarks_total) * 100 : 0;
        @endphp
            <!-- Additional rows can be added here -->
            <tr style="height: 30pt; color: white; font-size: 12pt; font-weight: bold;">
                <td colspan="3" style="background-color: black; border: 1pt solid black; text-align: center;height: 20pt;" >Total Obtain Marks</td>
                <td style="background-color: black; border: 1pt solid black; text-align: center;height: 20pt;">{{ $stdmark }}</td>
                <td style="border: 1pt solid black; background-color: black;height: 20pt;"></td>

            </tr>
        </table>

        <div style="width: 100%; margin-top: 15pt;">
        <table style="width: 100%; font-size: 16pt; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; padding: 10px;font-size: 14pt">
                    Percentage – {{ $percentage }}
                </td>
                <td style="text-align: right; padding: 10px;font-size: 14pt">
                    Result – @php
                        if($passfail >= 1) {
                            $porf = 'Fail';
                        }else{
                            $porf = 'Pass';
                        }   
                    @endphp {{$porf}}
                </td>
            </tr>
        </table>
</div>


        
        <div style="width: 100%; margin-top: 120px;">
    <table style="width: 100%; font-size: 16pt; border-collapse: collapse;">
        <tr>
            <td style="text-align: left; padding: 10px;font-size: 14pt">
                Class Teacher Sign
            </td>
            <td style="text-align: right; padding: 10px;font-size: 14pt">
                Principal Sign
            </td>
        </tr>

    </table> 
    <p style="position: absolute; bottom: 20pt; left: 30px; font-size: 16pt;">
        Date – {{ date('d-m-20y',strtotime($student_value['result_date'])) }}
    </p>
      
       
</div>

    </div>
    @endforeach
</div>
 