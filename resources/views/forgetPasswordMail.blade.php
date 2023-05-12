<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data['title']}}</title>
</head>
<body>
    <h6>Hello ,Dear User this email sended for reset password in Laravel API project</h6>
    <p>{{$data['body']}}</p><br>
    <a href="{{ $data['url']}}">Click Here TO reset Password</a>
    <p>Thank You.</p>
</body>
</html>