@extends('front.app')
@section('content')
@include('front.layouts.header')
<div class="collectionOne about">
  <div class="container">
    <div class="row">
      <h5 class="col-12">
        {{trans('front.contacts.contact')}}
      </h5>
    </div>
    <div class="aboutBlock row">
      <h3>{{trans('front.contacts.title')}}</h3>
      <p>{{trans('front.contacts.body')}}</p>
      <div class="col-12">
        <div style="width: 100%">
          {{-- <iframe width="100%" height="100" src="https://maps.google.com/maps?q={{getContactInfo('address')->translationByLanguage($lang->id)->first()->value}}&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
            <a href="https://www.maps.ie/map-my-route/">Create a route on google maps</a>
          </iframe> --}}
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5442.204217673889!2d28.81094!3d46.998968!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xfaf30cf2e091bc0f!2sMoldova+Film!5e0!3m2!1sru!2sus!4v1563461094327!5m2!1sru!2sus" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
      </div>
    </div>
    <div class="program row justify-content-center">
      <div class="col-12">
        <div class="contactBloc">
            <div class="title">
                {{trans('front.contacts.queriesProductsDetailsTitle')}}
            </div>
            <ul>
                <li>
                    <a href="tel:{{trans('front.contacts.mdphone')}}" class="phone">{{trans('front.contacts.mdphone')}}</a>
                </li>
                <li>
                    <a href="{{trans('front.contacts.email')}}" class="aron">{{trans('front.contacts.email')}}</a>
                </li>
                <li class="inactive">
                    <a href="#" class="location">{{trans('front.contacts.addressSiteMain')}}</a>
                </li>
            </ul>
        </div>
        <div class="contactBloc">
            <div class="title">
                {{ trans('front.contacts.queriesPaymentShippingReturnsTitle') }}
            </div>
            <p>{{ trans('front.contacts.queriesPaymentShippingReturnsSubTitle') }}</p>
            <ul>
                <li class="inactive" style="margin-bottom: 10px;">
                    {{ trans('front.contacts.queriesPaymentShippingReturnsCompany') }}
                </li>
                <li class="inactive">
                    <a href="#" class="location">
                        {{ trans('front.contacts.queriesPaymentShippingReturnsAddress') }}
                    </a>
                </li>
                <li>
                    <a href="tel:+{{ trans('front.contacts.queriesPaymentShippingReturnsPhone') }}" class="phone">{{ trans('front.contacts.queriesPaymentShippingReturnsPhone') }}</a>
                </li>
                <li>
                    <a href="mailto:{{ trans('front.contacts.queriesPaymentShippingReturnsEmail') }}" class="aron">{{ trans('front.contacts.queriesPaymentShippingReturnsEmail') }}</a>
                </li>
            </ul>
        </div>
      </div>
      <div class="col-auto">
        <div class="nameProgram">{{trans('front.contacts.schedule')}}</div>
        <ul>
          <li>{{trans('front.contacts.weekdays')}} {{getContactInfo('workWeekdays')->translationByLanguage()->first()->value}}</li>
          <li>{{trans('front.contacts.saturday')}} {{getContactInfo('workWeekends')->translationByLanguage()->first()->value}}</li>
          <li>{{trans('front.contacts.sunday')}} {{getContactInfo('weekend')->translationByLanguage()->first()->value}}</li>
          <li><a href="tel: +37368793425">{{trans('front.contacts.phone')}} {{getContactInfo('phone')->translationByLanguage()->first()->value}}</a></li>
        </ul>
      </div>
    </div>
    <div class="formContact">
      <div class="row justify-content-center">
        <div class="col-md-auto col-12">
          <form action="{{ url($lang->lang.'/contacts') }}" method="post">
            {{ csrf_field() }}

            @if (Session::has('success'))
                <div class="row">
                   <div class="col-12">
                      <div class="errorPassword">
                         <p>{{ Session::get('success') }}</p>
                      </div>
                   </div>
                </div>
            @endif

            <h3>{{trans('front.contacts.contact')}}</h3>
            <div class="row">
              <div class="col-12">
                @if ($errors->has('fullname'))
                   <div class="invalid-feedback" style="display: block">
                     {!!$errors->first('fullname')!!}
                   </div>
                @endif
                <input type="text" class="form-control {{$errors->has('fullname') ? 'validationError' : ''}}" name="fullname" placeholder="{{trans('front.fields.fullName')}}">
                @if ($errors->has('email'))
                   <div class="invalid-feedback" style="display: block">
                     {!!$errors->first('email')!!}
                   </div>
                @endif
                <input type="email" class="form-control {{$errors->has('fullname') ? 'validationError' : ''}}" name="email" placeholder="{{trans('front.fields.email')}}">
                @if ($errors->has('phone'))
                   <div class="invalid-feedback" style="display: block">
                     {!!$errors->first('phone')!!}
                   </div>
                @endif
                <input type="text" class="form-control {{$errors->has('fullname') ? 'validationError' : ''}}" name="phone" placeholder="{{trans('front.fields.phone')}}">
                @if ($errors->has('message'))
                   <div class="invalid-feedback" style="display: block">
                     {!!$errors->first('message')!!}
                   </div>
                @endif
                <textarea type="text" class="form-control {{$errors->has('fullname') ? 'validationError' : ''}}" name="message" placeholder="{{trans('front.fields.message')}}"></textarea>

              </div>
              <div class="col-12">
                <input class="btnSubmit" type="submit" value="{{trans('front.contacts.contact')}}">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@include('front.layouts.footer')
@stop
