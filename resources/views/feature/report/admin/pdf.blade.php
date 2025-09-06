<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Subject Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            height: 60px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .title-bar {
            background: #0d6efd;
            color: white;
            padding: 6px;
            font-weight: bold;
        }

        .flex-row {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="title" style="text-align:center; ">
        <h1>{{strtoupper($adminType)}} REPORT</h1>
    </div>
    <div class="title" >
        <img src="https://avpn.asia/wp-content/uploads/2024/02/PN-Round-Logo1.png" alt="User" class="rounded-circle bg-secondary mb-3" width="80" height="80">
    </div>
    <div class="flex-row" style="margin-top: 10px;">
        <div>
            <p>Generation: {{ $generationName }}</p>
            <p>Term: {{ $termName }}</p>
            <p>Total Subject: {{ count($subjects) }}</p>
        </div>

        <div style="text-align:right;">
            <p>Class: {{ $className }}</p>
            <p>Date: {{ $correntDate }}</p>
            <p>User: {{ $userName }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Subject Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $index => $subject)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subject->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
