<!DOCTYPE html>
<html>
<head>
    <title>Marksheet Report</title>
   
</head>
<body>
 
<div id="pdfContent">
    <div style="width: 210mm; height: 297mm; padding: 20px; box-sizing: border-box; border: 3px solid black; border-radius: 4px; font-family: Calibri, sans-serif;">
        <div style="width: 100%; margin-bottom: 10pt;">
            <div style="border-radius: 4px; border: 2pt solid black; padding: 15pt; height: 67pt;">
                <h1 style="text-align: center; font-size: 40pt; font-weight: bold; margin: 0; padding: 0;">{{ $data['student']['school_name'] }}</h1>  
                <br/>
                <p style="text-align: center; font-size: 14pt; padding: 0; margin: 0;">{{ $data['student']['address'] }}</p>
            </div>
        </div>

        <div style="width: 100%; margin-bottom: 10pt;">
            <div style="display: flex; height: 64pt; border-bottom: 1pt solid black; justify-content: space-between; align-items: center; font-size: 24pt;">
                <div style="font-size: 14pt;">
                    <p>Index No. <b>{{ $data['student']['school_index'] ?? '' }}</b></p>
                    <p>{{ $data['student']['exam_name'] }}</p>
                </div>
                <div style="border-radius: 4px; padding: 10px; text-align: center; background-color: black; color: white; font-size: 24pt; font-weight: bold; display: flex; align-items: center; justify-content: center;">
                    Result Sheet
                </div>
                <div style="font-size: 16pt;">
                    <p>Year – <b>{{ $data['student']['exam_year'] }}</b></p>
                    <p>Gujarati Medium</p>
                </div>
            </div>
        </div>

        <div style="width: 100%; margin-bottom: 10pt;">
            <div style="display: flex; height: 23pt; align-items: center; justify-content: space-between;">
                <div style="font-size: 14pt;">
                    <p>G R No - <b>{{ $data['student']['GR_no'] }}</b></p>
                </div>
                <div style="text-align: center; font-size: 18pt; font-weight: bold;">
                    {{ $data['student']['standard_name'] }}
                </div>
                <div style="font-size: 14pt;">
                    <p>Roll No – <b>{{ $data['student']['roll_no'] }}</b></p>
                </div>
            </div>
        </div>

        <p style="font-size: 16pt; margin: 0; padding: 0px; margin-top: 60px;">Student Name – <b>{{ $data['student']['name'] }}</b></p>

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
        <tr style="height: 29pt;">
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ ++$no }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $subjectslist->subject_name }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $subjectslist->total_marks }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $subjectslist->marks ?? ' ' }}</td>
            @php 
                if($subjectslist->marks < $subjectslist->passing_marks){
                    $passfail += $passfail+1;
                }
                $grade = match (true) {
                    $subjectslist->marks >= 90 => 'A+',
                    $subjectslist->marks >= 80 => 'A',
                    $subjectslist->marks >= 70 => 'B+',
                    $subjectslist->marks >= 60 => 'B',
                    $subjectslist->marks >= 50 => 'C',
                    $subjectslist->marks >= 40 => 'D',
                    default => 'F'
                };
                @endphp
                <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $grade }}</td>

            @php
            $totalMarksSum += $subjectslist->total_marks;
            $stdmarks += $subjectslist->marks;
            @endphp
        </tr>
        @endforeach
        @php
        $sno = $no;
        @endphp
        @foreach($data['optional_subjects'] as $optionalSubject)
        <tr style="height: 29pt;">
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ ++$sno }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $optionalSubject->subject_name }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $optionalSubject->total_marks }}</td>
            <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt;">{{ $optionalSubject->marks ?? ' ' }}</td>
            @php
            $subsubjtotalMarksSum += $optionalSubject->total_marks;
            $stdmarkssub += $optionalSubject->marks;
            @endphp
        </tr>
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

        <div style="padding: 10px; display: flex; align-items: center; justify-content: space-between; font-size: 16pt; margin-top: 15pt;">
            <span>Percentage – <b>{{ $percentage }}</b></span>
            <span>Result – <b>@php
                if($passfail >= 1) {
                    $porf = 'Fail';
                }else{
                    $porf = 'Pass';
                }   
            @endphp {{$porf}}</b></span>
        </div>

        <p style="padding-left: 10px; font-size: 16pt;">Date – <b>{{ $data['student']['result_date'] }}</b></p>

        <div style="padding: 10px; margin-top: 120px; font-size: 16pt; display: flex; align-items: center; justify-content: space-between;">
            <span>Class Teacher Sign</span><span>Principal Sign</span>
        </div>
    </div>
</div>
 
</body>
</html>
