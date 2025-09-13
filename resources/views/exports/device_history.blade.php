<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device History Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>Device History Report</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Action Type</th>
                <th>Employee</th>
                <th>Help Desk</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $history)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $history['action_type'] }}</td>
                    <td>{{ $history['employee_name'] }}</td>
                    <td>{{ $history['help_desk_name'] }}</td>
                    <td>{{ $history['created_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
