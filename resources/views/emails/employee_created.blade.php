<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Created</title>
</head>

<body>
    <p>Hello {{ $employee['first_name'] . ' ' . $employee['last_name'] }}</p>
    <p>We are pleased to announce that you have been added to the system.</p>
    <p>Email: {{ $employee['email'] }}</p>
    <p>Have a great day!</p>
</body>

</html>