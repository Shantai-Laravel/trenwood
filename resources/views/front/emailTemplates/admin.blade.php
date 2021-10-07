<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <p>Hello {{getContactInfo('adminname')->translationByLanguage()->first()->value}}</p>
  <p>New order details:</p>
  <p>Date: {{date('d m Y H:i:s', strtotime($order->created_at))}}</p>
  <p>Order amount: {{$order->amount}} {{trans('front.general.currency')}}</p>
  <p>Client email: {{$order->userLogged->first()->email}}</p>

  <p>Success!</p>
</body>
</html>
