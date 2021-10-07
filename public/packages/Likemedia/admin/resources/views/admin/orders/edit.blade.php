@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Orders</a></li>
        <li class="breadcrumb-item active" aria-current="brand">Edit order</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea order </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
      trans('variables.elements_list') => route('order.index'),
      trans('variables.add_element') => route('order.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <h3 href="{{ route('cart') }}">{{trans('front.cart.cart')}}</h3>
    <input type="text" placeholder="{{trans('front.cart.cod')}}: 0524026" value="" data-id="{{$order->id}}" class="form-control artProdus" style="max-width: 50%">
    <a href="javascript:void(0)" class="searchProductByCode">
      <div class="main-button">{{trans('front.cart.addToCart')}}</div>
    </a>
    <div class="col-lg-6 col-md-12 cartHere">
      @include('admin::admin.orders.cartBlock')
    </div>
    <div class="col-md-6">
      <div class="cartDelivery">
        <div class="row">
          <h3>Оформление заказа</h3>
        </div>
        <form method="post" action="{{route('order.update', $order->id)}}" id="order">
          {{ csrf_field() }}

            {{ method_field('PATCH') }}

            <input type="hidden" name="promocode" value="">
          <div class="row">
            <h4>Получатель</h4>
          </div>
          <div class="row">

                <div class="col-lg-3 col-md-12">

                  <div class="form-group">

                    <label for="emailCart">{{trans('front.cart.email')}}:</label>

                    <input type="text" name="email" class="form-control" placeholder="Like.media@mail.ru" value="{{$order->userLogged()->first() ? $order->userLogged()->first()->email : $order->userUnlogged()->first()->email}}" id="emailCart">

                  </div>

                </div>

                <div class="col-lg-3 col-md-12">

                  <div class="form-group">

                    <label for="telefon">{{trans('front.cart.phone')}}:</label>

                    <input type="text" name="phone" class="form-control" placeholder="069 254 025" value="{{$order->userLogged()->first() ? $order->userLogged()->first()->phone : $order->userUnlogged()->first()->phone}}" id="telefonCart">

                  </div>

                </div>

                <div class="col-lg-3 col-md-12">

                  <div class="form-group">

                    <label for="nume">{{trans('front.cart.name')}}:</label>

                    <input type="text" name="name" class="form-control" placeholder="Anastasia" value="{{$order->userLogged()->first() ? $order->userLogged()->first()->name : $order->userUnlogged()->first()->name}}" id="numeCart">

                  </div>

                </div>

                <div class="col-lg-3 col-md-12">

                  <div class="form-group">

                    <label for="nume">{{trans('front.cart.surname')}}:</label>

                    <input type="text" name="surname" class="form-control" placeholder="Tintari" value="{{$order->userLogged()->first() ? $order->userLogged()->first()->surname : $order->userUnlogged()->first()->surname}}" id="numeCart">

                  </div>

                </div>

              </div>

          <div class="row">

              <div class="col-12">

                <h4>{{trans('front.cart.delivery')}}</h4>

              </div>

              <div class="col-12">

                <p>{{trans('front.cart.deliverycourier')}}</p>

              </div>

              <div class="col-12">

                <div class="row">

                  @php

                      if(count($order->addressById()->first()) > 0) {

                          $courier = 'active';

                          $pickup = '';

                          $delivery = 'courier';

                      } else {

                          $courier = '';

                          $pickup = 'active';

                          $delivery = 'pickup';

                      }

                  @endphp

                  <input type="hidden" name="delivery" value="{{$delivery}}">

                  <div class="tab-area">

                      <ul class="nav nav-tabs nav-tabs-bordered">

                        <li class="nav-item">

                            <a href="#courier" class="nav-link {{$courier}}" data-target="#courier">{{trans('front.cart.typeDeliveryFirst')}}</a>

                        </li>

                        <li class="nav-item">

                            <a href="#pickup" class="nav-link {{$pickup}}" data-target="#pickup">{{trans('front.cart.typeDeliverySecond')}}</a>

                        </li>

                      </ul>

                  </div>

                  <div class="tab-content {{$courier}}-content" id="courier">

                    @if (count($order->userLogged()->first()) > 0)

                        <div class="infoAdr">

                          <input type="hidden" name="addressCourier" value="">

                          @if (count($order->userLogged()->first()->addresses()->get()) > 0)

                            <div class="col-12">

                              <div class="slideAdress">

                                <select class="form-control" name="addressCourier">

                                  @foreach ($order->userLogged()->first()->addresses()->get() as $key => $address)

                                      <option {{$order->address_id == $address->id ? 'selected' : ''}} value="{{$address->id}}">{{$address->addressname}}</option>

                                  @endforeach

                                </select>

                                <br>

                                @foreach ($order->userLogged()->first()->addresses()->get() as $key => $address)

                                  <div class="addressInfo" data-id="{{$address->id}}">

                                    @include('admin::admin.orders.editAddress')

                                  </div>

                                @endforeach

                              </div>

                            </div>

                          @endif

                          @if (session('deleteAddresses'))

                            <div class="row adrLimit">

                              <div class="dropdownAdr">

                                <p>{{trans('front.cart.addresslist')}}</p>

                              </div>

                              <div class="adrDown">

                                @foreach (session('deleteAddresses') as $key => $address)

                                    <div class="row adrDownItem">

                                      <div class="col-10">

                                        <div class="nameAdr">

                                          <span id="nrAdr">{{$key + 1}}.</span> {{$address->addressname}}

                                        </div>

                                        <div class="adr">

                                          <p>{{$address->address}}</p>

                                        </div>

                                      </div>

                                      <div class="col-2 ">

                                        <form>

                                          {{ csrf_field() }}

                                          {{ method_field('DELETE') }}

                                          <input type="button" class="deleteAddress" data-order_id="{{$order->id}}" data-id="{{$address->id}}" name="delete" value="{{trans('front.cabinet.myaddresses.deleteBtn')}}" class="removeItemAdr">

                                        </form>

                                      </div>

                                    </div>

                                @endforeach

                              </div>

                            </div>

                          @endif

                          <div class="row addAdr">

                            <div class="col-lg-5 col-md-12">

                              <div class="addAdres" data-toggle="modal" data-target="#modalCart">

                                {{trans('front.cart.addAddressBtn')}}

                              </div>

                            </div>

                          </div>

                        </div>

                    @else

                      @include('admin::admin.orders.address')

                    @endif

                  </div>

                  <div class="tab-content {{$pickup}}-content" id="pickup">

                    <div class="row">

                      <div class="col-12">

                        <h5>{{trans('front.cart.store')}} Ammo</h5></div>

                      <div class="col-9">

                        @php

                          $contact = getContactInfo('magazins');

                        @endphp

                        @if (count($contact) > 0)

                          <select name="addressPickup" id="slcAdr">

                            @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)

                              <option {{$order->address_id == $contact_translation->id ? 'selected': ''}} value="{{$contact_translation->id}}">{{$contact_translation->value}}</option>

                            @endforeach

                          </select>

                        @endif

                      </div>

                    </div>

                    <div class="row">

                      <div class="col-12">

                        <h5>{{trans('front.cart.datetime')}}</h5></div>

                      <div class="col-lg-5 col-md-8">

                        <label for="dateCart">{{trans('front.cart.date')}}</label>

                        <input type="date" id="dateCart" name="date" value="{{explode(' ', $order->datetime)[0]}}">

                      </div>

                      <div class="col-lg-2 col-md-3">

                        <label for="timeCart">{{trans('front.cart.time')}}</label>

                        <input type="time" id="timeCart" name="time" value="{{explode(' ', $order->datetime)[1]}}">

                      </div>

                    </div>

                  </div>

                </div>

                <div class="row">

                  @if ($order->status == 'pending' && ($order->payment != 'card' || $order->payment != 'paypal'))

                    <div class="col-12">

                      <h4>Метод оплаты</h4>

                    </div>

                    <select class="form-control editPayment" name="payment" data-id="{{$order->id}}">

                      <option {{$order->payment == 'card' ? 'selected': ''}} value="card">Card</option>

                      <option {{$order->payment == 'paypal' ? 'selected': ''}} value="paypal">Paypal</option>

                      <option {{$order->payment == 'invoice' ? 'selected': ''}} value="invoice">Invoice</option>

                      <option {{$order->payment == 'cash' ? 'selected': ''}} value="cash">Cash</option>

                    </select>

                  @endif

                </div>

                <div class="row">

                    <div class="col-12">

                      <h4>Основной статус Orders</h4>

                    </div>

                  <select class="form-control" name="status">

                    <option {{$order->status == 'pending' ? 'selected': ''}} value="pending">{{trans('front.cabinet.historyOrder.pending')}}</option>

                    <option {{$order->status == 'processing' ? 'selected': ''}} value="processing">{{trans('front.cabinet.historyOrder.processing')}}</option>

                    <option {{$order->status == 'inway' ? 'selected': ''}} value="inway">{{trans('front.cabinet.historyOrder.inway')}}</option>

                    <option {{$order->status == 'completed' ? 'selected': ''}} value="completed">{{trans('front.cabinet.historyOrder.completed')}}</option>

                  </select>

                    <div class="col-12">

                      <h4>Второстепенный статус Orders</h4>

                    </div>

                  <select class="form-control" name="secondarystatus">

                    <option {{$order->secondarystatus == 'notanswer' ? 'selected': ''}} value="notanswer">{{trans('front.cabinet.historyOrder.notanswer')}}</option>

                    <option {{$order->secondarystatus == 'confirmed' ? 'selected': ''}} value="confirmed">{{trans('front.cabinet.historyOrder.confirmed')}}</option>

                    <option {{$order->secondarystatus == 'cancelled' ? 'selected': ''}} value="cancelled">{{trans('front.cabinet.historyOrder.cancelled')}}</option>

                    <option {{$order->secondarystatus == 'notinstock' ? 'selected': ''}} value="notinstock">{{trans('front.cabinet.historyOrder.notinstock')}}</option>

                  </select>

                  <div class="col-12">

                    <h4>Achitare</h4>

                  </div>

                  <select class="form-control" name="paymentstatus">

                    <option {{$order->paymentstatus == 'payed' ? 'selected': ''}} value="payed">{{trans('front.cabinet.historyOrder.payed')}}</option>

                    <option {{$order->paymentstatus == 'notpayed' ? 'selected': ''}} value="notpayed">{{trans('front.cabinet.historyOrder.notpayed')}}</option>

                  </select>

                </div>

              </div>
          <div class="row justify-content-end">
            <div class="col-lg-6 col-md-12">
              <input type="submit" name="submitCart" id="confirmbtn" value="Сохранить">
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<div class="modal" id="addModalCart">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>

<!-- MODALKA -->
  @if(count($order->userLogged()->first()) > 0)
    <div class="modal" id="modalCart">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{trans('front.cart.addAddressBtn')}}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form method="post" action="{{ route('order.saveAddress', $order->id) }}">
            {{ csrf_field() }}
          <div class="modal-body">
              @include('admin::admin.orders.address')
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">{{trans('front.cart.closeBtn')}}</button>
              <input type="submit" value="{{trans('front.cart.addAddressBtn')}}">
            </div>
        </form>
        </div>
      </div>
    </div>
  @endif
<!-- MODALKA -->


@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
