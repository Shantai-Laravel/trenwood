

@if ((count($wishListProducts) > 0) || (count($wishListSets) > 0))
    <div class="col-12 buttonsScroll">
     <div id="btnTopWish">
     </div>
    </div>
@endif

@if ((count($wishListProducts) > 0) || (count($wishListSets) > 0))


<div class="col-12 wishListBox">
  <div class="row wrappCart">
    <div class="wishScrollBlock col-12">
      @if (count($wishListProducts) > 0)
          @foreach ($wishListProducts as $wishListProduct)
              <div class="row justify-content-center">
                <div class="col-10">
                  <div class="itemCart">
                    <div class="row">
                      <div class="col-3">
                        @if ($wishListProduct->product->withoutBack()->first())
                            <img src="{{ asset('images/products/og/'.$wishListProduct->product->withoutBack()->first()->src ) }}">
                        @else
                            <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                        @endif
                      </div>
                      <div class="col-9">
                        <div class="nameProduct">{{$wishListProduct->product->translationByLanguage($lang->id)->first()->name}}</div>
                        @if ($wishListProduct->subproduct)
                          <div class="stoc">{!!trans('front.header.stock', ['qty' => $wishListProduct->subproduct->stock])!!}</div>
                          <div class="cod">{{trans('front.header.cod')}} <span>{{$wishListProduct->subproduct->code}}</span></div>
                        @endif
                      </div>
                    </div>
                    <div class="deleteItemCart removeItemWishList" data-id="{{$wishListProduct->id}}" data-product_id="{{$wishListProduct->product->id}}"></div>
                  </div>
                </div>
              </div>
          @endforeach
      @endif

      @if (count($wishListSets) > 0)
          @foreach ($wishListSets as $wishListSet)
              <div class="row justify-content-center">
                <div class="col-10">
                  <div class="itemCart">
                    <div class="row">
                      <div class="col-3">
                        @if ($wishListSet->set()->first())
                          <img src="/images/sets/og/{{ $wishListSet->set()->first()->withoutBack()->first()->src }}" alt="">
                        @else
                          <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                        @endif
                      </div>
                      <div class="col-9">
                        <div class="nameProduct">{{$wishListSet->set()->first()->translationByLanguage($lang->id)->first()->name}}</div>
                      </div>
                    </div>
                    <div class="deleteItemCart removeSetWishList" data-set_id="{{$wishListSet->set_id}}" data-id="{{$wishListSet->id}}"></div>
                  </div>
                </div>
              </div>
          @endforeach
      @endif
    </div>
  </div>
</div>


@endif

@if ((count($wishListProducts) > 0) || (count($wishListSets) > 0))
<div class="col-12 buttonsScroll">
 <div id="btnBottomWish">
 </div>
</div>
@endif


@if (count($wishListProducts) === 0 && count($wishListSets) === 0)
{{trans('front.general.buyText')}}
@endif
