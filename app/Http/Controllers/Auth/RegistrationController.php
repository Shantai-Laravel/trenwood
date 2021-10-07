<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FrontUser;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Models\UserField;
use App\Whmcs\Whmcs;

class RegistrationController extends Controller
{
  /**
   *  get action
   *  Render register page
   */
    public function create()
    {
        $userfields = UserField::where('in_register', 1)->get();

        return view('auth.front.register', compact('userfields'));
    }
    /**
     *  post action
     *  Register user
     */
    public function store()
    {
        $toValidate = [];

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
        $toValidate['passwordRepeat'] = 'required|same:password';

        $validator = $this->validate(request(), $toValidate);

        $user = FrontUser::create([
            'is_authorized' => 0,
            'lang' => 1,
            'name' => request('name') ? request('name') : '',
            'surname' => request('surname') ? request('surname') : '',
            'email' => request('email') ? request('email') : '',
            'phone' => request('phone') ? request('phone') : '',
            'password' => request('password') ? bcrypt(request('password')) : '',
            'terms_agreement' => request('terms_agreement') ? request('terms_agreement') : 0,
            'promo_agreement' => request('promo_agreement') ? request('promo_agreement') : 0,
            'personaldata_agreement' => request('personaldata_agreement') ? request('personaldata_agreement') : 0,
            'remember_token' => request('_token')
        ]);

        $password = request('password');

        session()->put(['token' => str_random(60), 'user_id' => $user->id]);

        $to = request('email');
        $subject = trans('front.registration.subject');
        $message = view('front.emailTemplates.register', compact('user', 'password'))->render();
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        mail($to, $subject, $message, $headers);

        Auth::guard('persons')->login($user);

        if(!empty(request('prev'))) {
          return redirect(request('prev'))->withSuccess(trans('front.registration.success'));
        } else {
          return redirect()->back()->withSuccess(trans('front.registration.success'));
        }
    }
    /**
     *  get action
     *  Set that user is authorized
     */
    public function authorizeUser($token) {
        if($token == session('token')) {
            $user = FrontUser::find(session('user_id'));

            if(count($user) > 0) {
              session()->forget('token');
              session()->forget('user_id');

              $user->is_authorized = 1;
              $user->save();

              return redirect()->route('home');
            } else {
              return redirect()->route('404')->send();
            }
        } else {
            return redirect()->route('404')->send();
        }
    }
    /**
     *  post action
     *  Change user password, after registration in cart page
     */
    public function changePass($token) {
        if($token == session('token')) {
            $user = FrontUser::find(session('user_id'));

            if(count($user) > 0) {
              $user->is_authorized = 1;
              $user->save();

              return redirect()->route('password.reset');
            } else {
              return redirect()->route('404')->send();
            }
        } else {
            return redirect()->route('404')->send();
        }
    }
}
