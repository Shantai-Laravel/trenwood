<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <p>Hello, {{$user->name, $user->surname}},</p>
  <p>You have requested Your password account recovery. Please enter the following code: {{session('code')}}</p>
  <p>by the following link: {{url($lang->lang.'/password/email')}}</p>

  <p>In case You didn't do actions mentioned above or this mail doesn't refer to You, please ignore the message.</p>

  <p>Truly Yours,</p>

  <p>Eduard Doncila</p>
  <p>Email: {{getContactInfo('emailadmin')->translationByLanguage()->first()->value}}</p>
  <p>Tel: {{getContactInfo('phone')->translationByLanguage()->first()->value}}</p>
  <p>Facebook: {{getContactInfo('facebook')->translationByLanguage()->first()->value}}</p>
</body>
</html>
