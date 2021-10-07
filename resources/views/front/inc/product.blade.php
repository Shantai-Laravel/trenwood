@if (!empty($changedSubproduct))
<div class="row priceProductOne justify-content-center align-items-center">
  @if ($changedSubproduct->discount > 0)
    <div class="col-auto reduce">{{$changedSubproduct->price}} {{trans('front.general.currency')}}</div>
    <div class="col-auto">
      {{$changedSubproduct->price - ($changedSubproduct->price * $changedSubproduct->discount / 100)}} {{trans('front.general.currency')}}
    </div>
  @else
    <div class="col-auto">{{$changedSubproduct->price}} {{trans('front.general.currency')}}</div>
  @endif
</div>
@else
<div class="row priceProductOne justify-content-center align-items-center">
  @if ($product->discount > 0)
    <div class="col-auto reduce">{{$product->price}} {{trans('front.general.currency')}}</div>
    <div class="col-auto ">
      {{$product->price - ($product->price * $product->discount / 100)}} {{trans('front.general.currency')}}
    </div>
  @else
    <div class="col-auto">{{$product->price}} {{trans('front.general.currency')}}</div>
  @endif
</div>
@endif
