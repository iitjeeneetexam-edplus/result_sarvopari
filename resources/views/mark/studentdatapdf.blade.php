<h2>Student Data</h2>
<div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2; color: #333;">
                <th style="border: 1px solid #ddd; padding: 8px;">Roll No</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Name</th>
                <th style="border: 1px solid #ddd; padding: 8px;">GR No</th>
                @foreach($subjects as $subject)
                    <th style="border: 1px solid #ddd; padding: 8px;">{{ $subject }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $student['roll_no'] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $student['name'] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $student['GR_no'] }}</td>
                    @foreach($subjects as $subject)
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $student['marks'][$subject] ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
