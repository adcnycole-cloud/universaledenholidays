<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Packages Import Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #222;
        }

        h1 {
            font-size: 18pt;
            margin: 0 0 8pt;
        }

        p {
            margin: 0 0 10pt;
            line-height: 1.45;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10pt;
        }

        th,
        td {
            border: 1px solid #bfbfbf;
            padding: 6pt;
            vertical-align: top;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        ul {
            margin: 12pt 0 0 18pt;
        }

        li {
            margin-bottom: 4pt;
        }
    </style>
</head>
<body>
    <h1>Packages Import Template</h1>
    <p>Use this Word table as the column guide when preparing a package import file for the admin packages page.</p>
    <p><strong>Important:</strong> keep the column order exactly the same. Put numbers only in <strong>capacity</strong>, the full link in <strong>image_url</strong>, and leave <strong>discount_percentage</strong> blank unless <strong>is_discounted</strong> is yes.</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Header</th>
                <th>Required</th>
                <th>Description</th>
                <th>Sample</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($columns as $column)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $column['header'] }}</td>
                    <td>{{ $column['required'] }}</td>
                    <td>{{ $column['description'] }}</td>
                    <td>{{ $column['sample'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <ul>
        @foreach ($notes as $note)
            <li>{{ $note }}</li>
        @endforeach
    </ul>
</body>
</html>
