@props(['token' => $token])
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>


<form action="/api/reset-password/{{$token->reset_token}}" method="post">
    @csrf
    <label>
        password
        <input type="password" name="password" placeholder="Password">
    </label>
    <label>
        confirm password
        <input type="password" name="password_confirmation" placeholder="Password">
    </label>

    <button type="submit">Reset</button>
</form>


</body>
</html>
