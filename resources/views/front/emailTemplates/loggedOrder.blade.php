<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <p>Hello {{$user->name, $user->surname}},</p>
  <p>You have made an order on the site {{getContactInfo('site')->translationByLanguage()->first()->value}}</p>
  <p>We will contact you soon to process your order.</p>

  <p>To see the status of the order, the details of the ordered products and other details, please login into Your Client Area:</p>

  <p><a href="{{url($lang->lang.'/cabinet/personalData')}}">GO TO CLIENT AREA</a></p>

  <p>In case You didn't do actions mentioned above or this mail doesn't refer to You, please ignore the message.</p>

  <p>Truly Yours,</p>

  <p>Eduard Doncila</p>
  <p>Email: {{getContactInfo('emailadmin')->translationByLanguage()->first()->value}}</p>
  <p>Tel: {{getContactInfo('phone')->translationByLanguage()->first()->value}}</p>
  <p>Facebook: {{getContactInfo('facebook')->translationByLanguage()->first()->value}}</p>
</body>
</html>
