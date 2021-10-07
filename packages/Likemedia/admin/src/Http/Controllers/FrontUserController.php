<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FrontUser;
use App\Models\FrontUserAddress;
use App\Models\UserField;
use App\Models\Order;
use App\Models\Retur;
use Illuminate\Support\Facades\Hash;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;

class FrontUserController extends Controller
{
  public function index()
  {
    $users = FrontUser::orderBy('created_at', 'desc')->get();

    return view('admin::admin.frontusers.index', compact('users'));
  }

  public function create()
  {
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

    return view('admin::admin.frontusers.create', compact('userfields', 'countries'));
  }

  public function filterByCountries(Request $request)
  {
      $locationItems = Region::where('location_country_id', $request->get('value'))->get();

      if(!empty($request->get('address_id'))) {
          $address = FrontUserAddress::find($request->get('address_id'));
          $data['regions'] = view('admin::admin.frontusers.options', compact('locationItems', 'address'))->render();
      } else {
          $data['regions'] = view('admin::admin.frontusers.options', compact('locationItems'))->render();
      }

      return json_encode($data);
  }

  public function filterByRegions(Request $request) {
      $locationItems = City::where('location_region_id', $request->get('value'))->get();

      if(!empty($request->get('address_id'))) {
          $address = FrontUserAddress::find($request->get('address_id'));
          $data['cities'] = view('admin::admin.frontusers.options', compact('locationItems', 'address'))->render();
      } else {
          $data['cities'] = view('admin::admin.frontusers.options', compact('locationItems'))->render();
      }

      return json_encode($data);
  }

  public function store(Request $request)
  {
    $toValidate = [];

    $uniquefields = UserField::where('in_register', 1)->where('unique_field', 1)->where('required_field', 1)->get();

    if(count($uniquefields) > 0) {
        foreach ($uniquefields as $uniquefield) {
            if($uniquefield->field == 'email') {
                $toValidate[$uniquefield->field] = 'required|unique:front_users|email';
            } else {
                $toValidate[$uniquefield->field] = 'required|unique:front_users';
            }
        }
    }

    $requiredfields = UserField::where('in_register', 1)->where('required_field', 1)->where('unique_field', 0)->get();

    if(count($requiredfields) > 0) {
        foreach ($requiredfields as $requiredfield) {
            if($requiredfield->field == 'name' || $requiredfield->field == 'surname') {
                $toValidate[$requiredfield->field] = 'required|min:3';
            } else {
                $toValidate[$requiredfield->field] = 'required';
            }
        }
    }

    $toValidate['password'] = 'required|min:4';
    $toValidate['repeatpassword'] = 'required|same:password';

    if(strlen($request->addressname) > 0) {
      $toValidate['addressname'] = 'required|min:4';
      $toValidate['country'] = 'required';
      $toValidate['region'] = 'required';
      $toValidate['location'] = 'required';
      $toValidate['address'] = 'required';
    }

    $validator = $this->validate($request, $toValidate);

    $user = FrontUser::create([
        'is_authorized' => 0,
        'lang' => 1,
        'name' => $request->name,
        'surname' => $request->surname,
        'email' => $request->email,
        'phone' => $request->phone,
        'birthday' => $request->birthday == '' ? null: $request->birthday,
        'terms_agreement' => $request->terms_agreement == 'on' ? 1 : 0,
        'promo_agreement' => $request->promo_agreement == 'on' ? 1 : 0,
        'personaldata_agreement' => $request->personaldata_agreement == 'on' ? 1 : 0,
        'password' => bcrypt($request->password),
        'remember_token' => $request->_token
    ]);

    if(strlen($request->addressname) >= 4) {
        $address = $user->addresses()->create([
            'addressname' => $request->addressname,
            'country' => $request->country,
            'region' => $request->region,
            'location' => $request->location,
            'address' => $request->address,
            'code' => $request->code,
            'homenumber' => $request->homenumber,
            'entrance' => $request->entrance,
            'floor' => $request->floor,
            'apartment' => $request->apartment,
            'comment' => $request->comment
        ]);
    }

    session()->flash('message', 'User has been created!');

    return redirect()->route('frontusers.index')->withInput();
  }

  public function edit($id)
  {
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

    $user = FrontUser::findOrFail($id);

    if(!empty($user->addresses()->get())) {
        foreach ($user->addresses()->get() as $address) {
            $regions[] = Region::where('location_country_id', $address->country)->get();
            $cities[] = City::where('location_region_id', $address->region)->get();
        }
    }

    return view('admin::admin.frontusers.edit', compact('user', 'countries', 'regions', 'cities', 'userfields'));
  }

  public function update(Request $request, $id)
  {
    $toValidate = [];

    $requiredfields = UserField::where('in_register', 1)->where('required_field', 1)->get();

    if(count($requiredfields) > 0) {
        foreach ($requiredfields as $requiredfield) {
            $toValidate[$requiredfield->field] = 'required';
        }
    }

    $validator = $this->validate($request, $toValidate);

    $user = FrontUser::find($id);

    $user->name = $request->name;
    $user->surname = $request->surname;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->birthday = $request->birthday == '' ? null: $request->birthday;
    $user->terms_agreement = $request->terms_agreement == 'on' ? 1 : 0;
    $user->promo_agreement = $request->promo_agreement == 'on' ? 1 : 0;
    $user->personaldata_agreement = $request->personaldata_agreement == 'on' ? 1 : 0;
    $user->company = $request->company;
    $user->companyaddress = $request->companyaddress;
    $user->fisc = $request->fisc;
    $user->priorityaddress = $request->priorityaddress;
    $user->save();

    $address = $user->priorityAddress()->first();

    session()->flash('message', 'User has been updated!');

    return redirect()->route('frontusers.index');
  }

  public function addAddress(Request $request, $id) {
      $toValidate = [];

      $requiredfields = UserField::whereIn('id', $request->userfield_id)->where('required_field', 1)->get();
      if(count($requiredfields) > 0) {
        foreach ($requiredfields as $requiredfield) {
            $toValidate[$requiredfield->field] = 'required';
        }
      }

      $validator = $this->validate($request, $toValidate);

      $user = FrontUser::find($id);

      $maxaddress = UserField::where('field', 'maxaddress')->first();

      if(count($user->addresses()->get()) >= $maxaddress->value) {
          session()->flash('deleteAddresses', $userdata->addresses()->get());
          return redirect()
                  ->back()
                  ->withErrors(trans('front.cabinet.myaddresses.maxaddress').' '.$maxaddress->value.'. '.trans('front.cabinet.myaddresses.deleteaddress'));
      }

      $address = $user->addresses()->create([
          'addressname' => $request->addressname,
          'country' => $request->country,
          'region' => $request->region,
          'location' => $request->location,
          'address' => $request->address,
          'code' => $request->code,
          'homenumber' => $request->homenumber,
          'entrance' => $request->entrance,
          'floor' => $request->floor,
          'apartment' => $request->apartment,
          'comment' => $request->comment
      ]);

      return redirect()->back()->withInput()->withSuccess(trans('front.success'));
  }

  public function updateAddress(Request $request, $user_id, $address_id) {
      $toValidate = [];

      $requiredfields = UserField::whereIn('id', $request->userfield_id)->where('required_field', 1)->get();

      if(count($requiredfields) > 0) {
        foreach ($requiredfields as $requiredfield) {
            $toValidate[$requiredfield->field] = 'required';
        }
      }

      $validator = $this->validate($request, $toValidate);

      $user = FrontUser::find($user_id);

      $user->addresses()->where('id', $address_id)->update([
          'addressname' => $request->addressname,
          'country' => $request->country,
          'region' => $request->region,
          'location' => $request->location,
          'address' => $request->address,
          'code' => $request->code,
          'homenumber' => $request->homenumber,
          'entrance' => $request->entrance,
          'floor' => $request->floor,
          'apartment' => $request->apartment,
          'comment' => $request->comment
      ]);

      return redirect()->back()->withInput()->withSuccess(trans('front.success'));
  }

  public function deleteAddress($user_id, $id)
  {
    $user = FrontUser::findOrFail($user_id);
    $user->priorityaddress = 0;
    $user->save();

    $user->addresses()->where('id', $id)->delete();

    session()->flash('message', 'User has been deleted!');

    return redirect()->back()->withInput()->withSuccess(trans('front.success'));
  }

  public function editPassword($id)
  {
    $user = FrontUser::findOrFail($id);
    return view('admin::admin.frontusers.editPassword', compact('user'));
  }

  public function updatePassword(Request $request, $id)
  {
    $toValidate = [];
    $toValidate['oldpassword'] = 'required';
    $toValidate['newpassword'] = 'required|min:4';
    $toValidate['repeatpassword'] = 'required|same:newpassword';

    $validator = $this->validate($request, $toValidate);

    $user = FrontUser::where('id', $id)->first();

    if (!Hash::check($request->oldpassword, $user->password)) {
        return redirect()->back()->withInput()->withErrors('Incorrect old password');
    }

    $user->password = bcrypt($request->newpassword);
    $user->save();

    session()->flash('message', 'Password has been updated!');

    return redirect()->route('frontusers.index');
  }

  public function destroy($id)
  {
    $user = FrontUser::findOrFail($id);

    $user->delete();
    $user->addresses()->delete();
    $orders = Order::where('user_id', $id)->get();
    $returns = Retur::where('user_id', $id)->get();

    if(count($orders) > 0) {
        foreach ($orders as $order) {
            $order->orderProducts()->delete();
            $order->delete();
        }
    }

    if(count($returns) > 0) {
        foreach ($returns as $return) {
            $return->returnProducts()->delete();
            $return->delete();
        }
    }

    session()->flash('message', 'User has been deleted!');

    return redirect()->route('frontusers.index');
  }
}
