@extends('front.app')
@section('content')
@include('front.layouts.header')
<div class="collectionOne registration">
  <div class="container">
    <div class="row">
       <div class="col-auto">
          <div class="crumbs">
             <ul>
               <li><a href="{{url($lang->lang)}}">{{trans('front.general.homePage')}}</a> / </li>
               <li><a href="{{url($lang->lang.'/password/code')}}">{{trans('front.forgotPass.code')}}</a></li>
             </ul>
          </div>
       </div>
    </div>
    <div class="row">
       <div class="col-12">
                    <h3>{{trans('front.forgotPass.reset')}}</h3>
                 </div>
       <div class="col-12">
         <div class="registrationRet registrationRetMob">
          <a href="{{ url($lang->lang.'/login/facebook') }}"><img src="{{asset('fronts/img/icons/faceLog.svg')}}" alt="">{{trans('front.general.facebook')}}</a>
          <a href="{{ url($lang->lang.'/login/google') }}"><img src="{{asset('fronts/img/icons/googleLog.svg')}}" alt="">{{trans('front.general.google')}}</a>
         </div>
                 </div>
       <div class="col-lg-3 col-md-6 col-sm-8 col-12 aboutEstel">
         <h4>{{trans('front.general.aboutTrenwood')}}</h4>
         <ul>
           <li><a href="{{url($lang->lang.'/about')}}">{{trans('front.general.about')}}</a></li>
           <li><a href="{{url($lang->lang.'/conditions')}}">{{trans('front.general.conditions')}}</a></li>
           <li><a href="{{url($lang->lang.'/cookiePolicy')}}">{{trans('front.general.cookiePolicy')}}</a></li>
           <li><a href="{{url($lang->lang.'/privacyPolicy')}}">{{trans('front.general.privacyPolicy')}}</a></li>
         </ul>
                 </div>
       <div class="col-lg-6 col-sm-8 col-12 regBoxBorder">
           @if ($errors->has('invalidEmail'))
             <div class="row">
                <div class="col-12">
                   <div class="errorPassword">
                     <p><strong>{{trans('front.general.error')}}</strong></p>
                      <p>{!!$errors->first('invalidEmail')!!}</p>
                   </div>
                </div>
             </div>
           @endif

           @if (Session::has('success'))
               <div class="row">
                  <div class="col-12">
                     <div class="errorPassword">
                        <p>{{ Session::get('success') }}</p>
                     </div>
                  </div>
               </div>
           @endif
              <div class="regBox">
                 <div class="row">
                    <div class="col-12">
                       <h4>{{trans('front.forgotPass.reset')}}</h4>
                    </div>
                 </div>
                 <div class="row">
                   <div class="col-12">
                     <p>{{trans('front.forgotPass.text')}}</p>
                   </div>
                 </div>
                 <form action="{{ url()->current() }}" method="post">
                   {{ csrf_field() }}
                   <div class="row justify-content-center align-items-center">
                     <div class="col-md-7">
                       @if ($errors->has('code'))
                          <div class="invalid-feedback" style="display: block">
                            {!!$errors->first('code')!!}
                          </div>
                       @endif
                       <input type="text" class="{{$errors->has('code') ? 'validationError': ''}}" placeholder="{{trans('front.fields.code')}}*" name="code" style="padding: 5px;">
                     </div>
                     <div class="col-md-5 col-sm-6 col-12">
                         <input class="btnSubmit" type="submit" value="{{trans('front.forgotPass.reset')}}">
                     </div>
                   </div>
                 </form>
                 <div class="row ">
                   <div class="col-12 passError">
                     <p><b>{{trans('front.forgotPass.question')}}</b></p>
                     <p>{{trans('front.forgotPass.answer')}}</p>
                   </div>
                 </div>
              </div>
           </div>
       <div class="col-lg-3 col-md-6 col-sm-8 col-12 aboutEstel">
         <h4>{{trans('front.general.advantages')}}</h4>
         <div>{{trans('front.general.motive')}}</div>
         <ol>
           <li><a href="{{url($lang->lang.'/about')}}">{{trans('front.general.about')}}</a></li>
           <li><a href="{{url($lang->lang.'/conditions')}}">{{trans('front.general.conditions')}}</a></li>
           <li><a href="{{url($lang->lang.'/cookiePolicy')}}">{{trans('front.general.cookiePolicy')}}</a></li>
           <li><a href="{{url($lang->lang.'/privacyPolicy')}}">{{trans('front.general.privacyPolicy')}}</a></li>
         </ol>
         <div class="registrationRet">
           <a href="{{ url($lang->lang.'/login/facebook') }}"><img src="{{asset('fronts/img/icons/faceLog.svg')}}" alt="">{{trans('front.general.facebook')}}</a>
           <a href="{{ url($lang->lang.'/login/google') }}"><img src="{{asset('fronts/img/icons/googleLog.svg')}}" alt="">{{trans('front.general.google')}}</a>
         </div>
      </div>
    </div>
  </div>
</div>
@include('front.layouts.footer')
@stop
