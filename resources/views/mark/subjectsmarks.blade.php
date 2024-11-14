<div class="row">
<h2>School : {{$school_name}}</h2>
<h2>Standard : {{$standard_name}}</h2>
</div>
<div class="row">
<h2>Division : {{$division_name}}</h2>
<h2>Subject Name : {{$subject_name}}</h2>
</div>
<div class="row">
<h2>Total Marks : {{$total_marks}}</h2>
<h2>Passing Marks : {{$passing_marks}}</h2>
</div>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2; color: #333;">
            <th style="border: 1px solid #ddd; padding: 8px;">Roll No</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Name</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Marks</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $student['roll_no'] }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $student['name'] }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $student['marks'] }}</td>
                
            </tr>
        @endforeach
    </tbody>
</table>
