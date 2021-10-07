@if (count($findProducts) > 0)
    @foreach ($findProducts as $findProduct)

      @php
        $product = $findProduct;
      @endphp

      <div class="col-xl-3 col-lg-4 col-md-6 col-12">
        <div class="searchItem changeSubProduct">
          <div class="slideIt">
            @if ($product->withoutBack()->first())
                <img id="prOneBig1" src="{{ asset('images/products/og/'.$product->withoutBack()->first()->src ) }}">
            @else
                <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
            @endif
            <div class="onHover">
              <div class="addToWish {{$product->inWishList ? 'addedWishList' : ''}}" data-product_id="{{$product->id}}">{{trans('front.general.addToWish')}}</div>
              <div class="titleOnHover">{{$product->translationByLanguage($lang->id)->first()->name}}</div>
              <div class="subproduct">
                @include('front.inc.product')
              </div>
              <div class="parentRelative dropdownSize">
                @if (count($product->subproducts) > 0)
                  <div class="selSize">
                   {{trans('front.collections.select')}}  {{GetParameter('size', $lang->id)}}:
                  </div>
                  <div class="selSizeOpen">
                    <div class="sizeDelivery">
                      <div class="sizeGuide" data-toggle="modal" data-target="#modalSize">{{trans('front.general.size')}}</div>
                      <div class="deliveryGuide" data-toggle="modal" data-target="#modalDelivery">{{trans('front.general.delivery')}}</div>
                    </div>
                    @foreach ($product->subproducts as $subKey => $subproduct)
                        @foreach (json_decode($subproduct->combination) as $key => $combination)
                            @if ($key != 0)
                              @php $property = getMultiDataList($combination, $lang->id); @endphp

                              @if ($subproduct->stock > 0)
                                <span class="sect changeSubProductOneItemSize" data-subproduct_id="{{$subproduct->id}}">
                                  <b class="sizeLetter sizeText">{{$property->value}}</b>
                                  <b class="stocNumber">{{trans('front.general.inStock')}}</b>
                                </span>
                              @else
                                  <span class="sect" style="pointer-events: none;">
                                    <b class="sizeLetter sizeText">{{$property->value}}</b>
                                    <b class="notInStock">{{trans('front.general.notInStock')}}</b>
                                  </span>
                              @endif

                            @endif
                        @endforeach
                    @endforeach
                  </div>
                @endif
              </div>
              <a data-product_id="{{$product->id}}" class="buttSilver addToCart">{{trans('front.general.addToCart')}}</a>
              <a class="buttViewCollection" href="{{url($lang->lang.'/catalog/'.$product->setProduct->set->alias.'/'.$product->alias)}}">{{trans('front.general.viewProduct')}}</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
@endif

@if (count($findSets) > 0)
    @foreach ($findSets as $findSet)
      <div class="col-xl-3 col-lg-4 col-md-6 col-12">
        <div class="searchItem changeSubProduct">
          <div class="slideIt">
            @if ($findSet->withoutBack()->first())
            <img src="/images/sets/og/{{ $findSet->withoutBack()->first()->src }}" alt="">
            @else
            <img src="{{ asset('/images/no-image.png') }}" alt="">
            @endif
            <div class="onHover">
              <div class="addSetToWish {{$findSet->inWishList ? 'addedWishList' : ''}}" data-set_id="{{$findSet->id}}">{{trans('front.general.addToWish')}}</div>
              <div class="titleOnHover">{{$findSet->translationByLanguage($lang->id)->first()->name }}</div>
              <div class="row priceProductOne justify-content-center align-items-center">
                <div class="col-auto">{{$findSet->price}} {{trans('front.general.currency')}}</div>
                @if ($findSet->discount > 0)
                  <div class="col-auto reduce">
                    {{$findSet->price - ($findSet->price * $findSet->discount / 100)}} {{trans('front.general.currency')}}
                  </div>
                @endif
              </div>

              <a href="{{url($lang->lang.'/catalog/'.$findSet->alias)}}" class="buttSilver">{{trans('front.general.viewSet')}}</a>
              <a class="buttViewCollection" href="{{url($lang->lang.'/catalog/'.$findSet->alias)}}">{{trans('front.general.viewSet')}}</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
@endif

@if (count($findProducts) === 0 && count($findSets) === 0)
  <div class="col-12">
    <h4 class="text-center">{{trans('front.search.noResults')}}</h4>
  </div>
@endif
