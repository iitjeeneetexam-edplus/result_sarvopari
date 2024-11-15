 
<div id="pdfContent" style="font-family: system-ui, sans-serif;">
@foreach($data['student'] as $student_value)
    <div style="width: 170mm; height: 260mm; padding: 10px; box-sizing: border-box; border: 3px solid black; border-radius: 4px; font-family: Calibri, sans-serif;">
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
                        <p style="margin: 0;">Index No - <b>{{ ucfirst($student_value['school_index']) }}</b></p>
                        <p style="margin: 0;margin-top:10px;">Exam - <b>{{ ucfirst($student_value['exam_name']) }}</b> </p>
                    </td>
                    <td style="width: 34%; text-align: center; padding: 5px; background-color: black; color: white; font-size: 24pt; font-weight: bold; border-radius: 4px;">
                        Result Sheet
                    </td>
                    <td style="width: 33%; text-align: right; font-size: 14pt; vertical-align: top;">
                        <p style="margin: 0;">Year – <b>{{ $student_value['exam_year'] }}</b></p>
                        <p style="margin: 0;"> <b>{{ $student_value['medium'] }}</b></p>
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
<p style="font-size: 16pt; margin: 0; padding: 0px; margin-top: 20px;">Student Name – <b>{{ $student_value['name'] }}</b> </p>

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
        <td style="width: 22%; border: 1pt solid black; text-align: center; font-size: 12pt;height: 30pt;">{{ 
                                                                                                                  $subjectslist['marks']
        }}</td>

        @php 
        if($subjectslist['marks'] < $subjectslist['passing_marks']){
            $passfail += $passfail+1;
        }
        $smbper = $subjectslist['total_marks'] ? ($subjectslist['marks'] / $subjectslist['total_marks']) * 100 : 0;
        $grade = match (true) {
                    $smbper >= 91 => 'A1',
                    $smbper >= 81 => 'A2',
                    $smbper >= 71 => 'B1',
                    $smbper >= 61 => 'B2',
                    $smbper >= 51 => 'C1',
                    $smbper >= 41 => 'C2',
                    $smbper >= 33 => 'D',
                    $smbper >= 21 => 'E1',
                    $smbper <= 20 => 'E2',
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

                $subper = $value_optional['total_marks'] ? ($value_optional['marks'] / $value_optional['total_marks']) * 100 : 0;
                $grade = match (true) {
                    $subper >= 91 => 'A1',
                    $subper >= 81 => 'A2',
                    $subper >= 71 => 'B1',
                    $subper >= 61 => 'B2',
                    $subper >= 51 => 'C1',
                    $subper >= 41 => 'C2',
                    $subper >= 33 => 'D',
                    $subper >= 21 => 'E1',
                    $subper <= 20 => 'E2',
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
                    Percentage – <b>{{ round($percentage, 2) }}%</b>
                </td>
                <td style="text-align: center; padding: 10px;font-size: 14pt">
                    Result – @php
                        if($passfail >= 1) {
                            $porf = 'Fail';
                        }else{
                            $porf = 'Pass';
                        }   
                    @endphp 
                    <b>{{$porf}}</b>
                </td>
                <td style="text-align: right; padding: 10px;font-size: 14pt">Rank - <b>1</b></td>
            </tr>
        </table>
</div>


        
<div style="width: 100%; margin-top: 60px;">
    <table style="width: 100%; font-size: 16pt; border-collapse: collapse;">
        <tr>
            <td style="text-align: left; padding: 5px;font-size: 14pt">
                Class Teacher Sign
            </td>
            <td style="text-align: right; padding: 5px;font-size: 14pt">
                Principal Sign
            </td>
        </tr>

    </table> 
    <p style="position: absolute; margin-top: 2px; left: 20px; font-size: 12pt;">
        Date – <b>{{ date('d-m-20y',strtotime($student_value['result_date'])) }}</b>
    </p>
      
       
</div>

    </div>
    @endforeach
</div>
 