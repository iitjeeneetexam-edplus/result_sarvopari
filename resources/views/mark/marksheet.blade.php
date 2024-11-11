<!DOCTYPE html>
<html>
<head>
    <title>Marksheet Report</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .sub-header {
            text-align: center;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .table-container {
            width: 100%;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            word-wrap: break-word;
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
        td {
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }
        .center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .signature {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .signature div {
            width: 45%;
            text-align: center;
            border-top: 1px dotted #000;
            padding-top: 5px;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .teacher-signature,
        .principal-signature {
            flex: 1;
            text-align: center;
          }

    </style>
</head>
<body>

<div class="header">
    <h1>{{ $data['student']['school_name'] }}</h1>
    <p>Address : {{ $data['student']['address'] }}</p>
</div>

<div class="sub-header">
    <div>Exam: <b>{{ $data['student']['exam_name'] }}</b> | Year: <b>{{ $data['student']['exam_year'] }}</b></div>
    <div>Standard: <b>{{ $data['student']['standard_name'] }}</b> | School Index: <b>{{ $data['student']['school_index'] ?? '' }}</b></div>
    <h2>{{ $data['student']['name'] }}</h2>
</div>

<div class="table-container">
    <table>
        <tr>
            <td colspan="2">G.R No: <b>{{ $data['student']['GR_no'] }}</b></td>
            <td colspan="2">Roll No: <b>{{ $data['student']['roll_no'] }}</b></td>
        </tr>
        <tr>
            <th>Sr</th>
            <th>Subject</th>
            <th>Total Marks</th>
            <th>Obtained Marks</th>
        </tr>
        @php
        $totalMarksSum = 0;
        $stdmarks = 0;
        $subsubjtotalMarksSum = 0;
        $stdmarkssub = 0;
        @endphp
        @foreach($data['subjects'] as $no => $subjectslist)
        <tr>
            <td class="center">{{ ++$no }}</td>
            <td>{{ $subjectslist->subject_name }}</td>
            <td class="center">{{ $subjectslist->total_marks }}</td>
            <td class="center">{{ $subjectslist->marks ?? ' ' }}</td>
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
        <tr>
            <td class="center">{{ ++$sno }}</td>
            <td>{{ $optionalSubject->subject_name }}</td>
            <td class="center">{{ $optionalSubject->total_marks }}</td>
            <td class="center">{{ $optionalSubject->marks ?? ' ' }}</td>
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
        <tr class="bold">
            <td colspan="2">Total</td>
            <td class="center">{{ $totalmarks_total }}</td>
            <td class="center">{{ $stdmark }}</td>
        </tr>
        <tr class="bold">
            <td colspan="2">Percentage: {{ number_format($percentage, 2) }}%</td>
            <td colspan="2">
                Grade: 
                @php 
                $grade = match (true) {
                    $percentage >= 90 => 'A+',
                    $percentage >= 80 => 'A',
                    $percentage >= 70 => 'B+',
                    $percentage >= 60 => 'B',
                    $percentage >= 50 => 'C',
                    $percentage >= 40 => 'D',
                    default => 'F'
                };
                @endphp
                {{ $grade }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="center">Result Date: {{ $data['student']['result_date'] }}</td>
        </tr>
    </table>

    <div class="signature">
    <div class="teacher-signature">Teacher Signature</div>
    <div class="principal-signature">Principal Signature</div>
    </div>
</div>



</body>
</html>
