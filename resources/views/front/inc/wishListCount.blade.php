@if (count($wishListProducts) !== 0 || count($wishListSets) !== 0)
  <div class="btnm buttonWish wishAdded">
    <div class="nmb">{{count($wishListProducts) + count($wishListSets)}}</div>
    {{trans('front.general.wishList')}}
  </div>
@else
  <div class="btnm buttonWish">
    {{trans('front.general.wishList')}}
  </div>
@endif
