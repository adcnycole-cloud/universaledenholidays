<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Packages Import Template</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1c1917;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 20px;
        }

        p {
            margin: 0 0 10px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #d6d3d1;
            padding: 7px 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background: #f5f5f4;
            font-size: 10px;
            text-transform: uppercase;
        }

        ul {
            margin: 12px 0 0;
            padding-left: 18px;
        }

        li {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <h1>Packages Import Template</h1>
    <p>Use this table as the column guide for importing packages in bulk from the admin packages page.</p>
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
