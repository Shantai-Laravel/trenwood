<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderSet;
use App\Models\FrontUserAddress;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Contact;
use App\Models\UserField;
use App\Models\FrontUser;
use App\Models\Cart;
use App\Models\Retur;
use App\Models\ReturProduct;
use App\Models\ReturSet;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use PDF;

class ReturnController extends Controller
{
    public function index() {
      $returns = Retur::where('status', 'new')->where('is_active', 1)->get();
      $userfield = UserField::where('field', 'return_amount_days')->first();

      return view('admin::admin.returns.index', compact('returns', 'userfield'));
    }

    public function changeAmount(Request $request) {
        $this->validate($request, array(
          'returnAmount' => 'required|numeric'
        ));

        $userfield = UserField::where('field', 'return_amount_days')->first();
        $userfield->value = $request->get('returnAmount');
        $userfield->save();

        return redirect()->back();
    }

    public function filterReturns(Request $request)
    {
      $returns = Retur::where('status', $request->get('status'))->get();

      $data['returns'] = view('admin::admin.returns.returns', compact('returns'))->render();

      return json_encode($data);
    }

    public function create() {
        $users = FrontUser::all();

        if(count($users) > 0) {
            $frontuser = $users[0];

            if($frontuser->priorityaddress != 0) {
                $address = $frontuser->addresses()->where('id', $frontuser->priorityaddress)->first();
            } else {
                $address = $frontuser->addresses()->orderBy('id', 'desc')->first();
            }

            $return_amount_days = UserField::where('field', 'return_amount_days')->first()->value;

            $return = Retur::where('user_id', $frontuser->id)->where('is_active', 0)->first();

            if(!empty($return)) {
              $orders_id = $return->returnProducts()->pluck('orderProduct_id')->toArray();
            } else {
              $orders_id = [];
            }

            $orders = Order::where('user_id', $frontuser->id)
                            ->where('datetime', '<=', date('Y-m-d', strtotime('1 days')))
                            ->where('datetime', '>=', date('Y-m-d', strtotime('-'.$return_amount_days.' days')))
                            ->where(function ($query) {
                                $query->where('payment', 'card')->orWhere('status', 'completed');})
                            ->get();

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

            return view('admin::admin.returns.create', compact('users', 'frontuser', 'return', 'orders', 'orders_id', 'address', 'userfields', 'countries', 'regions', 'cities'));

        }

        return 'Для доступа к возвратам создайте пользователя';
    }

    public function filterUsers(Request $request) {
        $frontuser = FrontUser::find($request->get('user_id'));

        if(count($frontuser) > 0) {
            if($frontuser->priorityaddress != 0) {
                $address = $frontuser->addresses()->where('id', $frontuser->priorityaddress)->first();
            } else {
                $address = $frontuser->addresses()->orderBy('id', 'desc')->first();
            }
        }

        $return_amount_days = UserField::where('field', 'return_amount_days')->first()->value;

        $path = array_values(array_slice(explode('/', $request->get('path')), -1))[0];

        if($path == 'create') {
            if($request->get('return_id') != 0) {
              $return = Retur::where('id', $request->get('return_id'))->where('user_id', $frontuser->id)->where('is_active', 0)->first();
            } else {
              $return = Retur::where('user_id', $frontuser->id)->where('is_active', 0)->first();
            }
        } else {
            $return = Retur::where('id', $request->get('return_id'))->where('user_id', $frontuser->id)->where('is_active', 1)->first();
        }

        if(!empty($return)) {
          $orders_id = $return->returnProducts()->pluck('orderProduct_id');
        } else {
          $orders_id = [];
        }

        $orders = Order::where('user_id', $frontuser->id)
                        ->where('datetime', '<=', date('Y-m-d', strtotime('1 days')))
                        ->where('datetime', '>=', date('Y-m-d', strtotime('-'.$return_amount_days.' days')))
                        ->where(function ($query) {
                            $query->where('payment', 'card')->orWhere('status', 'completed');})
                        ->get();

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

        $data['userinfo'] = view('admin::admin.returns.returnBlock', compact('frontuser', 'address', 'return', 'userfields', 'countries', 'regions', 'cities'))->render();
        $data['return'] = view('admin::admin.returns.cartBlock', compact('return'))->render();
        $data['orders'] = view('admin::admin.returns.products', compact('orders', 'orders_id', 'return'))->render();

        return json_encode($data);
    }

    public function filterOrders(Request $request) {
        $orderProduct = OrderProduct::find($request->get('orderProduct_id'));
        if(count($orderProduct) == 0) {
            $orderSet = OrderSet::find($request->get('orderProduct_id'));
            $order = $orderSet->order;
        } else {
            $order = $orderProduct->order;
        }

        $path = array_values(array_slice(explode('/', url()->previous()), -1))[0];

        if($path == 'create') {
            if($request->get('return_id') != 0) {
              $return = Retur::where('id', $request->get('return_id'))->where('user_id', $order->user_id)->where('is_active', 0)->first();
            } else {
              $return = Retur::where('user_id', $order->user_id)->where('is_active', 0)->first();
            }
        } else {
            $return = Retur::where('id', $request->get('return_id'))->where('user_id', $order->user_id)->where('is_active', 1)->first();
        }

        if(empty($return)) {
            $return = Retur::create([
                'user_id' => $order->user_id,
                'is_active' => 0,
                'status' => 'new'
            ]);

            if(!empty($orderSet) && count($orderSet) > 0) {
                $returnSet = $return->returnSets()->create([
                    'set_id' => $orderSet->set_id,
                    'qty' => $orderSet->qty,
                    'price' => $orderSet->price
                ]);

                foreach ($orderSet->orderProduct as $orderProduct) {
                    $return->returnProducts()->create([
                        'orderProduct_id' => $orderProduct->id,
                        'product_id' => $orderProduct->product_id,
                        'subproduct_id' => $orderProduct->subproduct_id,
                        'qty' => $orderProduct->qty,
                        'set_id' => $returnSet->id
                    ]);
                }
            } else {
                $return->returnProducts()->create([
                    'orderProduct_id' => $orderProduct->id,
                    'product_id' => $request->get('product_id'),
                    'subproduct_id' => $request->get('subproduct_id'),
                    'qty' => $orderProduct->qty
                ]);
            }
        } else {
            if(!empty($orderSet) && count($orderSet) > 0) {
                $returnSet = $return->returnSets()->create([
                    'set_id' => $orderSet->set_id,
                    'qty' => $orderSet->qty,
                    'price' => $orderSet->price
                ]);

                foreach ($orderSet->orderProduct as $orderProduct) {
                    $return->returnProducts()->create([
                        'orderProduct_id' => $orderProduct->id,
                        'product_id' => $orderProduct->product_id,
                        'subproduct_id' => $orderProduct->subproduct_id,
                        'qty' => $orderProduct->qty,
                        'set_id' => $returnSet->id
                    ]);
                }
            } else {
                $return->returnProducts()->create([
                    'orderProduct_id' => $orderProduct->id,
                    'product_id' => $request->get('product_id'),
                    'subproduct_id' => $request->get('subproduct_id'),
                    'qty' => $orderProduct->qty
                ]);
            }
        }


        $amount = $this->getAmount($return->returnProducts) + $this->getSetsAmount($return->returnSets);
        $return->amount = $amount;
        $return->save();

        $return_amount_days = UserField::where('field', 'return_amount_days')->first()->value;

        $orders_id = $return->returnProducts()->pluck('orderProduct_id')->toArray();

        $orders = Order::where('user_id', $order->user_id)
                        ->where('datetime', '<=', date('Y-m-d', strtotime('1 days')))
                        ->where('datetime', '>=', date('Y-m-d', strtotime('-'.$return_amount_days.' days')))
                        ->where(function ($query) {
                            $query->where('payment', 'card')->orWhere('status', 'completed');})
                        ->get();

        $data['return'] = view('admin::admin.returns.cartBlock', compact('return'))->render();
        $data['orders'] = view('admin::admin.returns.products', compact('orders', 'orders_id', 'return'))->render();

        return json_encode($data);
    }

    public function store(Request $request) {
        $this->validate($request, array(
          'name' => 'required|min:3',
          'surname' => 'required|min:3',
          'email' => 'required|email',
          'phone' => 'required|min:9|numeric'
        ));

        $returnProducts = $this->getReturnProducts($request->get('return_id'), $request->get('front_user_id'));
        $returnSets = $this->getReturnSets($request->get('return_id'), $request->get('front_user_id'));

        if(count($returnProducts) == 0 && count($returnSets) == 0) {
          return redirect()->back()->withInput()->withErrors(trans('front.cart.empty'));
        }

        if($request->delivery == 'courier') {

          $this->validate($request, array(
            'addressname' => 'required',
            'country'=> 'required'
          ));

          $order = $this->returnProducts($request->all(), $returnProducts);

        } else {

          $this->validate($request, array(
            'addressPickup' => 'required',
            'date' => 'required',
            'time' => 'required'
          ));

          $order = $this->returnProducts($request->all(), $returnProducts);

        }

        return redirect()->route('returns.index');
    }

    public function edit($id)
    {
        $return = Retur::find($id);
        $users = FrontUser::all();
        $frontuser = FrontUser::find($return->user_id);

        $address = $frontuser->addresses()->where('id', $return->address_id)->first();

        if(empty($address)) {
            if($frontuser->priorityaddress != 0) {
                $address = $frontuser->addresses()->where('id', $frontuser->priorityaddress)->first();
            } else {
                $address = $frontuser->addresses()->orderBy('id', 'desc')->first();
            }
        }

        $return_amount_days = UserField::where('field', 'return_amount_days')->first()->value;

        if(!empty($return)) {
          $orders_id = $return->returnProducts()->pluck('orderProduct_id')->toArray();
        } else {
          $orders_id = [];
        }

        $orders = Order::where('user_id', $frontuser->id)
                        ->where('datetime', '<=', date('Y-m-d', strtotime('1 days')))
                        ->where('datetime', '>=', date('Y-m-d', strtotime('-'.$return_amount_days.' days')))
                        ->where(function ($query) {
                            $query->where('payment', 'card')->orWhere('status', 'completed');})
                        ->get();

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

        return view('admin::admin.returns.edit', compact('users', 'frontuser', 'return', 'orders', 'orders_id', 'address', 'userfields', 'countries', 'regions', 'cities'));
    }

    public function destroy($id)
    {
      $return = Retur::find($id);

      $return->returnProducts()->delete();
      $return->returnSets()->delete();
      $return->delete();

      session()->flash('message', 'Item has been deleted!');

    	return redirect()->route('returns.index');
    }

    public function changeQtyPlus(Request $request) {
        $returnProduct = ReturProduct::where('return_id', $request->get('return_id'))->where('orderProduct_id', $request->get('orderProduct_id'))->where('product_id', $request->get('product_id'))->where('subproduct_id', $request->get('subproduct_id'))->first();

        $order = OrderProduct::where('id', $request->get('orderProduct_id'))->where('product_id', $request->get('product_id'))->where('subproduct_id', $request->get('subproduct_id'))->first();
        if (!is_null($returnProduct)) {
            if($returnProduct->qty >= $order->qty) {
                return response()->json(['message' => 'Превышен лимит на добавление товаров'], 400);
            } else {
                ReturProduct::where('id', $returnProduct->id)->update([
                    'qty' => $returnProduct->qty + 1,
                ]);
            }
        }

        $returnProducts = ReturProduct::where('return_id', $returnProduct->return_id)->where('set_id', 0)->get();
        $returnSets = ReturSet::where('return_id', $returnProduct->return_id)->get();
        $amount = $this->getAmount($returnProducts) + $this->getSetsAmount($returnSets);

        $return = Retur::find($returnProduct->return_id);
        $return->amount = $amount;
        $return->save();

        $data['block'] = view('admin::admin.returns.cartBlock', compact('return'))->render();

        return json_encode($data);
    }

    public function changeSetQtyPlus(Request $request) {
        $returnSet = ReturSet::find($request->get('id'));

        if (!is_null($returnSet)) {
            if($returnSet->qty >= $returnSet->returnProducts[0]->orderProduct->orderSet->qty) {
                return response()->json(['message' => 'Превышен лимит на добавление товаров'], 400);
            } else {
                ReturSet::where('id', $returnSet->id)->update([
                    'qty' => $returnSet->qty + 1
                ]);
            }
        }

        $returnProducts = ReturProduct::where('return_id', $returnSet->return_id)->where('set_id', 0)->get();
        $returnSets = ReturSet::where('return_id', $returnSet->return_id)->get();
        $amount = $this->getAmount($returnProducts) + $this->getSetsAmount($returnSets);

        $return = Retur::find($returnSet->return_id);
        $return->amount = $amount;
        $return->save();

        $data['block'] = view('admin::admin.returns.cartBlock', compact('return'))->render();

        return json_encode($data);
    }

    public function changeQtyMinus(Request $request) {
        $returnProduct = ReturProduct::where('return_id', $request->get('return_id'))->where('orderProduct_id', $request->get('orderProduct_id'))->where('product_id', $request->get('product_id'))->where('subproduct_id', $request->get('subproduct_id'))->first();

        if (!is_null($returnProduct)) {
            ReturProduct::where('id', $returnProduct->id)->update([
                'qty' => $returnProduct->qty >= 2 ? $returnProduct->qty - 1 : 1,
            ]);
        }

        $returnProducts = ReturProduct::where('return_id', $returnProduct->return_id)->where('set_id', 0)->get();
        $returnSets = ReturSet::where('return_id', $returnProduct->return_id)->get();
        $amount = $this->getAmount($returnProducts) + $this->getSetsAmount($returnSets);

        $return = Retur::find($returnProduct->return_id);
        $return->amount = $amount;
        $return->save();

        $data['block'] = view('admin::admin.returns.cartBlock', compact('return'))->render();

        return json_encode($data);
    }

    public function changeSetQtyMinus(Request $request) {
        $returnSet = ReturSet::find($request->get('id'));

        if (!is_null($returnSet)) {
            ReturSet::where('id', $returnSet->id)->update([
                'qty' => $returnSet->qty >= 2 ? $returnSet->qty - 1 : 1,
            ]);
        }

        $returnProducts = ReturProduct::where('return_id', $returnSet->return_id)->where('set_id', 0)->get();
        $returnSets = ReturSet::where('return_id', $returnSet->return_id)->get();
        $amount = $this->getAmount($returnProducts) + $this->getSetsAmount($returnSets);

        $return = Retur::find($returnSet->return_id);
        $return->amount = $amount;
        $return->save();

        $data['block'] = view('admin::admin.returns.cartBlock', compact('return'))->render();

        return json_encode($data);
    }

    public function removeOrderItem(Request $request) {
        $returnProduct = ReturProduct::where('return_id', $request->get('return_id'))->where('orderProduct_id', $request->get('orderProduct_id'))->where('product_id', $request->get('product_id'))->where('subproduct_id', $request->get('subproduct_id'))->first();

        if (!is_null($returnProduct)) {
            ReturProduct::where('id', $returnProduct->id)->delete();
        }

        $returnProducts = ReturProduct::where('return_id', $returnProduct->return_id)->where('set_id', 0)->get();
        $returnSets = ReturSet::where('return_id', $returnProduct->return_id)->get();
        $amount = $this->getAmount($returnProducts) + $this->getSetsAmount($returnSets);

        $return = Retur::find($returnProduct->return_id);
        $return->amount = $amount;
        $return->save();

        $return_amount_days = UserField::where('field', 'return_amount_days')->first()->value;

        $orders_id = $return->returnProducts()->pluck('orderProduct_id')->toArray();

        $orders = Order::where('user_id', $return->user_id)
                        ->where('datetime', '<=', date('Y-m-d', strtotime('1 days')))
                        ->where('datetime', '>=', date('Y-m-d', strtotime('-'.$return_amount_days.' days')))
                        ->where(function ($query) {
                            $query->where('payment', 'card')->orWhere('status', 'completed');})
                        ->get();

        $data['orders'] = view('admin::admin.returns.products', compact('orders', 'orders_id', 'return'))->render();
        $data['block'] = view('admin::admin.returns.cartBlock', compact('return'))->render();

        return json_encode($data);
    }

    public function removeOrderSet(Request $request) {
        $returnSet = ReturSet::find($request->get('id'));

        if (!is_null($returnSet)) {
            $returnSet->delete();
            $returnSet->returnProducts()->delete();
        }

        $returnProducts = ReturProduct::where('return_id', $returnSet->return_id)->where('set_id', 0)->get();
        $returnSets = ReturSet::where('return_id', $returnSet->return_id)->get();
        $amount = $this->getAmount($returnProducts) + $this->getSetsAmount($returnSets);

        $return = Retur::find($returnSet->return_id);
        $return->amount = $amount;
        $return->save();

        $return_amount_days = UserField::where('field', 'return_amount_days')->first()->value;

        $orders_id = $return->returnProducts()->pluck('orderProduct_id')->toArray();

        $orders = Order::where('user_id', $return->user_id)
                        ->where('datetime', '<=', date('Y-m-d', strtotime('1 days')))
                        ->where('datetime', '>=', date('Y-m-d', strtotime('-'.$return_amount_days.' days')))
                        ->where(function ($query) {
                            $query->where('payment', 'card')->orWhere('status', 'completed');})
                        ->get();

        $data['orders'] = view('admin::admin.returns.products', compact('orders', 'orders_id', 'return'))->render();
        $data['block'] = view('admin::admin.returns.cartBlock', compact('return'))->render();

        return json_encode($data);
    }

    public function removeAllOrderItems(Request $request) {
        $returnProduct = ReturProduct::where('return_id', $request->get('return_id'))->first();

        if (!is_null($returnProduct)) {
            ReturProduct::where('return_id', $returnProduct->return_id)->delete();
            ReturSet::where('return_id', $returnProduct->return_id)->delete();
        }

        $return = Retur::find($request->get('return_id'));
        $return->amount = 0;
        $return->save();

        $return_amount_days = UserField::where('field', 'return_amount_days')->first()->value;

        $orders_id = [];

        $orders = Order::where('user_id', $return->user_id)
                        ->where('datetime', '<=', date('Y-m-d', strtotime('1 days')))
                        ->where('datetime', '>=', date('Y-m-d', strtotime('-'.$return_amount_days.' days')))
                        ->where(function ($query) {
                            $query->where('payment', 'card')->orWhere('status', 'completed');})
                        ->get();

        $data['orders'] = view('admin::admin.returns.products', compact('orders', 'return', 'orders_id'))->render();
        $data['block'] = view('admin::admin.returns.cartBlock', compact('return'))->render();

        return json_encode($data);
    }

    private function returnProducts($request, $returnProducts) {
      $user = FrontUser::find($request['front_user_id']);

      $amount = $this->getAmount($returnProducts);

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
                      'name' => $request['addressname'],
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

          if($request['return_id'] != 0) {
              $return = Retur::where('id', $request['return_id'])->where('user_id', $user->id)->update([
                  'is_active' => 1,
                  'address_id' => $address_id,
                  'amount' => $amount,
                  'status' => $request['status'],
                  'delivery' => $request['delivery'],
                  'payment' => $request['payment'],
                  'datetime' => $datetime
              ]);
          } else {
              $return = Retur::where('is_active', 0)->where('user_id', $user->id)->update([
                  'is_active' => 1,
                  'address_id' => $address_id,
                  'amount' => $amount,
                  'status' => $request['status'],
                  'delivery' => $request['delivery'],
                  'payment' => $request['payment'],
                  'datetime' => $datetime
              ]);
          }

      }

      return $return;
  }

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

  private function getSetsAmount($cartSets) {
      $amount = 0;
      foreach ($cartSets as $key => $cartSet):
        $price = $cartSet->price - ($cartSet->price * $cartSet->set->discount / 100);
        $amount +=  $price * $cartSet->qty;
      endforeach;

      return $amount;
  }

  private function getReturnProducts($return_id, $user_id) {
     if($return_id != 0) {
        $return = Retur::where('id', $return_id)->where('user_id', $user_id)->first();
     } else {
        $return = Retur::where('is_active', 0)->where('user_id', $user_id)->first();
     }

     if(!empty($return)) {
        $rows = $return->returnProductsNoSet;
     } else {
        $rows = [];
     }
     return $rows;
  }

  private function getReturnSets($return_id, $user_id) {
     if($return_id != 0) {
        $return = Retur::where('id', $return_id)->where('user_id', $user_id)->first();
     } else {
        $return = Retur::where('is_active', 0)->where('user_id', $user_id)->first();
     }

     if(!empty($return)) {
        $rows = $return->returnSets;
     } else {
        $rows = [];
     }
     return $rows;
  }


}
