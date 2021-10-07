@if (count($cartProducts) !== 0 || count($cartSets) !== 0)
  <div class="nmbCart">{{count($cartProducts) + count($cartSets)}}</div>
@endif
