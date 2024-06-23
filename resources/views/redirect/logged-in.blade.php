<!-- resources/views/logged-in.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged In Successfully</title>
</head>
<body>
<div>
    <h1>Logged in successfully!</h1>
    <p>Welcome to your dashboard.</p>
    <p>Click <a href="{{ env('FRONTEND_URL') }}">here</a> to go to the Vue.js frontend.</p>
</div>
</body>
</html>
