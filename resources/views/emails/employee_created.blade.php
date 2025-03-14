<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Created</title>
</head>

<body>
    <p>Xin chào {{ $employee['first_name'] . ' ' . $employee['last_name'] }}</p>
    <p>Chúng tôi vui mừng thông báo rằng bạn đã được thêm vào hệ thống.</p>
    <p>Email: {{ $employee['email'] }}</p>
    <p>Chúc bạn một ngày tốt lành!</p>
</body>

</html>