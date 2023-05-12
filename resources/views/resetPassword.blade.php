<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>reset Password</title>
</head>
<body>
    
    <form action="" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $user[0]['id']}}">
        <input type="password" name="password" placeholder="New Password"><br><br>
        <input type="password" name="password_confirmation" placeholder="Confirm Password"><br><br>
        <button>Change Password</button>

    </form>
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $err)
            <li>{{$err}}</li>
            @endforeach
            
        </ul>
    @endif
</body>
</html>

