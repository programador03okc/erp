<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th style="background-color: #cccccc;" width="18">serie</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
            <tr>
                <td>{{$d->serie}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>