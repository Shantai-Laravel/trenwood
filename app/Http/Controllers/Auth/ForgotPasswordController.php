<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FrontUser;
use Illuminate\Support\Facades\Request;
use App\Whmcs\Whmcs;

class ForgotPasswordController extends Controller
{
    /**
     *  get action
     *  Render get email page
     */
    public function getEmail()
    {
        return view('auth.front.forgotpassword');
    }
    /**
     *  post action
     *  Send code to email
     */
    public function postEmail()
    {
        $this->validate(request(), [
            'email' => 'required|email',
        ]);

        $user = FrontUser::where('email', request('email'))->first();

        if(count($user) > 0) {
          session()->put(['code' => str_random(10), 'user_id' => $user->id]);

          $to = request('email');
          $subject = trans('front.forgotPass.subject');
          $message = view('front.emailTemplates.forgotPassword', compact('user'))->render();
          $headers  = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

          mail($to, $subject, $message, $headers);

          return redirect()->route('password.code')->withSuccess(trans('front.forgotPass.codeMessage'));
        } else {
          return back()->withErrors(['invalidEmail' => [trans('front.forgotPass.email')]]);
        }
    }
    /**
     *  get action
     *  Render get code page
     */
    public function getCode()
    {
        return view('auth.front.code');
    }
    /**
     *  post action
     *  Check code
     */
    public function postCode()
    {
        $this->validate(request(), [
            'code' => 'required|in:'.session('code')
        ]);

        return redirect()->route('password.reset')->withSuccess(trans('front.forgotPass.codeSuccess'));
    }
    /**
     *  get action
     *  Render get resetpassword page
     */
    public function getReset()
    {
        return view('auth.front.resetpassword');
    }
    /**
     *  post action
     *  Reset password
     */
    public function postReset()
    {
        $this->validate(request(), [
            'password' => 'required|min:3',
            'passwordRepeat' => 'required|same:password',
        ]);

        $user = FrontUser::find(session('user_id'));

        if(count($user) > 0) {
          $user->password = bcrypt(request('password'));
          $user->remember_token = request('_token');
          $user->save();

          session()->forget('code');
          session()->forget('user_id');

          return redirect()->route('front.login')->withSuccess(trans('front.forgotPass.pass'));
        } else {
          return redirect()->route('404')->send();
        }
    }
}
