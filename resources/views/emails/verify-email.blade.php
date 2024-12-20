<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <h1>Welcome to DKingSolutions</h1>
    <p>Click the link below to verify your email address:</p>
    <a href="{{ $verificationUrl }}">Verify Email</a>
    <p>If above link now worked Try this {{$verificationUrl}}</p>
</body>
</html>
