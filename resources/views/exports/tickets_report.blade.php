<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f0e9e9; }
        h2 { margin-top: 30px; }
    </style>
</head>
<body>

    <h1>Tickets Report</h1>

    @foreach($data as $group)
        <h2>Status: {{ $group['status'] }} ({{ $group['count'] }} Tickets)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Help Desk</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group['tickets'] as $ticket)
                <tr>
                    <td>{{ $ticket['id'] }}</td>
                    <td>{{ $ticket['title'] }}</td>
                    <td>{{ $ticket['date'] }}</td>
                    <td>{{ $ticket['employee'] }}</td>
                    <td>{{ $ticket['help_desk'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>
