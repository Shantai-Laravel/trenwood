@if (!empty($orders))
  <label>Выберите товар из доступных заказов</label>

  <select class="form-control" name="orderProducts_return" data-return_id="{{!empty($return) ? $return->id : '0'}}" onfocus="this.setAttribute('PrvSelectedValue',this.value);">
    <option value="" disabled selected>Выберите товар из доступных заказов</option>
      @foreach ($orders as $order)

          @if (count($order->orderProducts) > 0)
            @foreach ($order->orderProducts as $orderProduct)
              @if (!in_array($orderProduct->id, $orders_id))
                  @if ($orderProduct->subproduct)
                        @if ($orderProduct->orderSet)
                          <option data-subproduct_id="{{$orderProduct->subproduct_id}}" data-product_id="{{$orderProduct->product_id}}" value="{{$orderProduct->id}}">{{$orderProduct->product->translationByLanguage($lang->id)->first()->name }} - {{ $orderProduct->qty }} item from {{$orderProduct->orderSet->set->translationByLanguage($lang->id)->first()->name}}</option>
                        @else
                          <option data-subproduct_id="{{$orderProduct->subproduct_id}}" data-product_id="{{$orderProduct->product_id}}" value="{{$orderProduct->id}}">{{$orderProduct->product->translationByLanguage($lang->id)->first()->name }} - {{ $orderProduct->qty }} items</option>
                        @endif
                  @else
                      <option data-subproduct_id="0" data-product_id="{{$orderProduct->product_id}}" value="{{$orderProduct->id}}">{{$orderProduct->product->translationByLanguage($lang->id)->first()->name }} - {{ $orderProduct->qty }} items</option>
                  @endif
              @endif
            @endforeach
          @endif

          @if (count($order->orderSets) > 0)
            @foreach ($order->orderSets as $orderSet)
              @if (!in_array($orderSet->order_id, $orders_id))
                  <option value="{{$orderSet->id}}">{{ $orderSet->set->translationByLanguage($lang->id)->first()->name }}(this is one set) - {{ $orderProduct->qty }} items</option>
              @endif
            @endforeach
          @endif

      @endforeach
  </select>
@else
  <label>У этого пользователя нет завершенных заказов</label>
@endif
