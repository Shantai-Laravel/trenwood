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

  <p>Additionally, we would like to inform you that we have created an account for You on our site. Having a personal account, you can log in to your personal office and benefit from several options:</p>
  <p>1. View order history and, current orders and products from Your Cart</p>
  <p>2. View Your Favorite products (Wishlist)</p>
  <p>3. Return request on ordered products</p>
  <p>4. Edit Your Profile data</p>
  <p>5. Functionality to initiate return order</p>
  <p>Below are the access data:</p>

  <p>Login: {{$user->email}}</p>
  <p>Password: {{$password}}</p>

  <p><a href="{{url($lang->lang.'/register/changePass/'.session('token'))}}">If you want to change your password, click on the following link:</a></p>

  <p>In case You didn't do actions mentioned above or this mail doesn't refer to You, please ignore the message.</p>

  <p>Truly Yours,</p>

  <p>Eduard Doncila</p>
  <p>Email: {{getContactInfo('emailadmin')->translationByLanguage()->first()->value}}</p>
  <p>Tel: {{getContactInfo('phone')->translationByLanguage()->first()->value}}</p>
  <p>Facebook: {{getContactInfo('facebook')->translationByLanguage()->first()->value}}</p>
</body>
</html>
