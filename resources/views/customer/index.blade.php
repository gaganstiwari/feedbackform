<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width:100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        tr:hover { background-color:#f9f9f9; cursor:pointer; }
        th { background:#f4f4f4; font-weight: bold; }
        h2 { text-align:center; margin-top:20px; color: #0f2650; }
        .info { background: #e8f4f8; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
<table border="1" cellpadding="8">
    <tr>
        <th>Patient Name</th>
        <th>Policy Number</th>
        <th>Request ID</th>
        <th>City</th>
        <th>Test</th>
    </tr>

    @foreach($records as $item)
    <tr class="clickable-row"
        data-href="{{ $item['feedback_url'] ?? route('feedback.form') }}">
        <td>{{ $item['patient_name'] }}</td>
        <td>{{ $item['policy_number'] }}</td>
        <td>{{ $item['request_id'] }}</td>
        <td>{{ $item['city'] }}</td>
        <td>{{ $item['test'] }}</td>
    </tr>
    @endforeach
</table>

<script>
    document.querySelectorAll('.clickable-row').forEach(function(row) {
        row.addEventListener('click', function() {
            const target = this.dataset.href;
            if (target) {
                window.location.href = target;
            }
        });
    });
</script>
</body>
</html>
