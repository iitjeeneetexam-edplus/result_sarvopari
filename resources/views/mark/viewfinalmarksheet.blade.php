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
                                <p style="margin: 0;margin-top:10px;"> <b>$student_value['medium']</b></p>
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
                    <tr style="height: 40pt; background-color: black; color: white; font-size: 12pt; font-weight: bold;">
                        <td style="width: 10%; border: 1pt solid black; text-align: center;height: 20pt; ">Subject Name</td>
                    </tr>
                    
                    @foreach($student_value['exam'] as $exam_array)
                      @foreach($exam_array['marks'] as $marks_array)
                      <tr style="height: 29pt;">
                         <td style="width: 10%; border: 1pt solid black; text-align: center; font-size: 12pt; height: 30pt;">
                            {{$marks_array['subject_name']}}
                        </td>
                        </tr>
                        @endforeach
                        @endforeach
                     
                   
                </table>

                <div style="width: 100%; margin-top: 15pt;">
                    <table style="width: 100%; font-size: 16pt; border-collapse: collapse;">
                        <tr>
                            <td style="text-align: left; padding: 8px;font-size: 14pt">
                                Percentage – <b></b>
                            </td>
                            <td style="text-align: center; padding: 10px;font-size: 14pt">
                                Result – 
                                <b></b>
                            </td>
                            <td style="text-align: right; padding: 10px;font-size: 14pt">
                                Rank – <b>  </b>
                            </td>

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
                        Date – <b></b>
                    </p>
                </div>

            </div>
            @endforeach
        </div>