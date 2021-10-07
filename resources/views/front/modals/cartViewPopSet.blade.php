<div class="row justify-content-center cartBl">
    <div class="col-12 text-right">
        Shoping Cart ({{ count($cartProducts) + count($cartSets) }})
    </div>
    <div class="col-lg-4 col-6">
        @if ($cartSet->set->withoutBack()->first())
        <img src="{{ asset('/images/sets/md/'.$cartSet->set->withoutBack()->first()->src ) }}" alt="">
        @else
        <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
        @endif
    </div>
    <div class="col-lg-6 col-6 descrItemCart">
      <div>{{ $cartSet->set->translationByLanguage($lang->id)->first()->name }}</div>
      {{-- <div>Pret: <b>{{ $set->price_lei }} Lei</b></div> --}}
    </div>
</div>
