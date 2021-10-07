@extends('front.app')
@section('content')
@include('front.layouts.header')
<div class="collectionOne cart">
  <div class="container">
    <div class="row">
      <div class="col-auto">
        <div class="crumbs">
          <ul>
            <li><a href="{{url($lang->lang)}}">{{trans('front.general.homePage')}}</a> / </li>
            <li><a href="{{url($lang->lang.'/cart')}}">{{trans('front.cart.cart')}}</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row justify-content-center comandSucces">
      <div class="col-auto">
        {{trans('front.cart.cart')}}
      </div>
    </div>
    @if (isMobile())
      <div class="cartBlockMob">
        @include('front.inc.cartBlockMob')
      </div>
    @else
      <div class="cartBlock">
        @include('front.inc.cartBlock')
      </div>
    @endif
    <div class="promo">
      @include('front.inc.promo')
    </div>
    <div class="deliveryCart">
      <div class="row">
        @if (count($generalFields) > 0)
           @foreach ($generalFields as $generalField)
               @if ($generalField->name === 'order')
                 <div class="col-md-3 col-sm-6 col-12">
                    <div class="deliveryItem">
                       <div class="deliveryTitle">
                          {{$generalField->translationByLanguage($lang->id)->first()->name}}
                       </div>
                       <p>{{$generalField->translationByLanguage($lang->id)->first()->body}}</p>
                    </div>
                 </div>
               @endif
           @endforeach
        @endif
      </div>
    </div>
    <div class="deliveryDetail">
      <form action="{{ url($lang->lang.'/order') }}" method="post" class="orderForm">
          {{ csrf_field() }}
        <div class="row parentCart">
          <div class="col-md-9 col-12 fan">
            <div class="row">
              <div class="col-12">
                <h4>{{trans('front.cart.details')}}</h4></div>
              <div class="col-12">
                <p>{!!trans('front.cart.detailsText')!!}</p>
              </div>
              <div class="col-12">
                <div class="row">

                  @if(Auth::guard('persons')->guest())
                    <div class="col-12">
                      <div class="row bord">
                        <div class="col-lg-6 col-md-8 col-12 radioBox">
                          <div class="col-md-6">
                              <label class="container1">{{trans('front.cart.new')}}
                                  <input type="radio" checked name="radio">
                                  <span class="checkmark1"></span>
                                </label>
                          </div>
                          <div class="col-md-6" data-toggle="modal">
                             <a href="{{ url('/'.$lang->lang.'/login') }}">
                                 <label class="container1" data-toggle="modal" data-target="#modLog">
                                     <div>{{trans('front.cart.old')}}</div>
                                     <span class="checkmark1" data-toggle="modal" data-target="#modLog"></span>
                                 </label>
                             </a>
                          </div>

                        </div>
                      </div>
                    </div>
                  @endif

                  @if (count($userfields) > 0)
                    <div class="col-12">
                      <div class="row">
                          @foreach ($userfields as $key => $userfield)
                          @if ($userfield->field_group == 'personaldata' && $userfield->type != 'checkbox')
                          <?php $field = $userfield->field; ?>
                          <div class="col-md-4">
                              <div class="form-group">
                                  @if ($errors->has($field))
                                    <div class="invalid-feedback" style="display: block">
                                        {!!$errors->first($field)!!}
                                    </div>
                                  @endif
                                  <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                                  <input type="text" name="{{$field}}" placeholder="{{trans('front.fields.'.$field)}}*" class="form-control {{$errors->has($field) ? 'validationError' : ''}}" value="{{$userdata ? $userdata->$field : old($field)}}">
                              </div>
                          </div>
                          @endif
                          @endforeach
                      </div>
                    </div>
                  @endif

                  <div class="col-12">
                    <h5>{{trans('front.cart.delivery')}}</h5>
                  </div>
                  <div class="col-12">
                    <div class="row">
                      <div class="col-md-8 col-12 radioBoxColumn">
                        @if ($errors->has('delivery'))
                          <div class="invalid-feedback" style="display: block">
                              {!!$errors->first('delivery')!!}
                          </div>
                        @endif
                        <label class="container1">{{trans('front.cart.courier')}}
                          <input type="radio" name="delivery" checked value="courier" class="showDelivery">
                          <span class="checkmark1"></span>
                        </label>
                        <label class="container1">{{trans('front.cart.pickup')}}
                          <input type="radio" name="delivery" value="pickup" class="showPickup">
                          <span class="checkmark1"></span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="col-12">
                    <h5>
                      {{trans('front.cart.addressDetails')}}
                    </h5>
                  </div>

                  <div class="col-md-4 pickupBlock" style="display: none;">
                      <div class="form-group">
                        @if ($errors->has('pickup'))
                        <div class="invalid-feedback" style="display: block">
                            {!!$errors->first('pickup')!!}
                        </div>
                        @endif
                          <select name="pickup" class="name {{$errors->has('pickup') ? 'validationError' : ''}}" id="pickup">
                              <option disabled selected value="0">{{trans('front.cart.chooseAddress')}}</option>
                              @if (!is_null($pickup))
                                  @if (count($pickup->translationByLanguage($lang->id)->get()) > 0)
                                      @foreach ($pickup->translationByLanguage($lang->id)->get() as $key => $address)
                                          <option value="{{ $address->id }}">{{ $address->value }}</option>
                                      @endforeach
                                  @endif
                              @endif
                          </select>
                      </div>
                  </div>

                  @if(Auth::guard('persons')->check())
                    <div class="deliveryBlock">
                      @if(count($userdata->addresses()->get()) > 0)
                        <div class="col-md-4">
                          <select class="form-control {{$errors->has('addressMain') ? 'validationError' : ''}}" name="addressMain">
                              @foreach ($userdata->addresses()->get() as $address)
                              <option value="{{$address->id}}">{{$address->addressname}}</option>
                              @endforeach
                          </select>
                        </div>

                        @foreach ($userdata->addresses()->get() as $address)
                          <div class="col-12 adressUnlogged addressInfo" data-id="{{$address->id}}">
                            @if (count($userfields) > 0)
                              <div class="row locationCart">
                                @foreach ($userfields as $key => $userfield)
                                  @if ($userfield->field_group == 'address')
                                    <?php $field = $userfield->field; ?>
                                    @if ($userfield->type == 'text')
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              @if ($errors->has($userfield->field))
                                                <div class="invalid-feedback" style="display: block">
                                                    {!!$errors->first($userfield->field)!!}
                                                </div>
                                              @endif
                                              <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                                              <input type="{{$userfield->type}}" placeholder="{{trans('front.cabinet.address.'.$field)}}" name="{{$field}}[]" class="name form-control {{$errors->has($userfield->field) ? 'validationError' : ''}}" id="{{$field}}" value="{{!empty($address) ? $address->$field : old($field)}}">
                                          </div>
                                      </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            @if ($errors->has($userfield->field))
                                            <div class="invalid-feedback" style="display: block">
                                                {!!$errors->first($userfield->field)!!}
                                            </div>
                                            @endif
                                            <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                                            @if ($userfield->field == 'country')
                                            <select name="{{$field}}[]" class="name filterCountriesCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="{{$address->id}}" id="{{$field}}">
                                                <option disabled selected>{{trans('front.cabinet.address.chooseCountry')}}</option>
                                                @foreach ($countries as $onecountry)
                                                <option {{!empty($address) && $address->country == $onecountry->id ? 'selected' : '' }} value="{{$onecountry->id}}">{{$onecountry->name}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            @if ($userfield->field == 'region')
                                            <select name="{{$field}}[]" class="name filterRegionsCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="{{$address->id}}" id="{{$field}}">
                                                <option disabled selected>{{trans('front.cabinet.address.chooseRegion')}}</option>
                                                @if (!empty($regions))
                                                @foreach ($regions as $region)
                                                @foreach ($region as $oneregion)
                                                <option {{!empty($address) && $address->region == $oneregion->id ? 'selected' : '' }} value="{{$oneregion->id}}">{{$oneregion->name}}</option>
                                                @endforeach
                                                @endforeach
                                                @endif
                                            </select>
                                            @endif
                                            @if ($userfield->field == 'location')
                                            <select name="{{$field}}[]" class="name filterCitiesCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="{{$address->id}}" id="{{$field}}">
                                                <option disabled selected>{{trans('front.cabinet.address.chooseLocation')}}</option>
                                                @if (!empty($cities))
                                                @foreach ($cities as $city)
                                                @foreach ($city as $onecity)
                                                <option {{!empty($address) && $address->location == $onecity->id ? 'selected' : '' }} value="{{$onecity->id}}">{{$onecity->name}}</option>
                                                @endforeach
                                                @endforeach
                                                @endif
                                            </select>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                  @endif
                                @endforeach
                              </div>
                            @endif
                          </div>
                        @endforeach
                      @else
                        <div class="col-12 addressUnlogged deliveryBlock">
                          <div class="row locationCart">
                            @if (count($userfields) > 0)
                              @foreach ($userfields as $key => $userfield)
                                @if ($userfield->field_group == 'address')
                                  <?php $field = $userfield->field; ?>
                                  @if ($userfield->type == 'text')
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            @if ($errors->has($userfield->field))
                                            <div class="invalid-feedback" style="display: block">
                                                {!!$errors->first($userfield->field)!!}
                                            </div>
                                            @endif
                                            <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                                            <input type="{{$userfield->type}}" placeholder="{{trans('front.cabinet.address.'.$field)}}" name="{{$field}}" class="name form-control {{$errors->has($userfield->field) ? 'validationError' : ''}}" id="{{$field}}" value="{{old($field)}}">
                                        </div>
                                    </div>
                                  @else
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          @if ($errors->has($userfield->field))
                                          <div class="invalid-feedback" style="display: block">
                                              {!!$errors->first($userfield->field)!!}
                                          </div>
                                          @endif
                                          <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                                          @if ($userfield->field == 'country')
                                          <select name="{{$field}}" class="name filterCountriesCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="0" id="{{$field}}">
                                              <option disabled selected>{{trans('front.cabinet.address.chooseCountry')}}</option>
                                              @foreach ($countries as $onecountry)
                                              <option value="{{$onecountry->id}}">{{$onecountry->name}}</option>
                                              @endforeach
                                          </select>
                                          @endif
                                          @if ($userfield->field == 'region')
                                          <select name="{{$field}}" class="name filterRegionsCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="0" id="{{$field}}">
                                              <option disabled selected>{{trans('front.cabinet.address.chooseRegion')}}</option>
                                          </select>
                                          @endif
                                          @if ($userfield->field == 'location')
                                          <select name="{{$field}}" class="name filterCitiesCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="0" id="{{$field}}">
                                              <option disabled selected>{{trans('front.cabinet.address.chooseLocation')}}</option>
                                          </select>
                                          @endif
                                      </div>
                                  </div>
                                  @endif
                                @endif
                              @endforeach
                            @endif
                          </div>
                        </div>
                      @endif
                    </div>
                  @else
                    <div class="col-12 addressUnlogged deliveryBlock">
                      <div class="row locationCart">
                        @if (count($userfields) > 0)
                          @foreach ($userfields as $key => $userfield)
                            @if ($userfield->field_group == 'address')
                              <?php $field = $userfield->field; ?>
                              @if ($userfield->type == 'text')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @if ($errors->has($userfield->field))
                                        <div class="invalid-feedback" style="display: block">
                                            {!!$errors->first($userfield->field)!!}
                                        </div>
                                        @endif
                                        <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                                        <input type="{{$userfield->type}}" placeholder="{{trans('front.cabinet.address.'.$field)}}" name="{{$field}}" class="name form-control {{$errors->has($userfield->field) ? 'validationError' : ''}}" id="{{$field}}" value="{{old($field)}}">
                                    </div>
                                </div>
                              @else
                              <div class="col-md-4">
                                  <div class="form-group">
                                      @if ($errors->has($userfield->field))
                                      <div class="invalid-feedback" style="display: block">
                                          {!!$errors->first($userfield->field)!!}
                                      </div>
                                      @endif
                                      <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                                      @if ($userfield->field == 'country')
                                      <select name="{{$field}}" class="name filterCountriesCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="0" id="{{$field}}">
                                          <option disabled selected>{{trans('front.cabinet.address.chooseCountry')}}</option>
                                          @foreach ($countries as $onecountry)
                                          <option value="{{$onecountry->id}}">{{$onecountry->name}}</option>
                                          @endforeach
                                      </select>
                                      @endif
                                      @if ($userfield->field == 'region')
                                      <select name="{{$field}}" class="name filterRegionsCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="0" id="{{$field}}">
                                          <option disabled selected>{{trans('front.cabinet.address.chooseRegion')}}</option>
                                      </select>
                                      @endif
                                      @if ($userfield->field == 'location')
                                      <select name="{{$field}}" class="name filterCitiesCart {{$errors->has($userfield->field) ? 'validationError' : ''}}" data-id="0" id="{{$field}}">
                                          <option disabled selected>{{trans('front.cabinet.address.chooseLocation')}}</option>
                                      </select>
                                      @endif
                                  </div>
                              </div>
                              @endif
                            @endif
                          @endforeach
                        @endif
                      </div>
                    </div>
                  @endif

                  <div class="col-12">
                    <h5>{{trans('front.cart.payment')}}</h5>
                    <div class="row">
                      <div class="col-12">
                        <div class="row">
                          <div class="col-lg-5 col-md-6 col-10 radioBoxColumn">
                            @if ($errors->has('payment'))
                                <div class="invalid-feedback" style="display: block">
                                    {!!$errors->first('payment')!!}
                                </div>
                            @endif
                            <input type="hidden" name="payment" value="">
                            <label class="container1">{{trans('front.cart.card')}}
                              <input type="radio" name="payment" value="card">
                              <span class="checkmark1"></span>
                            </label>
                            <label class="container1">{{trans('front.cart.paypal')}}
                              <input type="radio" name="payment" value="paypal">
                              <span class="checkmark1"></span>
                            </label>
                            <label class="container1">{{trans('front.cart.cash')}}
                              <input type="radio" name="payment" value="cash" checked>
                              <span class="checkmark1"></span>
                            </label>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @if (Auth::guard('persons')->guest() && count($userfields) > 0)
                @foreach ($userfields as $key => $userfield)
                  @if ($userfield->type == 'checkbox')
                      <div class="col-12 police">
                      <h4>{{trans('front.registration.'.$userfield->field.'_question')}}</h4>
                      <p>{{trans('front.registration.'.$userfield->field.'_p')}}</p>
                          <label class="containerCheck1">{{trans('front.registration.'.$userfield->field.'_checkbox')}}
                              <input type="hidden" name="{{$userfield->field}}"  value="">
                              <input type="checkbox" class="form-check-input" name="{{$userfield->field}}" value="1">
                              <span class="checkmarkCheck"></span>

                              @if ($errors->has($userfield->field))
                                  <div class="invalid-feedback" style="display: block">
                                      {!!$errors->first($userfield->field)!!}
                                  </div>
                              @endif
                          </label>
                      </div>
                  @endif
                @endforeach
              @endif
              <div class="col-md-4 col-sm-5 col-8">
                  <input class="btnSubmit" type="submit" value="{{trans('front.cart.checkout')}}">
              </div>
            </div>

          </div>
          <div class="col-3 summarComanda cartSummary">
            @include('front.inc.cartSummary')
          </div>
      </form>

        <div class="modal" id="modLog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{trans('front.login.auth')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="{{ url($lang->lang.'/login') }}" method="post">
                    {{ csrf_field() }}
                  <div class="row justify-content-center">
                    @if (count($loginFields) > 0)
                    @foreach ($loginFields as $key => $loginField)
                    <div class="col-12">
                      <div class="form-group">
                          @if ($errors->has($loginField->field))
                          <div class="invalid-feedback" style="display: block">
                              {!!$errors->first($loginField->field)!!}
                          </div>
                          @endif
                          <input type="text" class="form-control {{$errors->has($loginField->field) ? 'validationError' : ''}}" placeholder="{{trans('front.fields.'.$loginField->field)}}" name="{{$loginField->field}}" id="{{$loginField->field}}" value="{{ old($loginField->field) }}">
                      </div>
                    </div>
                    @endforeach
                    @endif
                    <div class="col-12">
                      <div class="form-group">
                          @if ($errors->has('password'))
                          <div class="invalid-feedback" style="display: block">
                              {!!$errors->first('password')!!}
                          </div>
                          @endif
                          <input type="password" placeholder="{{trans('front.fields.passwordEnter')}}" class="form-control {{$errors->has('password') ? 'validationError' : ''}}" name="password" id="pwdLog">
                      </div>
                    </div>
                    <div class="col-2 logImg">
                      <a href="{{url($lang->lang.'/login/facebook') }}"><img src="{{asset('fronts/img/icons/faceLog.svg')}}" alt=""></a>
                    </div>
                    <div class="col-2 logImg">
                      <a href="{{url($lang->lang.'/login/google') }}"><img src="{{asset('fronts/img/icons/googleLog.svg')}}" alt=""></a>
                    </div>
                    <div class="col-10">
                      <input class="btnSubmit" type="submit" name="" value="{{trans('front.login.auth')}}">
                    </div>
                    <div class="col-10"><a href="{{route('password.email')}}">{{trans('front.forgotPass.forgot')}}?</a></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@include('front.layouts.footer')
<script type="text/javascript">
  if(screen.width > 768) {
    $('#cover').css('overflow', 'visible');
  }
</script>
@stop
