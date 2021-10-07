<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
  /**
   *  get action
   *  Render contacts page
   */
    public function index(Request $request) {
        return view('front.pages.contacts');
    }
    /**
     *  post action
     *  Mail to user
     */
    public function feedBack(Request $request) {
        $toValidate['fullname'] = 'required|min:4';
        $toValidate['email'] = 'required|email';
        $toValidate['phone'] = 'required';
        $toValidate['message'] = 'required';

        $validator = $this->validate(request(), $toValidate);

        $emailAdmin = getContactInfo('emailadmin');
        $to = [];

        foreach ($emailAdmin->translationByLanguage($this->lang->id)->get() as $email) {
          $to[] = $email->value;
        }

        $to = implode($to, ',');

        $subject = trans('front.contacts.subject');

        // $message = $request->fullname.' '.$request->email.''.$request->phone;

        $message = '<p>Name - '. $request->fullname. '</p>';
        $message .= '<p>Email - '. $request->email. '</p>';
        $message .= '<p>Telefon - '. $request->phone. '</p>';
        $message .= '<p>Message - '. $request->message. '</p>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        mail($to, $subject, $message, $headers);

        return redirect()->back()->withSuccess(trans('front.contacts.success'));
    }
}
