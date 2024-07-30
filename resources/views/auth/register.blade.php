<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 20px;">Register</h2>
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; margin-bottom: 5px; font-weight: bold;">Name:</label>
                <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                @error('email')
                    <div style="color: red; margin-top: 5px; font-size: 14px">{{ $message }}</div>
                @enderror
            </div>
            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Password:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                @error('password')
                    <div style="color: red; margin-top: 5px; font-size: 14px">{{ $message }}</div>
                @enderror
            </div>
            <div style="margin-bottom: 15px;">
                <label for="password_confirmation" style="display: block; margin-bottom: 5px; font-weight: bold;">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <button type="submit" style="width: 100%; padding: 10px; background-color: #28a745; border: none; color: #fff; font-size: 16px; border-radius: 4px; cursor: pointer;">Register</button>
        </form>
        <a href="{{ route('login') }}" style="display: block; text-align: center; margin-top: 20px; color: #007bff; text-decoration: none;">Login</a>
    </div>
</body>
</html>
