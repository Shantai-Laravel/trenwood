<div class="templateOne row justify-content-center">
  @foreach ($products as $key => $product)
      @if ($key === 1)
        <div class="productOne col-lg-7">
          @if ($product->video !== null)
            <a href="{{url($lang->lang.'/catalog/'.$product->setProduct->set->alias.'/'.$product->alias)}}">
              <div class="videoBlock">
                <video width="100%" height="auto" src="{{asset('videos/products/'.$product->video)}}" autoplay muted loop>
                </video>
                <div class="zaglushka"></div>
              </div>
            </a>
          @endif
        </div>
      @endif

      @if ($key === 2)
        <div class="productTwo col-sm-6 col-12">
          <div class="slideIt changeSubProduct">
            @if ($product->mainImage()->first())
                <img src="{{ asset('images/products/og/'.$product->mainImage()->first()->src ) }}">
            @else
                <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
            @endif
            <div class="onHover">
                <div class="addToWish {{$product->inWishList ? 'addedWishList' : ''}}" data-product_id="{{$product->id}}" data-subproduct_id="{{$product->subproducts->first()->id}}">{{trans('front.general.addToWish')}}</div>
              <div class="titleOnHover">{{$product->translationByLanguage($lang->id)->first()->name}}</div>
              <div class="row price priceProduct subproduct">
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
                              <?php $property = getMultiDataList($combination, $lang->id); ?>

                              @if ($subproduct->stock > 0)
                                <span class="sect changeSubProductOneItemSize" data-subproduct_id="{{$subproduct->id}}">
                                  <b class="sizeLetter sizeText">{{$property->value}}</b>
                                  <b class="stocNumber">{{trans('front.general.inStock')}}</b>
                                </span>
                              @else
                                  <span class="sect" style="pointer-events: none;">
                                    <b class="sizeLetter">{{$property->value}}</b>
                                    <b class="stocNumber">{{trans('front.general.inStock')}}</b>
                                  </span>
                              @endif

                            @endif
                        @endforeach
                    @endforeach
                  </div>
                @endif
              </div>
              <a class="buttSilver addToCart" data-product_id="{{$product->id}}">{{trans('front.general.addToCart')}}</a>
              <a class="buttViewCollection" href="{{url($lang->lang.'/catalog/'.$product->setProduct->set->alias.'/'.$product->alias)}}">{{trans('front.general.viewProduct')}}</a>
            </div>
          </div>
        </div>
      @endif

      @if ($key === 3)
        <div class="productThree col-sm-6 col-12">
          <div class="slideIt changeSubProduct">
            @if ($product->mainImage()->first())
                <img src="{{ asset('images/products/og/'.$product->mainImage()->first()->src ) }}">
            @else
                <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
            @endif
            <div class="onHover">
              <div class="addToWish {{$product->inWishList ? 'addedWishList' : ''}}" data-product_id="{{$product->id}}" data-subproduct_id="{{$product->subproducts->first()->id}}">{{trans('front.general.addToWish')}}</div>
              <div class="titleOnHover">{{$product->translationByLanguage($lang->id)->first()->name}}</div>
              <div class="row price priceProduct subproduct">
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
                              <?php $property = getMultiDataList($combination, $lang->id); ?>

                              @if ($subproduct->stock > 0)
                                <span class="sect changeSubProductOneItemSize" data-subproduct_id="{{$subproduct->id}}">
                                  <b class="sizeLetter sizeText">{{$property->value}}</b>
                                  <b class="stocNumber">{{trans('front.general.inStock')}}</b>
                                </span>
                              @else
                                  <span class="sect" style="pointer-events: none;">
                                    <b class="sizeLetter">{{$property->value}}</b>
                                    <b class="stocNumber">{{trans('front.general.inStock')}}</b>
                                  </span>
                              @endif

                            @endif
                        @endforeach
                    @endforeach
                  </div>
                @endif
              </div>
              <a class="buttSilver addToCart" data-product_id="{{$product->id}}">{{trans('front.general.addToCart')}}</a>
              <a class="buttViewCollection" href="{{url($lang->lang.'/catalog/'.$product->setProduct->set->alias.'/'.$product->alias)}}">{{trans('front.general.viewProduct')}}</a>
            </div>
          </div>
        </div>
      @endif
  @endforeach
</div>
