@if (count($wishListProducts) !== 0 || count($wishListSets) !== 0)
  <div class="btnm buttonWish wishAdded">
    <div class="nmb">{{count($wishListProducts) + count($wishListSets)}}</div>
  </div>
@else
  <div class="btnm buttonWish">
  </div>
@endif
