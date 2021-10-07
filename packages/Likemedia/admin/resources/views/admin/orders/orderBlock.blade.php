<div class="">
  <div class="cartDelivery">
    <div class="row">
      <h3>{{trans('front.admin.orderMake')}}</h3>
    </div>
    <form method="post" action="{{route('order.store')}}" id="order">
      {{ csrf_field() }}

      <input type="hidden" name="promocode" value="">
      <input type="hidden" name="front_user_id" value="{{isset($frontuser) ? $frontuser->id : 0}}">
      <div class="row">
        <h4>{{trans('front.admin.orderClient')}}</h4>
      </div>
      <div class="row">

            <div class="col-lg-3 col-md-12">

              <div class="form-group">

                <label for="emailCart">{{trans('front.fields.email')}}:</label>

                <input type="text" name="email" class="form-control" placeholder="Like.media@mail.ru" value="{{isset($frontuser) ? $frontuser->email : ''}}" id="emailCart">

              </div>

            </div>

            <div class="col-lg-3 col-md-12">

              <div class="form-group">

                <label for="telefon">{{trans('front.fields.phone')}}:</label>

                <input type="text" name="phone" class="form-control" placeholder="069 254 025" value="{{isset($frontuser) ? $frontuser->phone : ''}}" id="telefonCart">

              </div>

            </div>

            <div class="col-lg-3 col-md-12">

              <div class="form-group">

                <label for="nume">{{trans('front.fields.name')}}:</label>

                <input type="text" name="name" class="form-control" placeholder="Anastasia" value="{{isset($frontuser) ? $frontuser->name : ''}}" id="numeCart">

              </div>

            </div>

            <div class="col-lg-3 col-md-12">

              <div class="form-group">

                <label for="nume">{{trans('front.fields.surname')}}:</label>

                <input type="text" name="surname" class="form-control" placeholder="Tintari" value="{{isset($frontuser) ? $frontuser->surname : ''}}" id="numeCart">

              </div>

            </div>

          </div>

      <div class="row">

          <div class="col-12">

            <h4>{{trans('front.cart.pickup')}}</h4>

          </div>

          <div class="col-12">

            <p>{{trans('front.cart.courier')}}</p>

          </div>

          <div class="col-12">

            <div class="row">

              <input type="hidden" name="delivery" value="courier">

              <div class="tab-area">

                  <ul class="nav nav-tabs nav-tabs-bordered">

                    <li class="nav-item">

                        <a href="#courier" class="nav-link active" data-target="#courier">{{trans('front.cart.courier')}}</a>

                    </li>

                    <li class="nav-item">

                        <a href="#pickup" class="nav-link " data-target="#pickup">{{trans('front.cart.pickup')}}</a>

                    </li>

                  </ul>

              </div>

              <div class="tab-content active-content" id="courier">

                @include('admin::admin.orders.editAddress')

              </div>

              <div class="tab-content" id="pickup">

                <div class="row">

                  <div class="col-12">

                    <h5>{{trans('front.cart.pickup')}}</h5></div>

                  <div class="col-9">

                    @php

                      $contact = getContactInfo('magazins');

                    @endphp

                    @if (count($contact) > 0)

                      <select name="addressPickup" id="slcAdr">

                        @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)

                          <option value="{{$contact_translation->id}}">{{$contact_translation->value}}</option>

                        @endforeach

                      </select>

                    @endif

                  </div>

                </div>

                <div class="row">

                  <div class="col-12">

                    <h5>{{trans('front.admin.chooseDate')}}</h5></div>

                  <div class="col-lg-5 col-md-8">

                    <label for="dateCart">{{trans('front.admin.date')}}</label>

                    <input type="date" id="dateCart" name="date" value="">

                  </div>

                  <div class="col-lg-2 col-md-3">

                    <label for="timeCart">{{trans('front.admin.time')}}</label>

                    <input type="time" id="timeCart" name="time" value="">

                  </div>

                </div>

              </div>

            </div>

            <div class="row">

              <div class="col-12">

                <h4>{{trans('front.admin.payment')}}</h4>

              </div>

                <select class="form-control" name="payment">

                  <option value="card">{{trans('front.cart.card')}}</option>

                  <option value="paypal">{{trans('front.cart.paypal')}}</option>

                  <option value="cash">{{trans('front.cart.cash')}}</option>
                </select>

            </div>

          </div>
      <div class="row justify-content-end">
        <div class="col-lg-6 col-md-12">
          <input type="submit" name="submitCart" id="confirmbtn" value="{{trans('front.cart.checkout')}}">
        </div>
      </div>
    </form>
    </div>
  </div>
</div>
