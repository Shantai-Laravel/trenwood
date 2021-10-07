<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\FrontUserAddress;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubProduct;
use App\Models\Contact;
use App\Models\UserField;
use App\Models\FrontUser;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use App\Models\Promocode;
use App\Models\Set;
use App\Models\CartSet;
use App\Models\OrderSet;
use PDF;
use Session;

class OrderController extends Controller
{
  /**
   *  get action
   *  Render orders page
   */
    public function index()
    {
      $orders = Order::where('status', 'pending')->get();

      return view('admin::admin.orders.index', compact('orders', 'filters'));
    }
    /**
     *  post action
     *  Filter orders by status
     */
    public function filterOrders(Request $request)
    {
      $orders = Order::where('status', $request->get('status'))->get();

      $data['orders'] = view('admin::admin.orders.orders', compact('orders'))->render();

      return json_encode($data);
    }
    /**
     *  get action
     *  Render create order page
     */
    public function create() {
        $users = FrontUser::all();

        if(count($users) > 0) {
            $frontuser = $users[0];

            if($frontuser->priorityaddress != 0) {
                $address = $frontuser->addresses()->where('id', $frontuser->priorityaddress)->first();
            } else {
                $address = $frontuser->addresses()->orderBy('id', 'desc')->first();
            }

            $cartProducts = Cart::where('user_id', $frontuser->id)->where('set_id', 0)->get();
            $cartSets = CartSet::where('user_id', $frontuser->id)->get();

            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();

            $userfields = UserField::where(function ($query) {
                                   $query->where('in_register', '!=', 0)
                                         ->orWhere('in_cabinet', '!=', 0)
                                         ->orWhere('in_cart', '!=', 0)
                                         ->orWhere('in_auth', '!=', 0);
                                    })->get();

            $countries = UserField::where('field', 'countries')->first();

            if(count($countries) > 0 && $countries->value != '') {
              $countries = Country::whereIn('id', json_decode($countries->value))->get();
            } else {
              $countries = Country::all();
            }

            if(!empty($address)) {
              $regions[] = Region::where('location_country_id', $address->country)->get();
              $cities[] = City::where('location_region_id', $address->region)->get();
            }

            return view('admin::admin.orders.create', compact('users', 'frontuser', 'address', 'userfields', 'cartProducts', 'cartSets', 'promocode', 'countries', 'regions', 'cities'));
        } else {
            return trans('front.admin.noUser');
        }
    }
    /**
     *  post action
     *  Filter users and their personal data
     */
    public function filterUsers(Request $request) {
        $frontuser = FrontUser::find($request->get('user_id'));

        if(count($frontuser) > 0) {
            if($frontuser->priorityaddress != 0) {
                $address = $frontuser->addresses()->where('id', $frontuser->priorityaddress)->first();
            } else {
                $address = $frontuser->addresses()->orderBy('id', 'desc')->first();
            }
        }

        $cartProducts = Cart::where('user_id', $request->get('user_id'))->get();

        $userfields = UserField::where(function ($query) {
                               $query->where('in_register', '!=', 0)
                                     ->orWhere('in_cabinet', '!=', 0)
                                     ->orWhere('in_cart', '!=', 0)
                                     ->orWhere('in_auth', '!=', 0);
                                })->get();

        $countries = UserField::where('field', 'countries')->first();

        if(count($countries) > 0 && $countries->value != '') {
          $countries = Country::whereIn('id', json_decode($countries->value))->get();
        } else {
          $countries = Country::all();
        }

        if(!empty($address)) {
          $regions[] = Region::where('location_country_id', $address->country)->get();
          $cities[] = City::where('location_region_id', $address->region)->get();
        }

        $data['userinfo'] = view('admin::admin.orders.orderBlock', compact('frontuser', 'address', 'userfields', 'countries', 'regions', 'cities'))->render();
        $data['cartProducts'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Main function which validate all required fields and if is valid call make order method,
     *  if payment is invoice send message
     */
    public function store(Request $request) {
        $this->validate($request, array(
          'name' => 'required|min:3',
          'surname' => 'required|min:3',
          'email' => 'required|email',
          'phone' => 'required|min:9|numeric'
        ));

        $cartProducts = $this->getCartProducts($request->get('front_user_id'));
        $cartSets = $this->getCartSets($request->get('front_user_id'));

        if(count($cartProducts) == 0 && count($cartSets) == 0) {
          return redirect()->back()->withInput()->withErrors(trans('front.cart.empty'));
        }

        if(count($cartSets) > 0) {
            foreach ($cartSets as $cartSet) {
                foreach ($cartSet->cart as $cartProduct) {
                    if(!$cartProduct->subproduct) {
                        return redirect()->back()->withErrors('Choose size');
                    }
                }
            }
        }

        if($request->delivery == 'courier') {

          $this->validate($request, array(
            'addressname' => 'required',
            'country'=> 'required'
          ));

          $order = $this->orderProducts($request->all(), $cartProducts, $cartSets);

        } else {

          $this->validate($request, array(
            'addressPickup' => 'required',
            'date' => 'required',
            'time' => 'required'
          ));

          $order = $this->orderProducts($request->all(), $cartProducts, $cartSets);

        }

        if($request->get('payment') == 'invoice') {
            $order = Order::find($order->id);
            $contacts = Contact::all();

            $pdf = PDF::loadView('front.inc.invoice', compact('order', 'contacts'))->output();

            $filename = 'invoice.pdf';
            $mailto = $request->get('email');
            $subject = trans('front.invoice.subject');
            $message = trans('front.invoice.message');

            $content = chunk_split(base64_encode($pdf));

            // a random hash will be necessary to send mixed content
            $separator = md5(time());

            // carriage return type (RFC)
            $eol = "\r\n";

            // main header (multipart mandatory)
            // $headers = "From: name <test@test.com>" . $eol;
            $headers = "MIME-Version: 1.0" . $eol;
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
            $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
            $headers .= "This is a MIME encoded message." . $eol;

            // message
            $body = "--" . $separator . $eol;
            $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
            $body .= "Content-Transfer-Encoding: 8bit" . $eol;
            $body .= $message . $eol;

            // attachment
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol;
            $body .= $content . $eol;
            $body .= "--" . $separator . "--";

            mail($mailto, $subject, $body, $headers);
        }

        return redirect()->route('order.index');
    }
    /**
     *  get action
     *  Render edit order page
     */
    public function edit($id)
    {
        $order = Order::find($id);

        $userfields = UserField::where(function ($query) {
                               $query->where('in_register', '!=', 0)
                                     ->orWhere('in_cabinet', '!=', 0)
                                     ->orWhere('in_cart', '!=', 0)
                                     ->orWhere('in_auth', '!=', 0);
                                })->get();

        $countries = UserField::where('field', 'countries')->first();

        if(count($countries) > 0 && $countries->value != '') {
          $countries = Country::whereIn('id', json_decode($countries->value))->get();
        } else {
          $countries = Country::all();
        }

        if(!empty($order->userLogged()->first()->addresses()->get())) {
            foreach ($order->userLogged()->first()->addresses()->get() as $address) {
                $regions[] = Region::where('location_country_id', $address->country)->get();
                $cities[] = City::where('location_region_id', $address->region)->get();
            }
        }

        if(!empty($order->userUnlogged()->first())) {
          if(!empty($order->userUnLogged()->first()->addresses()->get())) {
              foreach ($order->userUnLogged()->first()->addresses()->get() as $address) {
                  $regions[] = Region::where('location_country_id', $address->country)->get();
                  $cities[] = City::where('location_region_id', $address->region)->get();
              }
          }
        }

        if(!$order->promocode) {
          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();
        } else {
          $promocode = [];
        }

        return view('admin::admin.orders.edit', compact('order', 'userfields', 'countries', 'regions', 'cities', 'promocode'));
    }
    /**
     *  post action
     *  Main function which validate all required fields and if is valid update order
     */
    public function update($id) {
        $this->validate(request(), array(
          'name' => 'required|min:3',
          'surname' => 'required|min:3',
          'email' => 'required|email',
          'phone' => 'required|min:9|numeric'
        ));

        $order = Order::find($id);

        if(count($order->orderSets) > 0) {
            foreach ($order->orderSets as $orderSet) {
                foreach ($orderSet->orderProduct as $orderProduct) {
                    if(!$orderProduct->subproduct) {
                        return redirect()->back()->withErrors('Choose size');
                    }
                }
            }
        }

        if(count($order->userLogged()->first()) > 0) {
            $user = $order->userLogged()->first();
        } else {
            $user = $order->userUnlogged()->first();
        }

        $user->email = request('email');
        $user->phone = request('phone');
        $user->name = request('name');
        $user->surname = request('surname');
        $user->save();

        if(request('delivery') == 'courier') {

            $this->validate(request(), array(
              'addressname' => 'required',
              'country' => 'required'
            ));

            if(request('addressCourier') != '') {
                $order->address_id = request('addressCourier');
                $order->save();

                $address = $order->addressById()->first();
                $address->addressname = request('addressname');
                $address->country = request('country');
                $address->region = request('region');
                $address->location = request('location');
                $address->address = request('address');
                $address->code = request('code');
                $address->homenumber = request('homenumber');
                $address->entrance = request('entrance');
                $address->floor = request('floor');
                $address->apartment = request('apartment');
                $address->comment = request('comment');
                $address->save();
            } else {
                $address = FrontUserAddress::create([
                  'front_user_id' => $order->user_id,
                  'name' => request('addressname'),
                  'country' => request('country'),
                  'region' => request('region'),
                  'location' => request('location'),
                  'address' => request('address'),
                  'code' => request('code'),
                  'homenumber' => request('homenumber'),
                  'entrance' => request('entrance'),
                  'floor' => request('floor'),
                  'apartment' => request('apartment'),
                  'comment' => request('comment')
                ]);

                $order->address_id = $address->id;
            }
        } else {
            $this->validate(request(), array(
              'addressPickup' => 'required'
            ));
            $order->address_id = request('addressPickup');
            $order->datetime = date("Y-m-d H:i",strtotime(request('date').request('time')));
        }

        if(!$order->promocode) {

            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->whereRaw('to_use < times')
                                    ->where(function($query) {
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })
                                    ->first();

            if(count($promocode) > 0) {
                $order->promocode_id = count($promocode) > 0 ? $promocode->id : 0;
                $promocode->to_use += 1;
                $promocode->save();
            }
        }

        $order->status = request('status');
        $order->secondarystatus = request('secondarystatus');
        $order->paymentstatus = request('paymentstatus');
        $order->save();

        return redirect()->route('order.index');
    }
    /**
     *  post action
     *  Update user address
     */
    public function saveAddress($id) {
        $this->validate(request(), array(
          'addressname' => 'required',
          'country' => 'required'
        ));

        $order = Order::find($id);

        $maxaddress = UserField::where('field', 'maxaddress')->first();

        if(count($order->userLogged()->first()->addresses()->get()) >= $maxaddress->value) {
          session()->flash('deleteAddresses', $order->userLogged()->first()->addresses()->get());
            return redirect()
                    ->back()
                    ->withErrors(trans('front.cabinet.myaddresses.maxaddress').' '.$maxaddress->value.'. '.trans('front.cabinet.myaddresses.deleteaddress'));
        }

        $address = $order->userLogged()->first()->addresses()->create([
            'addressname' => request('addressname'),
            'country' => request('country'),
            'region' => request('region'),
            'location' => request('location'),
            'address' => request('address'),
            'code' => request('code'),
            'homenumber' => request('homenumber'),
            'entrance' => request('entrance'),
            'floor' => request('floor'),
            'apartment' => request('apartment'),
            'comment' => request('comment')
        ]);

        $order->address_id = $address->id;
        $order->save();

        return redirect()->back()->withInput()->withSuccess(trans('front.cabinet.success'));
    }
    /**
     *  post action
     *  Delete user address
     */
    public function deleteAddress($order_id, $id) {
        $order = Order::find($order_id);

        $address = $order->userLogged()->first()->addresses()->where('id', $id)->first();

        if(count($address) > 0) {
          $address->delete();
        } else {
          return redirect()->back()->withErrors(trans('front.cabinet.myaddresses.addressNotExist'));
        }

        request()->session()->flash('success', trans('front.cabinet.myaddresses.deleteAddress'));
        return response()->json(['message' => 'success'], 200);
    }
    /**
     *  post action
     *  Delete order
     */
    public function destroy($id)
    {
      $order = Order::find($id);

      if(count($order->userUnlogged()->first()) > 0) {
        FrontUserAddress::where('front_user_id', $order->userUnlogged()->first()->id)->delete();
        $order->userUnlogged()->delete();
      }

      $order->orderProducts()->delete();
      $order->orderSets()->delete();
      $order->delete();

      session()->flash('message', 'Item has been deleted!');

    	return redirect()->route('order.index');
    }
    /**
     *  post action
     *  Set promocode and apply discount
     */
    public function setPromocode(Request $request)
    {
        $promocode = Promocode::where('name', $request->get('promocode'))
                                ->where(function($query){
                                    $query->where('status', 'valid');
                                    $query->orWhere('status', 'partially');
                                })->first();

        if (!is_null($promocode)) {
            $promocodeId = $promocode->id;
        }else{
            $promocodeId = '';
        }

        setcookie('promocode', $promocodeId, time() + 10000000, '/');

        Session::flash('promocode', $request->get('promocode'));

        if(count($request->get('order_id')) > 0) {
            $order = Order::find($request->get('order_id'));
            $data['block'] = view('admin::admin.orders.cartBlock', compact('order', 'promocode'))->render();
        } else {
            $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
            $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
            $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();
        }

        return json_encode($data);
    }
    /**
     *  post action
     *  Increment product qty (create order - cart tables, update order - tables)
     */
    public function changeQtyPlus(Request $request) {
        if(count($request->get('order_id')) > 0) {
            $orderProduct = OrderProduct::where('order_id', $request->get('order_id'))
                                        ->where('product_id', $request->get('product_id'))
                                        ->where('subproduct_id', $request->get('subproduct_id'))
                                        ->where('set_id', 0)
                                        ->first();

            if (!is_null($orderProduct)) {
                if($orderProduct->subproduct && $orderProduct->qty >= $orderProduct->subproduct->stock) {
                    return response()->json(['message' => trans('front.admin.limit')], 400);
                } else {
                    OrderProduct::where('id', $orderProduct->id)->update([
                        'qty' => $orderProduct->qty + 1,
                    ]);
                }
            }

            $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
            $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
            $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);
            $order = Order::find($orderProduct->order_id);

            if($order->promocode) {
              $promocode = Promocode::where('id', $order->promocode_id)
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query) {
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })
                                      ->first();
            } else {
              $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query){
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })->first();
            }

            if(count($promocode) > 0) {
                $amount = $amount - ($amount * $promocode->discount / 100);
            }

            $order->amount = $amount;
            $order->save();

            $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

            return json_encode($data);
        } else {
            $cartProduct = Cart::where('user_id', $request->get('user_id'))
                               ->where('product_id', $request->get('product_id'))
                               ->where('subproduct_id', $request->get('subproduct_id'))
                               ->where('set_id', 0)
                               ->first();

            if (!is_null($cartProduct)) {
                if($cartProduct->subproduct) {
                    if($cartProduct->qty >= $cartProduct->subproduct->stock) {
                        return response()->json(['message' => trans('front.admin.limit')], 400);
                    } else {
                        Cart::where('id', $cartProduct->id)->update([
                           'qty' => $cartProduct->qty + 1,
                        ]);
                    }
                } else {
                    if($cartProduct->qty >= $cartProduct->product->stock) {
                        return response()->json(['message' => trans('front.admin.limit')], 400);
                    } else {
                        Cart::where('id', $cartProduct->id)->update([
                           'qty' => $cartProduct->qty + 1,
                        ]);
                    }
                }
            }

            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();

            $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
            $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
            $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

            return json_encode($data);
        }
    }
    /**
     *  post action
     *  Increment set qty (create order - cart tables, update order - tables)
     */
    public function changeSetQtyPlus(Request $request) {
        if(count($request->get('order_id')) > 0) {
            $orderSet = OrderSet::where('order_id', $request->get('order_id'))
                                        ->where('id', $request->get('id'))
                                        ->first();

            if (!is_null($orderSet)) {
                OrderSet::where('id', $orderSet->id)->update([
                    'qty' => $orderSet->qty + 1,
                ]);
            }

            $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
            $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
            $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

            $order = Order::find($orderSet->order_id);

            if($order->promocode) {
              $promocode = Promocode::where('id', $order->promocode_id)
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query) {
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })
                                      ->first();
            } else {
              $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query){
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })->first();
            }

            if(count($promocode) > 0) {
                $amount = $amount - ($amount * $promocode->discount / 100);
            }

            $order->amount = $amount;
            $order->save();

            $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

            return json_encode($data);
        } else {
            $cartSet = CartSet::where('user_id', $request->get('user_id'))
                               ->where('id', $request->get('id'))
                               ->first();

            if (!is_null($cartSet)) {
                CartSet::where('id', $cartSet->id)->update([
                   'qty' => $cartSet->qty + 1,
                ]);
            }

            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();

            $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
            $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
            $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

            return json_encode($data);
        }
    }
    /**
     *  post action
     *  Decrement product qty (create order - cart tables, update order - tables)
     */
    public function changeQtyMinus(Request $request) {
        if(count($request->get('order_id')) > 0) {
          $orderProduct = OrderProduct::where('order_id', $request->get('order_id'))
                                      ->where('product_id', $request->get('product_id'))
                                      ->where('subproduct_id', $request->get('subproduct_id'))
                                      ->where('set_id', 0)
                                      ->first();

            if (!is_null($orderProduct)) {
                OrderProduct::where('id', $orderProduct->id)->update([
                    'qty' => $orderProduct->qty >= 2 ? $orderProduct->qty - 1 : 1,
                ]);
            }

            $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
            $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
            $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

            $order = Order::find($orderProduct->order_id);

            if($order->promocode) {
              $promocode = Promocode::where('id', $order->promocode_id)
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query) {
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })
                                      ->first();
            } else {
              $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query){
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })->first();
            }

            if(count($promocode) > 0) {
                $amount = $amount - ($amount * $promocode->discount / 100);
            }

            $order->amount = $amount;
            $order->save();

            $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

            return json_encode($data);
        } else {
            $cartProduct = Cart::where('user_id', $request->get('user_id'))
                             ->where('product_id', $request->get('product_id'))
                             ->where('subproduct_id', $request->get('subproduct_id'))
                             ->where('set_id', 0)
                             ->first();

            if (!is_null($cartProduct)) {
                Cart::where('id', $cartProduct->id)->update([
                    'qty' => $cartProduct->qty >= 2 ? $cartProduct->qty - 1 : 1,
                ]);
            }

            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();

            $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
            $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
            $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

            return json_encode($data);
        }
    }
    /**
     *  post action
     *  Decrement set qty (create order - cart tables, update order - tables)
     */
    public function changeSetQtyMinus(Request $request) {
        if(count($request->get('order_id')) > 0) {
            $orderSet = OrderSet::where('order_id', $request->get('order_id'))
                                        ->where('id', $request->get('id'))
                                        ->first();

            if (!is_null($orderSet)) {
                OrderSet::where('id', $orderSet->id)->update([
                    'qty' => $orderSet->qty >= 2 ? $orderSet->qty - 1 : 1,
                ]);
            }

            $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
            $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
            $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

            $order = Order::find($orderSet->order_id);

            if($order->promocode) {
              $promocode = Promocode::where('id', $order->promocode_id)
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query) {
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })
                                      ->first();
            } else {
              $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query){
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })->first();
            }

            if(count($promocode) > 0) {
                $amount = $amount - ($amount * $promocode->discount / 100);
            }

            $order->amount = $amount;
            $order->save();

            $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

            return json_encode($data);
        } else {
            $cartSet = CartSet::where('user_id', $request->get('user_id'))
                               ->where('id', $request->get('id'))
                               ->first();

            if (!is_null($cartSet)) {
                CartSet::where('id', $cartSet->id)->update([
                   'qty' => $cartSet->qty >= 2 ? $cartSet->qty - 1 : 1,
                ]);
            }

            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();

            $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
            $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
            $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

            return json_encode($data);
        }
    }
    /**
     *  post action
     *  Change product price (create order - cart tables, update order - tables)
     */
    public function changeProductPrice(Request $request) {
        $subproduct = SubProduct::where('id', $request->get('id'))->first();

        if (!is_null($subproduct)) {
            $subproduct->price = $request->get('price');
            $subproduct->save();
        } else {
            $product = Product::where('id', $request->get('id'))->first();

            if (!is_null($product)) {
                $product->price = $request->get('price');
                $product->save();
            }
        }

        if(count($request->get('order_id')) > 0) {
            $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
            $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
            $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

            $order = Order::find($request->get('order_id'));

            if($order->promocode) {
              $promocode = Promocode::where('id', $order->promocode_id)
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query) {
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })
                                      ->first();
            } else {
              $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query){
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })->first();
            }

            if(count($promocode) > 0) {
                $amount = $amount - ($amount * $promocode->discount / 100);
            }

            $order->amount = $amount;
            $order->save();

            $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

            return json_encode($data);
        } else {
          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();

          $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
          $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
          $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

          return json_encode($data);
        }
    }
    /**
     *  post action
     *  Change set price (create order - cart tables, update order - tables)
     */
    public function changeSetPrice(Request $request) {
        if(count($request->get('order_id')) > 0) {
            $orderSet = OrderSet::where('id', $request->get('id'))->first();
            $set = Set::find($orderSet->set_id);

            if (!is_null($orderSet) && !is_null($set)) {
                $orderSet->price = $request->get('price');
                $orderSet->save();

                $set->price = $request->get('price');
                $set->save();
            }

            $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
            $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
            $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

            $order = Order::find($request->get('order_id'));

            if($order->promocode) {
              $promocode = Promocode::where('id', $order->promocode_id)
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query) {
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })
                                      ->first();
            } else {
              $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query){
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })->first();
            }

            if(count($promocode) > 0) {
                $amount = $amount - ($amount * $promocode->discount / 100);
            }

            $order->amount = $amount;
            $order->save();

            $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

            return json_encode($data);
        } else {
          $cartSet = CartSet::where('id', $request->get('id'))->first();
          $set = Set::find($cartSet->set_id);

          if (!is_null($cartSet) && !is_null($set)) {
              $cartSet->price = $request->get('price');
              $cartSet->save();

              $set->price = $request->get('price');
              $set->save();
          }

          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();

          $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
          $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
          $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

          return json_encode($data);
        }
    }
    /**
     *  post action
     *  Change product discount (create order - cart tables, update order - tables)
     */
    public function changeProductDiscount(Request $request) {
        $subproduct = SubProduct::where('id', $request->get('id'))->first();

        if (!is_null($subproduct)) {
            $subproduct->discount = $request->get('discount');
            $subproduct->save();
        } else {
            $product = Product::where('id', $request->get('id'))->first();

            if (!is_null($product)) {
                $product->discount = $request->get('discount');
                $product->save();
            }
        }

        if(count($request->get('order_id')) > 0) {
            $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
            $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
            $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

            $order = Order::find($request->get('order_id'));

            if($order->promocode) {
              $promocode = Promocode::where('id', $order->promocode_id)
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query) {
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })
                                      ->first();
            } else {
              $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query){
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })->first();
            }

            if(count($promocode) > 0) {
                $amount = $amount - ($amount * $promocode->discount / 100);
            }

            $order->amount = $amount;
            $order->save();

            $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

            return json_encode($data);
        } else {
          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();

          $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
          $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
          $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

          return json_encode($data);
        }
    }
    /**
     *  post action
     *  Change set discount (create order - cart tables, update order - tables)
     */
    public function changeSetDiscount(Request $request) {
        $set = Set::find($request->get('id'));

        if (!is_null($set)) {
            $set->discount = $request->get('discount');
            $set->save();
        }

        if(count($request->get('order_id')) > 0) {
            $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
            $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
            $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

            $order = Order::find($request->get('order_id'));

            if($order->promocode) {
              $promocode = Promocode::where('id', $order->promocode_id)
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query) {
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })
                                      ->first();
            } else {
              $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                      ->where('treshold', '<', $amount)
                                      ->whereRaw('to_use < times')
                                      ->where(function($query){
                                          $query->where('status', 'valid');
                                          $query->orWhere('status', 'partially');
                                      })->first();
            }

            if(count($promocode) > 0) {
                $amount = $amount - ($amount * $promocode->discount / 100);
            }

            $order->amount = $amount;
            $order->save();

            $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

            return json_encode($data);
        } else {
          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();

          $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
          $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
          $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

          return json_encode($data);
        }
    }
    /**
     *  post action
     *  Check code and if is valid return product/set/subproduct by code
     */
    public function addProductByCode(Request $request) {
      $set = Set::where('code', $request->get('code'))->first();
      if(count($set) > 0) {
          if(count($request->get('order_id')) > 0) {
              $checkSetInOrder = OrderSet::where('set_id', $set->id)->where('order_id', $request->get('order_id'))->first();
              if(count($checkSetInOrder) > 0) {
                return response()->json(['message' => trans('front.admin.errorProduct')], 400);
              } else {
                return response()->json(['message' => trans('front.admin.successProduct'), 'product' => $set], 200);
              }
          } else {
              $checkSetInCart = CartSet::where('user_id', $request->get('user_id'))->where('set_id', $set->id)->first();

              if(count($checkSetInCart) > 0) {
                return response()->json(['message' => trans('front.admin.errorProduct')], 400);
              } else {
                return response()->json(['message' => trans('front.admin.successProduct'), 'product' => $set], 200);
              }
          }
      } else {
          $product = Product::where('code', $request->get('code'))->first();
          if(count($product) > 0 && $request->get('code') != '' && $product->stock > 0) {
              if(count($product->subproducts()->get()) > 0 && $product->subproducts()->get()[0]->active == 1) {
                  return response()->json(['message' => trans('front.admin.errorProductHasSubProduct')], 400);
              } else {
                  if(count($request->get('order_id')) > 0) {
                      $checkProductInOrder = OrderProduct::where('product_id', $product->id)
                                                          ->where('subproduct_id', 0)
                                                          ->where('order_id', $request->get('order_id'))
                                                          ->where('set_id', 0)
                                                          ->first();

                      if(count($checkProductInOrder) > 0) {
                        return response()->json(['message' => trans('front.admin.errorProduct')], 400);
                      } else {
                        return response()->json(['message' => trans('front.admin.successProduct'), 'product' => $product], 200);
                      }
                  } else {
                      $checkProductInCart = Cart::where('user_id', $request->get('user_id'))
                                                ->where('product_id', $product->id)
                                                ->where('subproduct_id', 0)
                                                ->where('set_id', 0)
                                                ->first();

                      if(count($checkProductInCart) > 0) {
                        return response()->json(['message' => trans('front.admin.errorProduct')], 400);
                      } else {
                        return response()->json(['message' => trans('front.admin.successProduct'), 'product' => $product], 200);
                      }
                  }
              }
          }
          else {
              $subproduct = SubProduct::where('code', $request->get('code'))->first();
              if(count($subproduct) > 0 && $request->get('code') != '' && $subproduct->stock > 0) {
                  if(count($request->get('order_id')) > 0) {
                      $checkSubProductInOrder = OrderProduct::where('product_id', $subproduct->product_id)
                                                            ->where('subproduct_id', $subproduct->id)
                                                            ->where('order_id', $request->get('order_id'))
                                                            ->where('set_id', 0)
                                                            ->first();

                      if(count($checkSubProductInOrder) > 0) {
                        return response()->json(['message' => trans('front.admin.errorProduct')], 400);
                      } else {
                        return response()->json(['message' => trans('front.admin.successProduct'), 'product' => $subproduct], 200);
                      }
                  } else {
                      $checkSubProductInCart = Cart::where('user_id', $request->get('user_id'))
                                                    ->where('product_id', $subproduct->product_id)
                                                    ->where('subproduct_id', $subproduct->id)
                                                    ->where('set_id', 0)
                                                    ->first();

                      if(count($checkSubProductInCart) > 0) {
                        return response()->json(['message' => trans('front.admin.errorProduct')], 400);
                      } else {
                        return response()->json(['message' => trans('front.admin.successProduct'), 'product' => $subproduct], 200);
                      }
                  }
              } else {
                  return response()->json(['message' => trans('front.admin.productNotExist')], 400);
              }
          }
      }
  }
  /**
   *  post action
   *  Add item to cart/order(if create page in cart, else in order)
   */
  public function addToOrder(Request $request) {
      if(count($request->get('order_id')) > 0) {

          $set = Set::where('code', $request->get('code'))->first();
          $order = Order::find($request->get('order_id'));

          if(count($order) > 0) {
              if(count($set) > 0) {
                  $orderSet = $order->orderSets()->create([
                      'set_id' => $set->id,
                      'qty' => 1,
                      'price' => $set->price
                  ]);

                  foreach ($set->products as $product):
                      $order->orderProducts()->create([
                        'product_id' => $product->id,
                        'subproduct_id' => 0,
                        'qty' => 1,
                        'set_id' => $orderSet->id
                      ]);
                  endforeach;
              } else {
                  $product = Product::find($request->get('product_id'));
                  if(count($product) > 0) {
                      if(count($product->subproducts()->get()) > 0) {
                          $subproduct = SubProduct::find($request->get('product_id'));
                          if(count($subproduct) > 0) {
                              $order->orderProducts()->create([
                                'product_id' => $subproduct->product_id,
                                'subproduct_id' => $subproduct->id,
                                'qty' => 1
                              ]);
                          }
                      } else {
                          $order->orderProducts()->create([
                            'product_id' => $product->id,
                            'subproduct_id' => 0,
                            'qty' => 1
                          ]);
                      }
                  } else {
                      $subproduct = SubProduct::find($request->get('product_id'));
                      if(count($subproduct) > 0) {
                          $order->orderProducts()->create([
                            'product_id' => $subproduct->product_id,
                            'subproduct_id' => $subproduct->id,
                            'qty' => 1
                          ]);
                      }
                  }
              }
          }

          $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
          $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
          $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

          if($order->promocode) {
            $promocode = Promocode::where('id', $order->promocode_id)
                                    ->where('treshold', '<', $amount)
                                    ->whereRaw('to_use < times')
                                    ->where(function($query) {
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })
                                    ->first();
          } else {
            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->where('treshold', '<', $amount)
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();
          }

          if(count($promocode) > 0) {
              $amount = $amount - ($amount * $promocode->discount / 100);
          }

          $order->amount = $amount;
          $order->save();

          $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

          return json_encode($data);

      } else {
          $set = Set::where('code', $request->get('code'))->first();

          if(count($set) > 0) {
              $cartSet = CartSet::create([
                  'set_id' => $set->id,
                  'user_id' => $request->get('user_id'),
                  'qty' => 1,
                  'price' => $set->price,
                  'is_logged' => $request->get('user_id') > 0 ? 1 : 0
              ]);
              foreach ($set->products as $product):
                  Cart::create([
                      'product_id' => $product->id,
                      'subproduct_id' => 0,
                      'user_id' => $request->get('user_id'),
                      'qty' => 1,
                      'is_logged' => $request->get('user_id') > 0 ? 1 : 0,
                      'set_id' => $cartSet->id
                  ]);
              endforeach;
          } else {
              $product = Product::find($request->get('product_id'));
              if(count($product) > 0) {
                  if(count($product->subproducts()->get()) > 0) {
                      $subproduct = SubProduct::find($request->get('product_id'));

                      if(count($subproduct) > 0) {
                          Cart::create([
                            'product_id' => $subproduct->product_id,
                            'subproduct_id' => $subproduct->id,
                            'user_id' => $request->get('user_id'),
                            'qty' => 1,
                            'is_logged' => $request->get('user_id') > 0 ? 1 : 0
                          ]);
                      }
                  } else {
                      Cart::create([
                        'product_id' => $product->id,
                        'subproduct_id' => 0,
                        'user_id' => $request->get('user_id'),
                        'qty' => 1,
                        'is_logged' => $request->get('user_id') > 0 ? 1 : 0
                      ]);
                  }
              } else {
                  $subproduct = SubProduct::find($request->get('product_id'));

                  if(count($subproduct) > 0) {
                      Cart::create([
                        'product_id' => $subproduct->product_id,
                        'subproduct_id' => $subproduct->id,
                        'user_id' => $request->get('user_id'),
                        'qty' => 1,
                        'is_logged' => $request->get('user_id') > 0 ? 1 : 0
                      ]);
                  }
              }
          }

          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();

          $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
          $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
          $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

          return json_encode($data);
      }
  }
  /**
   *  post action
   *  Change subproduct in select (create order - cart tables, update order - tables)
   */
  public function changeSubproductSize(Request $request) {
      $subproduct = SubProduct::find($request->get('subproductId'));

      if (!is_null($subproduct)) {
        $cartProduct = Cart::where('user_id', $request->get('user_id'))->where('id', $request->get('id'))->first();

        if(!is_null($cartProduct)) {
            $cartProduct->subproduct_id = $subproduct->id;
            $cartProduct->save();

            $data['subproduct'] = $subproduct;

            return json_encode($data);
        } else {
            $orderProduct = OrderProduct::where('id', $request->get('id'))->first();

            if(!is_null($orderProduct)) {
                $orderProduct->subproduct_id = $subproduct->id;
                $orderProduct->save();

                $data['subproduct'] = $subproduct;

                return json_encode($data);
            }
        }
      } else {
          return response()->json(trans('front.admin.productNotExist'), 400);
      }
  }
  /**
   *  post action
   *  Remove product (create order - cart tables, update order - tables)
   */
  public function removeOrderItem(Request $request) {
      if(count($request->get('order_id')) > 0) {
          $product = OrderProduct::where('order_id', $request->get('order_id'))->where('product_id', $request->get('product_id'))->where('subproduct_id', $request->get('subproduct_id'))->first();

          if (!is_null($product)) {
              OrderProduct::where('id', $product->id)->delete();
          }

          $order = Order::find($request->get('order_id'));

          $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
          $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
          $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

          if($order->promocode) {
            $promocode = Promocode::where('id', $order->promocode_id)
                                    ->where('treshold', '<', $amount)
                                    ->whereRaw('to_use < times')
                                    ->where(function($query) {
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })
                                    ->first();
          } else {
            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->where('treshold', '<', $amount)
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();
          }

          if(count($promocode) > 0) {
              $amount = $amount - ($amount * $promocode->discount / 100);
          }

          $order->amount = $amount;
          $order->save();

          $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

          return json_encode($data);
      } else {
          $product = Cart::where('user_id', $request->get('user_id'))->where('product_id', $request->get('product_id'))->where('subproduct_id', $request->get('subproduct_id'))->first();

          if (!is_null($product)) {
              Cart::where('id', $product->id)->delete();
          }

          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();

          $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
          $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
          $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

          return json_encode($data);
      }
  }
  /**
   *  post action
   *  Remove set (create order - cart tables, update order - tables)
   */
  public function removeOrderSet(Request $request) {
      if(count($request->get('order_id')) > 0) {
          $orderSet = OrderSet::where('order_id', $request->get('order_id'))->where('id', $request->get('id'))->first();

          if (!is_null($orderSet)) {
              $orderSet->delete();
              $orderSet->orderProduct()->delete();
          }

          $order = Order::find($request->get('order_id'));

          $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
          $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
          $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

          if($order->promocode) {
            $promocode = Promocode::where('id', $order->promocode_id)
                                    ->where('treshold', '<', $amount)
                                    ->whereRaw('to_use < times')
                                    ->where(function($query) {
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })
                                    ->first();
          } else {
            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->where('treshold', '<', $amount)
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();
          }

          if(count($promocode) > 0) {
              $amount = $amount - ($amount * $promocode->discount / 100);
          }

          $order->amount = $amount;
          $order->save();

          $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

          return json_encode($data);
      } else {
          $cartSet = CartSet::where('user_id', $request->get('user_id'))->where('id', $request->get('id'))->first();

          if (!is_null($cartSet)) {
              $cartSet->delete();
              $cartSet->cart()->delete();
          }

          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();

          $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
          $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
          $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

          return json_encode($data);
      }
  }
  /**
   *  post action
   *  Remove all items (create order - cart tables, update order - tables)
   */
  public function removeAllOrderItems(Request $request) {
      if(count($request->get('order_id')) > 0) {
          $product = OrderProduct::where('order_id', $request->get('order_id'))->first();

          if (!is_null($product)) {
              OrderProduct::where('order_id', $request->get('order_id'))->delete();
              OrderSet::where('order_id', $request->get('order_id'))->delete();
          }

          $order = Order::find($request->get('order_id'));

          $orderProducts = OrderProduct::where('order_id', $request->get('order_id'))->where('set_id', 0)->get();
          $orderSets = OrderSet::where('order_id', $request->get('order_id'))->get();
          $amount = $this->getAmount($orderProducts) + $this->getSetsAmount($orderSets);

          if($order->promocode) {
            $promocode = Promocode::where('id', $order->promocode_id)
                                    ->where('treshold', '<', $amount)
                                    ->whereRaw('to_use < times')
                                    ->where(function($query) {
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })
                                    ->first();
          } else {
            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->where('treshold', '<', $amount)
                                    ->whereRaw('to_use < times')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();
          }

          if(count($promocode) > 0) {
              $amount = $amount - ($amount * $promocode->discount / 100);
          }

          $order->amount = $amount;
          $order->save();

          $data['block'] = view('admin::admin.orders.cartBlock', compact('order'))->render();

          return json_encode($data);
      } else {
          $product = Cart::where('user_id', $request->get('user_id'))->first();

          if (!is_null($product)) {
              Cart::where('user_id', $request->get('user_id'))->delete();
              CartSet::where('user_id', $request->get('user_id'))->delete();
          }

          $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                  ->whereRaw('to_use < times')
                                  ->where(function($query){
                                      $query->where('status', 'valid');
                                      $query->orWhere('status', 'partially');
                                  })->first();

          $cartProducts = Cart::where('user_id', $request->get('user_id'))->where('set_id', 0)->get();
          $cartSets = CartSet::where('user_id', $request->get('user_id'))->get();
          $data['block'] = view('admin::admin.orders.cartBlockCreate', compact('cartProducts', 'cartSets', 'promocode'))->render();

          return json_encode($data);
      }
  }
  /**
   *  post action
   *  Change order payment
   */
  public function changePayment(Request $request) {
      $order = Order::find($request->get('id'));

      if(count($order) > 0) {

        if($request->get('payment') == 'cash') {
            $order->payment = $request->get('payment');
            $order->save();
        }

        if($request->get('payment') == 'invoice') {
          $order->payment = $request->get('payment');
          $order->save();

          $contacts = Contact::all();

          $pdf = PDF::loadView('front.inc.invoice', compact('order', 'contacts'))->output();

          $filename = 'invoice.pdf';

          if(count($order->userLogged()->first()) > 0) {
            $mailto = $order->userLogged()->first()->email;
          } else {
            $mailto = $order->userUnlogged()->first()->email;
          }

          $subject = trans('front.invoice.subject');
          $message = trans('front.invoice.message');

          $content = chunk_split(base64_encode($pdf));

          // a random hash will be necessary to send mixed content
          $separator = md5(time());

          // carriage return type (RFC)
          $eol = "\r\n";

          // main header (multipart mandatory)
          // $headers = "From: name <test@test.com>" . $eol;
          $headers = "MIME-Version: 1.0" . $eol;
          $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
          $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
          $headers .= "This is a MIME encoded message." . $eol;

          // message
          $body = "--" . $separator . $eol;
          $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
          $body .= "Content-Transfer-Encoding: 8bit" . $eol;
          $body .= $message . $eol;

          // attachment
          $body .= "--" . $separator . $eol;
          $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
          $body .= "Content-Transfer-Encoding: base64" . $eol;
          $body .= "Content-Disposition: attachment" . $eol;
          $body .= $content . $eol;
          $body .= "--" . $separator . "--";

          mail($mailto, $subject, $body, $headers);
        }

        if($request->get('payment') == 'card' || $request->get('payment') == 'paypal') {
            foreach ($order->orderProducts()->get() as $orderProduct):
                Cart::create([
                  'product_id' => $orderProduct->product_id,
                  'subproduct_id' => $orderProduct->subproduct_id,
                  'user_id' => $order->user_id,
                  'qty' => $orderProduct->qty,
                  'is_logged' => $order->is_logged
                ]);
            endforeach;

            $order->orderProducts()->delete();
            $order->delete();
        }

        return response()->json(['message' => 'ok'], 200);
      } else {
        return response()->json(['message' => 'ne ok'], 400);
      }
  }
  /**
   *  post action
   *  Function which creates order
   */
  private function orderProducts($request, $cartProducts, $cartSets) {
      $user = FrontUser::find($request['front_user_id']);

      $statuses = ['valid', 'partially'];

      $amount = $this->getAmount($cartProducts) + $this->getSetsAmount($cartSets);

      $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                              ->where('treshold', '<', $amount)
                              ->whereRaw('to_use < times')
                              ->where(function($query) {
                                  $query->where('status', 'valid');
                                  $query->orWhere('status', 'partially');
                              })
                              ->first();

      if(count($promocode) > 0) {
          $amount = $amount - ($amount * $promocode->discount / 100);
          $promocode->to_use += 1;
          $promocode->save();
      }

      if(count($user) > 0) {
          $user->name = $request['name'];
          $user->surname = $request['surname'];
          $user->phone = $request['phone'];
          $user->email = $request['email'];
          $user->save();

          if($request['delivery'] == 'courier') {
                if($request['addressCourier'] > 0) {
                  $user->addresses()->where('id', $request['addressCourier'])->update([
                      'front_user_id' => $user->id,
                      'addressname' => $request['addressname'],
                      'country' => $request['country'],
                      'region' => $request['region'],
                      'location' => $request['location'],
                      'address' => $request['address'],
                      'code' => $request['code'],
                      'homenumber' => $request['homenumber'],
                      'entrance' => $request['entrance'],
                      'floor' => $request['floor'],
                      'apartment' => $request['apartment'],
                      'comment' => $request['comment']
                  ]);
                  $address_id = $request['addressCourier'];
              } else {
                  $address = FrontUserAddress::create([
                      'front_user_id' => $user->id,
                      'addressname' => $request['addressname'],
                      'country' => $request['country'],
                      'region' => $request['region'],
                      'location' => $request['location'],
                      'address' => $request['address'],
                      'code' => $request['code'],
                      'homenumber' => $request['homenumber'],
                      'entrance' => $request['entrance'],
                      'floor' => $request['floor'],
                      'apartment' => $request['apartment'],
                      'comment' => $request['comment']
                  ]);
                  $address_id = $address->id;
              }
              $datetime = date('Y-m-d H:i:s');
          } else {
              $address_id = $request['addressPickup'];
              $datetime = date('Y-m-d h:i:s', strtotime($request['date'].' '.$request['time']));
          }

          $order = Order::create([
              'user_id' => $user->id,
              'address_id' => $address_id,
              'is_logged' => 1,
              'amount' => $amount,
              'status' => 'pending',
              'secondarystatus' => 'confirmed',
              'paymentstatus' => 'notpayed',
              'delivery' => $request['delivery'],
              'payment' => $request['payment'],
              'datetime' => $datetime,
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
              endforeach;
          }

          Cart::where('user_id', $user->id)->delete();
          CartSet::where('user_id', $user->id)->delete();
      }

      return $order;
  }
  /**
   *  get action
   *  get products amount
   */
  private function getAmount($cartProducts) {
      $amount = 0;
      foreach ($cartProducts as $key => $cartProduct):
          if($cartProduct->subproduct) {
              $price = $cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100);

              if($price) {
                $amount +=  $price * $cartProduct->qty;
              }
          } else {
              $price = $cartProduct->product->price - ($cartProduct->product->price * $cartProduct->product->discount / 100);

              if($price) {
                $amount +=  $price * $cartProduct->qty;
              }
          }
      endforeach;
      return $amount;
  }
  /**
   *  get action
   *  get sets amount
   */
  private function getSetsAmount($cartSets) {
      $amount = 0;
      foreach ($cartSets as $key => $cartSet):
        $price = $cartSet->price - ($cartSet->price * $cartSet->set->discount / 100);
        $amount +=  $price * $cartSet->qty;
      endforeach;

      return $amount;
  }
  /**
   *  get action
   *  get products
   */
  private function getCartProducts($id) {
     $rows = Cart::where('user_id', $id)->where('set_id', 0)->get();
     return $rows;
  }
  /**
   *  get action
   *  get sets
   */
  private function getCartSets($id) {
      $rows = CartSet::where('user_id', $id)->get();
      return $rows;
  }

}
