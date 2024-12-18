@php
$studentPercentages = [];
$rankList = [];
$rank = 1;

foreach ($data['student'] as $student) {
$totalMarksSum = 0;
$stdmarks = 0;
$subsubjtotalMarksSum = 0;
$stdmarkssub = 0;
$passfail = 0;

foreach ($data['subjects'] as $subject) {
if ($subject['student_id'] == $student['id']) {
if (!isset($subject['marks']) || $subject['marks'] == 'AB') {
$mrkses = 0;
}else{
$mrkses = $subject['marks'];
}
$totalMarksSum += $subject['total_marks'];
$stdmarks += $mrkses;
if ($mrkses < $subject['passing_marks']) {
    $passfail=1;
    }
    }
    }

    foreach ($data['optional_subjects'] as $optionalSubject) {
    if ($optionalSubject['student_id']==$student['id']) {
    if (!isset($optionalSubject['marks']) || $optionalSubject['marks']=='AB' ) {
    $opmrkses=0;
    }else{
    $opmrkses=$optionalSubject['marks'];
    }
    $subsubjtotalMarksSum +=$optionalSubject['total_marks'];
    $stdmarkssub +=$opmrkses;
    if ($opmrkses < $optionalSubject['passing_marks']) {
    $passfail=1;
    }
    }
    }

    $totalmarks_total=$subsubjtotalMarksSum + $totalMarksSum;
    $stdmark=$stdmarkssub + $stdmarks;
    $percentage=$totalmarks_total ? ($stdmark / $totalmarks_total) * 100 : 0;

    if ($passfail==0) {
    $studentPercentages[]=['id'=> $student['id'], 'percentage' => $percentage];
    }
    }

    usort($studentPercentages, function ($a, $b) {
    return $b['percentage'] <=> $a['percentage'];
        });

        foreach ($studentPercentages as $studentData) {
        $rankList[$studentData['id']] = $rank++;
        }
        @endphp

        <!-- start -->
            @foreach($data['student'] as $student_value)
            <body class="noto-sans-gujarati">
            <div style="box-sizing: border-box;"> 

            @php
            $index_no = 1; // Reset index_no for each student
            @endphp
            @php
            $studentRank = $rankList[$student_value['id']] ?? 'N/A';
            @endphp
            <div style="width: auto; height: 270mm;margin-left: 30px;margin-right: 30px;margin-right: 30px; padding: 20px; border: 3px solid black; border-radius: 4px; font-family: Calibri, sans-serif;margin-top:30px">
            <div style="width: 100%; margin-bottom: 10pt;">
                <div style="border-radius: 4px; border: 2pt solid black; padding: 15pt; height: 75pt;">
                        <h1 style="text-align: center; font-size: 30pt; font-weight: bold; margin: 0; padding: 0;">{{ ucfirst($student_value['school_name']) }}
                        </h1>
                        <br />
                        <p style="text-align: center; font-size: 14pt; padding: 0; margin-top:-20px;">{{ $student_value['address'] }}</p>
                    </div>
                </div>
                <br>
                <div style="width: 100%; margin-bottom: 10pt;">
                    <table style="width: 100%; border-bottom: 1pt solid black; font-size: 24pt;">
                        <tr>
                            <td style="width: 33%; font-size: 14pt; vertical-align: top;">
                                <p style="margin: 0;" >{{(!empty($student_value['school_index'])?'Index No -':'') }} <b>{{ ucfirst($student_value['school_index']) }}</b></p>
                                <p style="margin: 0;margin-top:10px;">પરીક્ષા - <b>{{ ucfirst($student_value['exam_name']) }}</b> </p>
                            </td>
                            <td style="width: 34%; text-align: center; padding: 5px; background-color: black; color: white; font-size: 24pt; font-weight: bold; border-radius: 4px;">
                                પરિણામ પત્રક
                            </td>
                            <td style="width: 33%; text-align: right; font-size: 14pt; vertical-align: top;">
                                <p style="margin: 0;">વર્ષ – <b>{{ $student_value['exam_year'] }}</b></p>
                                <p style="margin: 0;margin-top:10px;"> <b>{{ $student_value['medium'] }}</b></p>
                            </td>

                        </tr>
                        <br>
                    </table>
                </div>


                <div style="width: 100%; margin-top: 20px;">
                    <table style="width: 100%; font-size: 14pt; border-collapse: collapse;">
                        <tr>
                            <td style="width: 33%; text-align: left;">
                                જી.આર.નંબર - <b>{{ $student_value['GR_no'] }}</b>
                            </td>
                            <td style="width: 35%; text-align: center; font-size: 16pt; ">
                                ધોરણ – <b>{{ ucfirst($student_value['standard_name']) }}-{{$student_value['division_name']}}</b>
                            </td>
                            <td style="width: 35%; text-align: right;">
                                રોલ નંબર – <b>{{ $student_value['roll_no'] }}</b>
                            </td>
                        </tr>
                    </table>
                </div>
                <p style="margin: 0;margin-top:10px;">UID - <b>{{ $student_value['uid'] }}</b> </p>
                <p style="font-size: 16pt; margin: 0; padding: 0px; margin-top: 20px;">વિદ્યાર્થીનું નામ – <b>{{ $student_value['name'] }}</b> </p>

                <table style="border-collapse: collapse; width: 100%; margin-top: 10pt;">
                    <tr style="height: 40pt; background-color: black; color: white; font-size: 12pt; font-weight: bold;">
                        <!-- <td style="width: 10%; border: 1pt solid black; text-align: center;height: 20pt; ">No</td> -->
                        <td style="width: 35%; border: 1pt solid black; text-align: center;height: 20pt;">વિષય</td>
                        <td style="width: 25%; border: 1pt solid black; text-align: center;height: 20pt;">કુલ ગુણ</td>
                        <td style="width: 25%; border: 1pt solid black; text-align: center;height: 20pt;">મેળવેલ ગુણ</td>
                        <td style="width: 15%; border: 1pt solid black; text-align: center;height: 20pt;">ગ્રેડ</td>
                    </tr>

                    @php
                    $totalMarksSum = 0;
                    $stdmarks = 0;
                    $subsubjtotalMarksSum = 0;
                    $stdmarkssub = 0;
                    $passfail = 0;
                    @endphp
                    @php $index_no=1 @endphp
                    @php
                    $allSubjects = array_merge($data['subjects'], $data['optional_subjects']);
                    @endphp

                    @foreach($allSubjects as $no => $subject)
                    @if(!empty($subject['student_id']) && $subject['student_id'] == $student_value['id'])

                    <tr style="height: 29pt;">
                        <!-- <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt; height: 30pt;">
                            {{ $index_no }}
                        </td> -->
                        <td style="width: 35%; border: 1pt solid black; text-align: center; font-size: 12pt; height: 30pt;">
                            {{ ucfirst($subject['subject_name']) }}
                        </td>
                        <td style="width: 25%; border: 1pt solid black; text-align: center; font-size: 12pt; height: 30pt;">
                            {{ $subject['total_marks'] }}
                        </td>
                        <td style="width: 25%; border: 1pt solid black; text-align: center; font-size: 12pt; height: 30pt;">
                            {{ $subject['marks']  }}
                        </td>
                        @php
                        if($subject['marks'] == 'AB'){
                        $mrkses = 0;
                        }else{
                        $mrkses = $subject['marks'];
                        }

                        if ($mrkses < $subject['passing_marks']) {
                            $passfail +=1;
                            }

                            $percentage=$subject['total_marks'] ? ($mrkses / $subject['total_marks']) * 100 : 0;
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
                                @php
                                $subsubjtotalMarksSum += $subject['total_marks'];
                                $stdmarkssub += $mrkses;
                                @endphp
                                <td style="width: 15%; border: 1pt solid black; text-align: center; font-size: 12pt; height: 30pt;">
                                    {{ $grade }}
                                </td>
                    </tr>
                    @php $index_no++; @endphp
                    @endif
                    @endforeach
                    @php
                    $totalmarks_total = $subsubjtotalMarksSum + $totalMarksSum;
                    $stdmark = $stdmarkssub + $stdmarks;
                    $percentage = $totalmarks_total ? ($stdmark / $totalmarks_total) * 100 : 0;

                    @endphp
                    <!-- Additional rows can be added here -->
                    <tr style="height: 30pt; color: white; font-size: 12pt; font-weight: bold;">
                        <td  style="background-color: black; border: 1pt solid black; text-align: center;height: 20pt;">કુલ મેળવેલ ગુણ</td>
                        <td style="background-color: black; border: 1pt solid black; text-align: center;height: 20pt;"></td>
                        <td style="background-color: black; border: 1pt solid black; text-align: center;height: 20pt;">{{ $stdmark }}</td>
                        <td style="border: 1pt solid black; background-color: black;height: 20pt;"></td>
                    </tr>
                </table>

                <div style="width: 100%; margin-top: 15pt;">
                    <table style="width: 100%; font-size: 16pt; border-collapse: collapse;">
                        <tr>
                            <td style="text-align: left; padding: 8px;font-size: 14pt">
                                ટકા – <b>{{ round($percentage, 2) }}%</b>
                            </td>
                            <td style="text-align: center; padding: 10px;font-size: 14pt">
                                પરિણામ – @php
                                if($passfail >= 1) {
                                $porf = 'Fail';
                                }else{
                                $porf = 'Pass';
                                }
                                @endphp
                                <b>{{$porf}}</b>
                            </td>
                            <td style="text-align: right; padding: 10px;font-size: 14pt">
                                ક્રમાંક – <b> {{ $student_value['rank'] }} </b>
                            </td>

                        </tr>
                    </table>
                </div>



                <div style="width: 100%; margin-top: 60px;">
                    <table style="width: 100%; font-size: 16pt; border-collapse: collapse;">
                        <tr>
                            <td style="text-align: left;">
                                શિક્ષકની સહી:
                            </td>
                            <td style="text-align: right;">
                                પ્રિન્સિપાલની સહી:
                            </td>
                        </tr>
                    </table>
                    <p style="position: absolute; margin-top:10px; font-size: 12pt;">
                        તારીખ:  <b>{{ date('d-m-20y',strtotime($student_value['result_date'])) }}</b>
                    </p>
                </div>
            </div>
            @if(!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif
@endforeach
</div>
</body>