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
                 <li><a href="{{url($lang->lang.'/register')}}">{{trans('front.registration.register')}}</a></li>
              </ul>
           </div>
        </div>
     </div>
      <div class="row">
         <div class="col-12">
            <h3>{{trans('front.registration.registerNow')}}</h3>
         </div>
         <div class="col-12">
            <div class="registrationRet registrationRetMob">
               <a href="{{ url($lang->lang.'/login/facebook') }}"><img src="{{asset('fronts/img/icons/faceLog.svg')}}" alt="">{{trans('front.general.facebook')}}</a>
               <a href="{{ url($lang->lang.'/login/google') }}"><img src="{{asset('fronts/img/icons/googleLog.svg')}}" alt="">{{trans('front.general.google')}}</a>
               <a href="{{ url($lang->lang.'/login/instagram') }}"><img src="{{asset('fronts/img/icons/instagramLog.svg')}}" alt="">{{trans('front.general.instagram')}}</a>
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
                     <h4>{{trans('front.registration.register')}}</h4>
                  </div>
               </div>
               <form action="{{ url($lang->lang.'/register') }}" method="post">
                   {{ csrf_field() }}

                   <input type="hidden" name="prev" value="{{url()->previous()}}">

                   @if (count($userfields) > 0)
                     @foreach ($userfields as $key => $userfield)
                         @if ($userfield->type != 'checkbox')
                             <div class="form-group">
                               @if ($errors->has($userfield->field))
                                  <div class="invalid-feedback" style="display: block">
                                    {!!$errors->first($userfield->field)!!}
                                  </div>
                               @endif
                               <input type="text" class="form-control {{$errors->has($userfield->field) ? 'validationError' : ''}}" placeholder="{{trans('front.fields.'.$userfield->field)}}*" name="{{$userfield->field}}" id="{{$userfield->field}}" value="{{ old($userfield->field) }}">
                             </div>
                         @endif
                     @endforeach
                   @endif

                   <div class="form-group">
                     @if ($errors->has('password'))
                        <div class="invalid-feedback" style="display: block">
                          {!!$errors->first('password')!!}
                        </div>
                     @endif
                     <input type="password" placeholder="{{trans('front.fields.password')}}*" class="form-control {{$errors->has('password') ? 'validationError' : ''}}" name="password" id="pwd" >
                   </div>
                   <div class="form-group">
                     @if ($errors->has('passwordRepeat'))
                        <div class="invalid-feedback" style="display: block">
                          {!!$errors->first('passwordRepeat')!!}
                        </div>
                     @endif
                     <input type="password" placeholder="{{trans('front.fields.repeatPass')}}*" class="form-control {{$errors->has('password') ? 'validationError' : ''}}" name="passwordRepeat" id="confpwd" >
                   </div>

                   @if (count($userfields) > 0)
                     @foreach ($userfields as $key => $userfield)
                         @if ($userfield->type == 'checkbox')
                           <div class="offr">
                              {{trans('front.registration.'.$userfield->field.'_question')}}
                           </div>
                           <p>{{trans('front.registration.'.$userfield->field.'_p')}}</p>
                           <div class="row">
                              <div class="col-12">
                                 <label class="containerCheck1">{!!trans('front.registration.'.$userfield->field.'_checkbox')!!}
                                   @if ($errors->has($userfield->field))
                                      <div class="invalid-feedback" style="display: block">
                                        {!!$errors->first($userfield->field)!!}
                                      </div>
                                   @endif
                                 <input type="checkbox" name="{{$userfield->field}}">
                                 <span class="checkmarkCheck"></span>
                                 </label>
                              </div>
                           </div>
                         @endif
                     @endforeach
                   @endif
                  <div class="row justify-content-start margeTop2">
                     <div class="col-md-5 col-sm-6 col-7">
                        <input class="btnSubmit" type="submit" value="{{trans('front.registration.registerBtn')}}">
                     </div>
                  </div>
               </form>
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
