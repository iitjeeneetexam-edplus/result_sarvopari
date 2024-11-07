<!DOCTYPE html>
<html>
<head>
    <title>Deadstock report PDF</title>
    <style>
        body {
            margin: 0mm;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            word-wrap: break-word;
            table-layout: fixed; /* Ensure even distribution of content */
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            white-space: nowrap;
        }
        

    </style>
</head>
<body>

   
    <div class="content">
    <table>
    
    <thead>
        <tr class="header-row">
            <td colspan="4"><center><b>{{ $data['student']['school_name'] }}</b></center></td>
        </tr>
        <tr class="header-row" colspan="4">
            <td colspan="2">Exam: exam</td>
            <td colspan="2">Year: <b>year</b></td>
        </tr>
        <tr class="header-row">
            <td colspan="2">Standard: <b>{{ $data['student']['standard_name'] }} </b></td>
            <td colspan="2">school_index: <b>school_index</b></td>
        </tr>
        <tr class="header-row">
            <td colspan="4">Name <center><b>{{ $data['student']['name'] }}</b></center></td>
        </tr>
        <tr class="header-row">
            <td colspan="1">G.R</td>
            <td colspan="1"><b>{{ $data['student']['GR_no'] }}</b></td>
            <td colspan="1">Roll No.</td>
            <td colspan="1"><b>{{ $data['student']['roll_no'] }}</b></td>
        </tr>
        <tr>
            <th>Sr</th>
            <th style="width: 30%;">Subjects</th>
            <th>Total Marks</th>
            <th>Obtain Marks</th>
        </tr>
    </thead>
    <tbody>
        @php
        $totalMarksSum= 0;
        $stdmarks = 0;
        $subsubjtotalMarksSum= 0;
        $stdmarkssub = 0;
        @endphp
        @foreach($data['subjects'] as $no=>$subjectslist)
        <tr>
            <td>{{ ++$no }}</td>
            <td>{{ $subjectslist->subject_name }}</td>
            <td>{{ $subjectslist->total_marks }}</td>
            <td>{{ $subjectslist->marks ?? ' ' }}</td>            
        </tr>
        @php
        $totalMarksSum += $subjectslist->total_marks;
        $stdmarks += $subjectslist->marks;
        @endphp
        @endforeach

        @foreach($data['optional_subjects'] as $optinalsubjects)
             <tr>
                <td>{{ $no }}</td>
                <td>{{ $optinalsubjects->subject_name }}</td>
                <td>{{ $optinalsubjects->total_marks }}</td>
                <td>{{ $optinalsubjects->marks ?? ' ' }}</td>
             </tr>   
             @php
            $subsubjtotalMarksSum += $optinalsubjects->total_marks;
            $stdmarkssub += $optinalsubjects->marks;
            @endphp
        @endforeach
        @php
        $totalmarks_total = $subsubjtotalMarksSum + $totalMarksSum;
        $stdmark = $stdmarkssub + $stdmarks;
        @endphp
        <tr>
            <td colspan="2">Total</td>
            <td colspan="1">{{ $totalmarks_total }}</td>
            <td colspan="1">{{ $stdmark }}</td>
        </tr>
        <tr>
            <td colspan="2">Percentage : @php $percentage = 0;  $percentage = ($stdmark/$totalmarks_total)*100; @endphp {{ $percentage }}</td>
            <td colspan="2"> @php 
            if ($percentage >= 90) {
                $grade = 'A+';
            } elseif ($percentage >= 80) {
                $grade = 'A';
            } elseif ($percentage >= 70) {
                $grade = 'B+';
            } elseif ($percentage >= 60) {
                $grade = 'B';
            } elseif ($percentage >= 50) {
                $grade = 'C';
            } elseif ($percentage >= 40) {
                $grade = 'D';
            } else {
                $grade = 'F';
            }
            @endphp
            Grade : {{$grade}}
        </td>
        </tr>
        <tr>
            <td colspan="4">Result Date:</td>
        </tr>
        <tr>
            <td colspan="2">Teacher Signature</td>
            <td colspan="2">Principal Signature</td>
        </tr>
    </tbody>
</table>
<br>
</div>

</body>
</html>
