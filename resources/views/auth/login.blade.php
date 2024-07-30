<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 20px;">Login</h2>
       
        <form method="post" action="{{ route('login.post') }}">
            @csrf
            <div style="margin-bottom: 15px;">
                <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                @error('email')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Password:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                @error('password')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer;">Login</button>
        </form>
        <a href="{{ route('register') }}" style="display: block; text-align: center; margin-top: 20px; color: #007bff; text-decoration: none;">Register</a>
    </div>
</body>
</html>
