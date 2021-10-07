<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartSet;
use App\Models\Contact;
use App\Models\FrontUser;
use App\Models\UserField;
use App\Models\Promocode;
use App\Models\PromocodeType;
use PDF;
use Session;
use App\Models\Collection;

class OrderController extends Controller
{
    private $addressMain;

    /**
     *  post action
     *  Main function, which is called when make order in front end,
     *  validate all required fields and if it's valid call function which make order
     */
    public function index(Request $request)
    {
        $toValidate = [];

        $uniquefields = UserField::where('in_cart', 1)->where('unique_field', 1)->get();

        $requiredPersonalDatafields = UserField::where('in_cart', 1)->where('field_group', 'personaldata')->where('required_field', 1)->get();
        $requiredAddressfields = UserField::where('in_cart', 1)->where('field_group', 'address')->where('required_field', 1)->get();

        if(count($requiredPersonalDatafields) > 0) {
            foreach ($requiredPersonalDatafields as $requiredPersonalDatafield) {
                if($requiredPersonalDatafield->field == 'name' || $requiredPersonalDatafield->field == 'surname') {
                    $toValidate[$requiredPersonalDatafield->field] = 'required|min:3';
                } else {
                    $toValidate[$requiredPersonalDatafield->field] = 'required';
                }
            }
        }

        if(request('delivery') !== 'pickup') {
            if(count($requiredAddressfields) > 0) {
                foreach ($requiredAddressfields as $requiredAddressfield) {
                    $toValidate[$requiredAddressfield->field] = 'required';
                }
            }
        } else {
            $toValidate['pickup'] = 'required';
        }

        $toValidate['delivery'] = 'required';
        $toValidate['payment'] = 'required';

        if(Auth::guard('persons')->guest()) {
            // $client = new Client;
            // $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            //         'form_params' => [
            //             'secret' => env('RE_CAP_SECRET'),
            //             'response' => request('g-recaptcha-response'),
            //             'remoteip' => request()->ip()
            //         ]
            // ]);
            //
            // if(!json_decode($response->getBody())->success) {
            //     $toValidate['captcha'] = 'required';
            // }
            if(count($uniquefields) > 0) {
                foreach ($uniquefields as $uniquefield) {
                    if($uniquefield->field == 'email') {
                        $toValidate[$uniquefield->field] = 'required|unique:front_users|email';
                    } else {
                        $toValidate[$uniquefield->field] = 'required|unique:front_users';
                    }
                }
            }

            $cartProducts = $this->getCartProducts($_COOKIE['user_id']);
            $cartSets = $this->getCartSets($_COOKIE['user_id']);
        } else {
            $user = FrontUser::find(Auth::guard('persons')->id());
            if(count($user->addresses()->get()) > 0) {
              $toValidate['addressMain'] = 'required';
            }
            unset($toValidate['terms_agreement']);
            if(count($uniquefields) > 0) {
                foreach ($uniquefields as $uniquefield) {
                    if($uniquefield->field == 'email') {
                        $toValidate[$uniquefield->field] = 'required|email';
                    } else {
                        $toValidate[$uniquefield->field] = 'required';
                    }
                }
            }
            $cartProducts = $this->getCartProducts($user->id);
            $cartSets = $this->getCartSets($user->id);
        }

        if(count($cartProducts) == 0 && count($cartSets) == 0) {
          $toValidate['emptyCart'] = 'required';
        }

        $validator = $this->validate(request(), $toValidate);

        $order = $this->orderProducts($request->all(), $cartProducts, $cartSets);

        return redirect()->route('thanks');
    }
    /**
     *  private method
     *  Register user
     */
    private function createClient($password) {
        $user = FrontUser::create([
            'is_authorized' => 0,
            'lang' => 1,
            'name' => request('name') ? request('name') : '',
            'surname' => request('surname') ? request('surname') : '',
            'email' => request('email') ? request('email') : '',
            'phone' => request('phone') ? request('phone') : '',
            'password' => bcrypt($password),
            'terms_agreement' => request('terms_agreement') ? 1 : 0,
            'promo_agreement' => request('promo_agreement') ? 1 : 0,
            'personaldata_agreement' => request('personaldata_agreement') ? 1 : 0,
            'remember_token' => request('_token')
        ]);

        $this->createClientAddress($user);

        return $user;
    }
    /**
     *  private method
     *  Create user address
     */
    private function createClientAddress($user) {
        if(request('delivery') !== 'pickup') {
            $address = $user->addresses()->create([
                'addressname' => request('addressname'),
                'country' => request('country'),
                'region' => request('region'),
                'location' => request('location')
            ]);

            $this->addressMain = $address->id;
        } else {
            $this->addressMain = request('pickup');
        }

    }
    /**
     *  private method
     *  Create promocode
     */
    private function createPromocode($promoType, $userId) {
        $promocode = Promocode::create([
          'name' => 'repeated'.str_random(5),
          'type_id' => $promoType->id,
          'discount' => $promoType->discount,
          'valid_from' => date('Y-m-d'),
          'valid_to' => date('Y-m-d', strtotime(' + '.$promoType->period.' days')),
          'period' => $promoType->period,
          'treshold' => $promoType->treshold,
          'to_use' => 0,
          'times' => $promoType->times,
          'status' => 'valid',
          'user_id' => $userId
        ]);

        return $promocode;
    }
    /**
     *  private method
     *  Create order
     */
    private function createOrder($userId, $amount, $promocode, $cartProducts, $cartSets) {
        $order = Order::create([
            'user_id' => $userId,
            'address_id' => $this->addressMain,
            'is_logged' => 1,
            'amount' => $amount,
            'status' => 'pending',
            'secondarystatus' => 'confirmed',
            'paymentstatus' => 'notpayed',
            'delivery' => request('delivery'),
            'payment' => request('payment'),
            'promocode_id' => count($promocode) > 0 ? $promocode->id : 0
        ]);

        if(count($cartSets) > 0) {
            foreach ($cartSets as $key => $cartSet):
                $orderSet = $order->orderSets()->create([
                    'set_id' => $cartSet->set_id,
                    'qty' => $cartSet->qty,
                    'price' => $cartSet->price
                ]);

                foreach ($cartSet->cart as $cart):
                    $order->orderProducts()->create([
                      'product_id' => $cart->product_id,
                      'subproduct_id' => $cart->subproduct_id,
                      'qty' => $cart->qty,
                      'set_id' => $orderSet->id
                    ]);

                    if ($cart->subproduct->stock >= $cartSet->qty) {
                        $cart->subproduct->stock -= $cartSet->qty;
                    }else{
                        $cart->subproduct->stock = 0;
                    }
                    $cart->subproduct->save();
                endforeach;
            endforeach;
        }

        if(count($cartProducts) > 0) {
            foreach ($cartProducts as $key => $cartProduct):
                $order->orderProducts()->create([
                  'product_id' => $cartProduct->product_id,
                  'subproduct_id' => $cartProduct->subproduct_id,
                  'qty' => $cartProduct->qty
                ]);

                if ($cartProduct->subproduct->stock >= $cartProduct->qty) {
                    $cartProduct->subproduct->stock -= $cartProduct->qty;
                }else{
                    $cartProduct->subproduct->stock = 0;
                }
                $cartProduct->subproduct->save();
            endforeach;
        }

        return $order;
    }
    /**
     *  private method
     *  Send message to user
     */
    private function sendMessage($user, $promocode, $password) {
        $to = request('email');

        $subject = trans('front.cart.subject');

        if(Auth::guard('persons')->check()) {
            $message = view('front.emailTemplates.loggedOrder', compact('user'))->render();
        } else {
            $message = view('front.emailTemplates.unloggedOrder', compact('user', 'password'))->render();
        }

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        mail($to, $subject, $message, $headers);
    }
    /**
     *  private method
     *  Send message to admin
     */
    private function sendMessageToAdmin($order) {
        $to = implode(',', getContactInfo('emailadmin')->translationByLanguage()->pluck('value')->toArray());

        $subject = trans('front.cart.subjectAdmin', ['site' => getContactInfo('site')->translationByLanguage()->first()->value]);

        $message = view('front.emailTemplates.admin', compact('order'))->render();

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        mail($to, $subject, $message, $headers);
    }
    /**
     *  private method
     *  Update user
     */
    private function updateClient() {
        $user = FrontUser::find(Auth::guard('persons')->id());
        $user->name = request('name');
        $user->surname = request('surname');
        $user->email = request('email');
        $user->phone = request('phone');

        $user->save();

        $this->updateClientAddress($user);

        return $user;
    }
    /**
     *  private method
     *  Update user addresses
     */
    private function updateClientAddress($user) {
        if(request('delivery') !== 'pickup') {
            if(count($user->addresses()->get()) > 0) {
                foreach ($user->addresses()->get() as $key => $address) {
                    $address->addressname = request('addressname')[$key];
                    $address->country = request('country')[$key];
                    $address->region = request('region')[$key];
                    $address->location = request('location')[$key];
                    $address->save();
                }
                $this->addressMain = request('addressMain');
            } else {
                $address = $user->addresses()->create([
                    'addressname' => request('addressname'),
                    'country' => request('country'),
                    'region' => request('region'),
                    'location' => request('location')
                ]);
                $this->addressMain = $address->id;
            }
        } else {
            $this->addressMain = request('pickup');
        }
    }
    /**
     *  private method
     *  Check if promo is valid
     */
    private function checkPromo($amount) {
      $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                              ->where('treshold', '<', $amount)
                              ->whereRaw('to_use < times')
                              ->where(function($query) {
                                  $query->where('status', 'valid');
                                  $query->orWhere('status', 'partially');
                              })
                              ->first();
      if(count($promocode) > 0) {
          if($promocode->user_id !== 0) {
              if(Auth::guard('persons')->guest()) {
                  return false;
              } else if(Auth::guard('persons')->check() && $promocode->user_id !== Auth::guard('persons')->id()) {
                  return false;
              }
          }
          $amount = $amount - ($amount * $promocode->discount / 100);
          $promocode->to_use += 1;
          $promocode->status = 'invalid';
          $promocode->save();
      }

      return $amount;
    }
    /**
     *  private method
     *  Method where all private methods are called,
     *  make order method
     */
    private function orderProducts($request, $cartProducts, $cartSets) {
        $amountWithOutPromo = $this->getAmount($cartProducts) + $this->getSetsAmount($cartSets);

        $amount = $this->checkPromo($amountWithOutPromo);

        $deliveryPrice = getContactInfo('delivery')->translationByLanguage()->first()->value;
        $treshold = getContactInfo('treshold')->translationByLanguage()->first()->value;

        if ($treshold < $amount) {
            $deliveryPrice = 0;
        }

        $amount = $amount + $deliveryPrice;

        $promoType = PromocodeType::find(4);

        if(Auth::guard('persons')->check()) {
            $user = $this->updateClient();

            $promocode = $this->createPromocode($promoType, $user->id);

            $order = $this->createOrder($user->id, $amount, $promocode, $cartProducts, $cartSets);

            Cart::where('user_id', Auth::guard('persons')->id())->delete();
            CartSet::where('user_id', Auth::guard('persons')->id())->delete();

            $this->sendMessage($user, $promocode, '');
        } else {
            $password = str_random(12);

            $user = $this->createClient($password);

            $promocode = $this->createPromocode($promoType, $user->id);

            $order = $this->createOrder($user->id, $amount, $promocode, $cartProducts, $cartSets);

            Cart::where('user_id', @$_COOKIE['user_id'])->delete();
            CartSet::where('user_id', @$_COOKIE['user_id'])->delete();

            session()->put(['token' => str_random(60), 'user_id' => $user->id]);

            $this->sendMessage($user, $promocode, $password);

            Auth::guard('persons')->login($user);
        }

        $this->sendMessageToAdmin($order);

        return $order;
    }
    /**
     *  private method
     *  Get products amount
     */
    private function getAmount($cartProducts) {
        $amount = 0;
        foreach ($cartProducts as $key => $cartProduct):

          if($cartProduct->set) {
            $price = $cartProduct->price;
          } else {
            $price = $cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100);
          }

          if($price) {
            $amount +=  $price * $cartProduct->qty;
          }
        endforeach;

        return $amount;
    }
    /**
     *  private method
     *  Get sets amount
     */
    private function getSetsAmount($cartSets) {
        $amount = 0;
        foreach ($cartSets as $key => $cartSet):
          $amount +=  $cartSet->price * $cartSet->qty;
        endforeach;

        return $amount;
    }
    /**
     *  private method
     *  get cart products
     */
    private function getCartProducts($id) {
       $rows = Cart::where('user_id', $id)->where('set_id', 0)->get();
       return $rows;
    }
    /**
     *  private method
     *  get cart sets
     */
    private function getCartSets($id) {
        $rows = CartSet::where('user_id', $id)->get();
        return $rows;
    }
    /**
     *  get action
     *  Render thanks page
     */
    public function thanks() {
        $promocode = Promocode::where('user_id', Auth::guard('persons')->id())
                      ->whereRaw('to_use < times')
                      ->where('valid_to', '>', date('Y-m-d'))
                      ->orderBy('id', 'desc')->first();
        if(Auth::guard('persons')->check() && count($promocode) > 0) {
            $collections = Collection::all();
            return view('front.orders.thanks', compact('collections', 'promocode'));
        } else {
            return redirect()->route('404')->send();
        }
    }

}
