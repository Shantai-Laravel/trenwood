@if (!empty($orders))
  <label>{{trans('front.admin.chooseProduct')}}</label>
  <select class="form-control" name="orderProducts_return" data-return_id="{{!empty($return) ? $return->id : '0'}}" onfocus="this.setAttribute('PrvSelectedValue',this.value);">
    <option value="" disabled selected>{{trans('front.admin.chooseProduct')}}</option>
      @foreach ($orders as $order)
          @if (count($order->orderProducts) > 0)
            @foreach ($order->orderProducts as $orderProduct)
              @if (!in_array($orderProduct->id, $orderProducts_id))
                  @if ($orderProduct->subproduct)
                        @if ($orderProduct->orderSet)
                          <option data-subproduct_id="{{$orderProduct->subproduct_id}}" data-product_id="{{$orderProduct->product_id}}" value="{{$orderProduct->id}}">{{$orderProduct->product->translationByLanguage($lang->id)->first()->name }} - {{ $orderProduct->qty }} {{trans('front.admin.itemFrom')}} {{$orderProduct->orderSet->set->translationByLanguage($lang->id)->first()->name}}</option>
                        @else
                          <option data-subproduct_id="{{$orderProduct->subproduct_id}}" data-product_id="{{$orderProduct->product_id}}" value="{{$orderProduct->id}}">{{$orderProduct->product->translationByLanguage($lang->id)->first()->name }} - {{ $orderProduct->qty }} {{trans('front.admin.item')}}</option>
                        @endif
                  @else
                      <option data-subproduct_id="0" data-product_id="{{$orderProduct->product_id}}" value="{{$orderProduct->id}}">{{$orderProduct->product->translationByLanguage($lang->id)->first()->name }} - {{ $orderProduct->qty }} {{trans('front.admin.item')}}</option>
                  @endif
              @endif
            @endforeach
          @endif
          @if (count($order->orderSets) > 0)
            @foreach ($order->orderSets as $orderSet)
              @if (!in_array($orderSet->order_id, $orderSets_id))
                  <option value="{{$orderSet->id}}">{{ $orderSet->set->translationByLanguage($lang->id)->first()->name }} - {{ $orderProduct->qty }} {{trans('front.admin.item')}}</option>
              @endif
            @endforeach
          @endif
      @endforeach
  </select>
@else
  <label>{{trans('front.admin.noOrders')}}</label>
@endif
