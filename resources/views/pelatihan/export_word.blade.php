<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1.title {
            text-align: center;
        }
        p {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1 class="title">Laporan Data User</h1>
    <p>Berikut adalah data user yang terdaftar:</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ e($user->username) }}</td>
                    <td>{{ e($user->nama_lengkap) }}</td>
                    <td>{{ e(optional($user->level)->level_nama) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
