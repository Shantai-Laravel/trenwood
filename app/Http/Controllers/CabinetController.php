<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FrontUser;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderSet;
use App\Models\Cart;
use App\Models\CartSet;
use App\Models\UserField;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use App\Models\Retur;
use App\Models\ReturProduct;
use App\Models\ReturSet;
use App\Models\WishList;

class CabinetController extends Controller
{
  /**
     *  get action
     *  Render personal data page
     */
    public function index() {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $userfields = UserField::where('in_cabinet', 1)->get();

        $maxaddress = UserField::where('field', 'maxaddress')->first();

        $countries = UserField::where('field', 'countries')->first();

        if(count($countries) > 0 && $countries->value != '') {
          $countries = Country::whereIn('id', json_decode($countries->value))->get();
        } else {
          $countries = Country::all();
        }

        if(!empty($userdata->addresses()->get())) {
            foreach ($userdata->addresses()->get() as $address) {
                $regions[] = Region::where('location_country_id', $address->country)->get();
                $cities[] = City::where('location_region_id', $address->region)->get();
            }
        }

        return view('front.cabinet.personalData', compact('userdata', 'userfields', 'maxaddress', 'countries', 'regions', 'cities'));
    }
    /**
     *  post action
     *  Change user data
     */
    public function savePersonalData(Request $request) {

        $this->validate($request, array(
          'name' => 'required|min:3',
          'surname' => 'required|min:3',
          'email' => 'required|email',
          'phone' => 'required'
        ));

        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $userdata->name = $request->name;
        $userdata->surname = $request->surname;
        $userdata->phone = $request->phone;
        $userdata->company = $request->company;
        $userdata->email = $request->email;
        $userdata->birthday = $request->birthday;
        $userdata->save();

        return redirect()->back()->withSuccess(trans('front.cabinet.success'));
    }
    /**
     *  post action
     *  Change user password
     */
    public function savePass(Request $request) {
        $this->validate($request, array(
          'oldpass' => 'required|min:3',
          'newpass' => 'required|min:3',
          'repeatnewpass' => 'required|same:newpass'
        ));

        $user = FrontUser::where('id', auth('persons')->id())->first();

        if (!Hash::check($request->oldpass, $user->password)) {
            return redirect()->back()->withErrors(trans('front.cabinet.changePass.error'));
        }

        $user->password = bcrypt($request->newpass);
        $user->save();

        return redirect()->back()->withSuccess(trans('front.cabinet.success'));
    }
    /**
     *  post action
     *  Filter regions by country
     */
    public function filterByCountries(Request $request)
    {
        $locationItems = Region::where('location_country_id', $request->get('value'))->get();

        if(!empty($request->get('address_id'))) {
            $address = FrontUserAddress::find($request->get('address_id'));
            $data['regions'] = view('front.inc.options', compact('locationItems', 'address'))->render();
        } else {
            $data['regions'] = view('front.inc.options', compact('locationItems'))->render();
        }

        return json_encode($data);
    }
    /**
     *  post action
     *  Filter locations by region
     */
    public function filterByRegions(Request $request) {
        $locationItems = City::where('location_region_id', $request->get('value'))->get();

        if(!empty($request->get('address_id'))) {
            $address = FrontUserAddress::find($request->get('address_id'));
            $data['cities'] = view('front.inc.options', compact('locationItems', 'address'))->render();
        } else {
            $data['cities'] = view('front.inc.options', compact('locationItems'))->render();
        }

        return json_encode($data);
    }
    /**
     *  post action
     *  Create user address
     */
    public function addAddress() {
        $toValidate = [];

        $requiredfields = UserField::where('in_cabinet', 1)->where('required_field', 1)->where('field_group', 'address')->get();

        if(count($requiredfields) > 0) {
            foreach ($requiredfields as $requiredfield) {
                $toValidate[$requiredfield->field] = 'required';
            }
        }

        $validator = $this->validate(request(), $toValidate);

        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $maxaddress = UserField::where('field', 'maxaddress')->first();

        if(count($userdata->addresses()->get()) >= $maxaddress->value) {
            session()->flash('deleteAddresses', $userdata->addresses()->get());
            return redirect()
                    ->back()
                    ->withErrors(trans('front.cabinet.address.maxaddress', ['maxaddress' => $maxaddress->value]));
        }

        $userdata->addresses()->create([
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

        return redirect()->back()->withInput()->withSuccess(trans('front.cabinet.success'));
    }
    /**
     *  post action
     *  Update user address
     */
    public function saveAddress($id) {
        $toValidate = [];

        $requiredfields = UserField::where('in_cabinet', 1)->where('required_field', 1)->where('field_group', 'address')->get();

        if(count($requiredfields) > 0) {
            foreach ($requiredfields as $requiredfield) {
                $toValidate[$requiredfield->field] = 'required';
            }
        }

        $validator = $this->validate(request(), $toValidate);

        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $address = $userdata->addresses()->where('id', $id)->first();

        if(count($address) > 0) {
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
        }

        return redirect()->back()->withSuccess(trans('front.cabinet.success'));
    }
    /**
     *  post action
     *  Delete user address
     */
    public function deleteAddress(Request $request, $id) {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $address = $userdata->addresses()->where('id', $id)->first();

        if(count($address) > 0) {
          $address->delete();
        } else {
          return redirect()->back()->withErrors(trans('front.cabinet.address.addressNotExist'));
        }

        if($request->ajax()){
            $request->session()->flash('success', trans('front.cabinet.address.deleteAddress'));
            return response()->json(['message' => 'success'], 200);
        }
        return redirect()->back()->withSuccess(trans('front.cabinet.address.deleteAddress'));
    }
    /**
     *  post action
     *  Set priority address
     */
    public function priorityAddress() {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $address = $userdata->addresses()->where('id', request('priorityAddress'))->first();
        if(count($address) > 0) {
            $userdata->priorityaddress = request('priorityAddress');
            $userdata->save();
        } else {
          return redirect()->back()->withErrors(trans('front.cabinet.address.addressNotExist'));
        }

        return redirect()->back()->withSuccess(trans('front.cabinet.address.priorityAddressSuccess'));
    }
    /**
     *  get action
     *  Render get history page
     */
    public function history() {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $orders = Order::where('user_id', auth('persons')->id())->where('is_logged', 1)->get();

        return view('front.cabinet.history', compact('userdata', 'orders'));
    }
    /**
     *  post action
     *  Add order in cart
     */
    public function historyCart($id) {
        $order = Order::find($id);

        foreach ($order->orderSets as $orderSet):
            $cartSet = CartSet::create([
                'set_id' => $orderSet->set_id,
                'user_id' => $order->user_id,
                'qty' => $orderSet->qty,
                'price' => $orderSet->price,
                'is_logged' => 1
            ]);

            foreach ($orderSet->orderProduct as $orderProduct):
                Cart::create([
                  'product_id' => $orderProduct->product_id,
                  'subproduct_id' => $orderProduct->subproduct_id,
                  'user_id' => $order->user_id,
                  'qty' => $orderProduct->qty,
                  'is_logged' => 1,
                  'set_id' => $cartSet->id
                ]);
            endforeach;
        endforeach;

        foreach ($order->orderProductsNoSet as $order):
            Cart::create([
              'product_id' => $order->product_id,
              'subproduct_id' => $order->subproduct_id,
              'user_id' => auth('persons')->id(),
              'qty' => $order->qty,
              'is_logged' => 1
            ]);
        endforeach;

        return redirect()->route('cart')->withSuccess(trans('front.cabinet.historyAll.historyCart'));
    }
    /**
     *  get action
     *  Render history one page
     */
    public function historyOrder($id) {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();
        $order = Order::find($id);

        return view('front.cabinet.historyOpen', compact('userdata', 'order'));
    }
    /**
     *  post action
     *  Add history set in cart
     */
    public function historyCartSet($id) {
        $orderSet = OrderSet::find($id);

        $cartSet = CartSet::create([
            'set_id' => $orderSet->set_id,
            'user_id' => $orderSet->order->user_id,
            'qty' => $orderSet->qty,
            'price' => $orderSet->price,
            'is_logged' => 1
        ]);

        foreach ($orderSet->orderProduct as $orderProduct):
            Cart::create([
              'product_id' => $orderProduct->product_id,
              'subproduct_id' => $orderProduct->subproduct_id,
              'user_id' => $orderSet->order->user_id,
              'qty' => $orderSet->qty,
              'is_logged' => 1,
              'set_id' => $cartSet->id
            ]);
        endforeach;

        return redirect()->route('cart')->withSuccess(trans('front.cabinet.historyAll.historyCartSet'));
    }
    /**
     *  post action
     *  Add history product in cart
     */
    public function historyCartProduct($id) {
        $order = OrderProduct::find($id);

        Cart::create([
          'product_id' => $order->product_id,
          'subproduct_id' => $order->subproduct_id,
          'user_id' => auth('persons')->id(),
          'qty' => $order->qty,
          'is_logged' => 1
        ]);

        return redirect()->route('cart')->withSuccess(trans('front.cabinet.historyAll.historyCartProduct'));
    }
    /**
     *  get action
     *  Render return page
     */
    public function return() {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $return_amount_days = UserField::where('field', 'return_amount_days')->first()->value;

        $orders = Order::where('user_id', auth('persons')->id())
                        ->where('is_logged', 1)
                        ->where('datetime', '<=', date('Y-m-d', strtotime('1 days')))
                        ->where('datetime', '>=', date('Y-m-d', strtotime('-'.$return_amount_days.' days')))
                        ->where(function ($query) {
                            $query->where('payment', 'card')->orWhere('status', 'completed');})
                        ->get();

        return view('front.cabinet.return', compact('userdata', 'orders', 'return_amount_days'));
    }
    /**
     *  get action
     *  Render return one page
     */
    public function returnOrder($id) {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();
        $order = Order::find($id);
        $orderProductsId = $order->orderProducts()->pluck('id')->toArray();

        $returnProducts = ReturProduct::whereIn('orderProduct_id', $orderProductsId)->get();
        if(count($returnProducts) > 0) {
            $return = Retur::find($returnProducts[0]->return_id);
        } else {
            $return = [];
        }

        return view('front.cabinet.returnOpen', compact('userdata', 'order', 'return'));
    }
    /**
     *  post action
     *  Add products to return
     */
    public function addProductsToReturn($id) {
        $orderProduct = OrderProduct::find($id);
        if(count($orderProduct) > 0) {
            if(request('returnOrder') == 1) {
                if(request('return_id') != 0) {
                    $return = Retur::find(request('return_id'));

                    if(count($return) > 0) {
                        $return->returnProducts()->create([
                            'orderProduct_id' => $orderProduct->id,
                            'product_id' => $orderProduct->product_id,
                            'subproduct_id' => $orderProduct->subproduct_id,
                            'qty' => $orderProduct->qty
                        ]);
                    }
                } else {
                     $return = Retur::create([
                         'user_id' => auth('persons')->id(),
                         'is_active' => 0,
                         'status' => 'new'
                     ]);

                     $return->returnProducts()->create([
                         'orderProduct_id' => $orderProduct->id,
                         'product_id' => $orderProduct->product_id,
                         'subproduct_id' => $orderProduct->subproduct_id,
                         'qty' => $orderProduct->qty
                     ]);
                }

                if(count($return) > 0) {
                    $return->amount = $this->getAmount($return->returnProductsNoSet) + $this->getSetsAmount($return->returnSets);
                    $return->save();
                }

                return redirect()->back()->withSuccess(trans('front.cabinet.returnAll.addSuccess'));

            } else {
                 $returnProducts = ReturProduct::where('return_id', request('return_id'))->get();
                 $return = Retur::find($returnProducts[0]->return_id);

                 if(count($returnProducts) > 1 && count($return) > 0) {
                     ReturProduct::where('orderProduct_id', $orderProduct->id)->where('product_id', $orderProduct->product_id)->delete();

                     $return->amount = $this->getAmount($return->returnProductsNoSet) + $this->getSetsAmount($return->returnSets);
                     $return->save();
                 } else {
                     $return->delete();
                     $return->returnProducts()->delete();
                 }

                 return redirect()->back()->withSuccess(trans('front.cabinet.returnAll.deleteSuccess'));
            }
        }
    }
    /**
     *  post action
     *  Add sets to return
     */
    public function addSetsToReturn($id) {
        $orderSet = OrderSet::find($id);
        if(count($orderSet) > 0) {
            if(request('returnOrder') == 1) {
                if(request('return_id') != 0) {
                    $return = Retur::find(request('return_id'));

                    if(count($return) > 0) {
                        $returnSet = $return->returnSets()->create([
                            'set_id' => $orderSet->set_id,
                            'qty' => $orderSet->qty,
                            'price' => $orderSet->price
                        ]);

                        foreach ($orderSet->orderProduct as $orderProduct):
                            $return->returnProducts()->create([
                                'orderProduct_id' => $orderProduct->id,
                                'product_id' => $orderProduct->product_id,
                                'subproduct_id' => $orderProduct->subproduct_id,
                                'qty' => $orderProduct->qty,
                                'set_id' => $returnSet->id
                            ]);
                        endforeach;
                    }
                } else {
                     $return = Retur::create([
                         'user_id' => auth('persons')->id(),
                         'is_active' => 0,
                         'status' => 'new'
                     ]);

                     $returnSet = $return->returnSets()->create([
                         'set_id' => $orderSet->set_id,
                         'qty' => $orderSet->qty,
                         'price' => $orderSet->price
                     ]);

                     foreach ($orderSet->orderProduct as $orderProduct):
                         $return->returnProducts()->create([
                             'orderProduct_id' => $orderProduct->id,
                             'product_id' => $orderProduct->product_id,
                             'subproduct_id' => $orderProduct->subproduct_id,
                             'qty' => $orderProduct->qty,
                             'set_id' => $returnSet->id
                         ]);
                     endforeach;
                }

                if(count($return) > 0) {
                    $return->amount = $this->getAmount($return->returnProductsNoSet) + $this->getSetsAmount($return->returnSets);
                    $return->save();
                }

                return redirect()->back()->withSuccess(trans('front.cabinet.returnAll.addSuccess'));

            } else {
                 $returnSets = ReturSet::where('return_id', request('return_id'))->get();
                 foreach ($returnSets as $returnSet):
                    $returnSet->delete();
                    $returnSet->returnProducts()->delete();

                    if(count($returnSet->return->returnProducts) == 0) {
                        $returnSet->return->delete();
                    } else {
                        $returnSet->return->amount = $this->getAmount($returnSet->return->returnProductsNoSet) + $this->getSetsAmount($returnSet->return->returnSets);
                        $returnSet->return->save();
                    }
                 endforeach;

                 return redirect()->back()->withSuccess(trans('front.cabinet.returnAll.deleteSuccess'));
            }
        }
    }
    /**
     *  post action
     *  Change info about return
     */
    public function saveReturn($id) {
        if($id != 0) {
            $return = Retur::find($id);
            $orderProduct = OrderProduct::find($return->returnProducts()->first()->orderProduct_id);
            $order = $orderProduct->order;
            $return->motive = request('motive');
            $return->payment = request('payment');
            $return->is_active = 1;
            $return->address_id = $order->address_id;
            $return->delivery = $order->delivery;
            $return->amount = $this->getAmount($return->returnProductsNoSet) + $this->getSetsAmount($return->returnSets);
            $return->save();

            $to = auth('persons')->user()->email;

            $subject = trans('front.cabinet.returnAll.subject');

            $message = trans('front.cabinet.returnAll.message');
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text; charset=utf-8' . "\r\n";

            mail($to, $subject, $message, $headers);

            return redirect()->back()->withSuccess(trans('front.cabinet.success'));
        } else {
            return redirect()->back()->withErrors(trans('front.cabinet.returnAll.error'));
        }
    }
    /**
     *  private method
     *  get products amount
     */
    private function getAmount($cartProducts) {
        $amount = 0;
        foreach ($cartProducts as $key => $cartProduct):
            $price = $cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100);

            if($price) {
              $amount +=  $price * $cartProduct->qty;
            }
        endforeach;
        return $amount;
    }
    /**
     *  private method
     *  get sets amount
     */
    private function getSetsAmount($cartSets) {
        $amount = 0;
        foreach ($cartSets as $key => $cartSet):
          $amount +=  $cartSet->price * $cartSet->qty;
        endforeach;

        return $amount;
    }
    /**
     *  get action
     *  Render wishlist page
     */
    public function wishList() {

        $wishlistProducts = WishList::where('user_id', auth('persons')->id())->get();

        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        return view('front.cabinet.wishList', compact('wishlistProducts', 'userdata'));
    }


}
