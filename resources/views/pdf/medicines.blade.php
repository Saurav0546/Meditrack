<!DOCTYPE html>
<html>

<head>
    <title>Medicines</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }
    </style>
</head>

<body>
    <h1>Medicines List</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Stock</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($medicines as $medicine)
            <tr>
                <td>{{ $medicine->id }}</td>
                <td>{{ $medicine->name }}</td>
                <td>{{ $medicine->description }}</td>
                <td>{{ $medicine->stock }}</td>
                <td>${{ number_format($medicine->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>