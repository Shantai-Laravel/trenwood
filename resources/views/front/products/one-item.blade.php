@extends('front.app')
@section('content')
@include('front.layouts.header')
<div class="collectionOne vintageOne oneItem">
  <div class="collectionHeader">
    <div class="container-fluid">
      <div class="row">
        <div class="col-auto">
          <div class="crumbs">
            <ul>
              <li><a href="{{url($lang->lang)}}">{{trans('front.general.homePage')}}</a> / </li>
              <li><a href="{{url($lang->lang.'/catalog/'.$set->alias)}}">{{$set->translationByLanguage($lang->id)->first()->name}}</a> / </li>
              <li><a href="{{url($lang->lang.'/catalog/'.$set->alias.'/'.$product->alias)}}">{{$product->translationByLanguage($lang->id)->first()->name}}</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-12">
          <div class="slideItems">
            @if (count($product->images) > 0)
                @foreach ($product->images  as $image)
                  <div>
                    <img src="{{ asset('images/products/og/'.$image->src ) }}" alt="" data-zoom-image="{{ asset('images/products/og/'.$image->src ) }}">
                  </div>
                @endforeach
            @else
                <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
            @endif
          </div>
        </div>
      </div>
      <div class="row justify-content-center changeSubProduct">
        <div class="col-lg-4 col-md-5 col-sm-8">
          <div class="titleOnHover">{{$product->translationByLanguage($lang->id)->first()->name}}</div>
          <div class="subproduct">
            @include('front.inc.product')
          </div>
          <div class="row">
            <div class="col-6">
              <div class="parentRelative" style="margin-right: auto; margin-left: auto;">
                  <div class="selSize selSizeOneItem">
                    {{trans('front.collections.select')}} {{GetParameter('size', $lang->id)}}:
                  </div>
                  <div class="selSizeOpen vintageSize">
                    <div class="sizeDelivery">
                      <div class="sizeGuide" data-toggle="modal" data-target="#modalSize">{{trans('front.general.size')}}</div>
                      <div class="deliveryGuide" data-toggle="modal" data-target="#modalDelivery">{{trans('front.general.delivery')}}</div>
                    </div>
                    @foreach ($product->subproducts as $subKey => $subproduct)
                        @foreach (json_decode($subproduct->combination) as $key => $combination)
                            @if ($key != 0)
                              <?php $property = getMultiDataList($combination, $lang->id); ?>

                              @if ($subproduct->stock > 0)
                                  <span class="sect changeSubProductOneItemSize" data-subproduct_id="{{$subproduct->id}}"><b class="sizeText">{{$property->value}}</b> - {{trans('front.general.inStock')}}</span>
                              @else
                                  <span class="sect" style="pointer-events: none;"><b class="sizeText">{{$property->value}}</b> - {{trans('front.general.notInStock')}}</span>
                              @endif

                            @endif
                        @endforeach
                    @endforeach
                  </div>
              </div>
            </div>
            <div class="col-6 text-center">
              <div class="buttSilver addToCart cartOneItem" data-product_id="{{$product->id}}">{{trans('front.general.addToCart')}}</div>
            </div>
          </div>
          <div class="row justify-content-center textOneItem">
            <div class="col-auto">
              <div class="addToWish {{$product->inWishList ? 'addedWishList' : ''}}" data-product_id="{{$product->id}}" >{{trans('front.general.addToWish')}}</div>
            </div>
            <div class="col-12 text-center beFirst">
              {{-- {!!$product->translationByLanguage($lang->id)->first()->description!!} --}}
            </div>
            <div class="col-12 text-center borders">
              {{-- <h4>{{getCeneralInfo('shippingReturns')->translationByLanguage($lang->id)->first()->name}}</h4> --}}
              <h4>{{ trans('front.products.ShippingAndReturns') }}</h4>
              <p>{{ getContactInfo('shipping_and_returns')->translationByLanguage($lang->id)->first()->value }}</p>
            </div>
            <div class="col-12 text-center borders">
              <h4>{{ trans('front.products.description') }}</h4>
              {{-- <p>{{getCeneralInfo('description')->translationByLanguage($lang->id)->first()->body}}</p> --}}
              <p>{!!$product->translationByLanguage($lang->id)->first()->description!!}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- {{ dd('fbx') }} --}}
  @if (count($product->lifestyleImages) === 5)
    <div class="productsImg">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-12">
            @if (strlen($product->video) !== 0)
              <video width="100%" height="auto" src="{{asset('videos/products/'.$product->video)}}" autoplay muted loop>
              </video>
            @endif
          </div>
          <div class="col-md-4 col-12">
            <div class="row imgAfterVideo">
              <div class="col-md-12 col-6">
                <img  class="marginBott mainImg" src="{{ asset('images/products/og/'.$product->lifestyleImages[0]->src ) }}" alt="{{$product->lifestyleImages[0]->translationByLanguage($lang->id)->first()->alt}}" title="{{$product->lifestyleImages[0]->translationByLanguage($lang->id)->first()->title}}">
              </div>
              <div class="col-md-12 col-6">
                <img class="mainImg" src="{{ asset('images/products/og/'.$product->lifestyleImages[1]->src ) }}" alt="{{$product->lifestyleImages[1]->translationByLanguage($lang->id)->first()->alt}}" title="{{$product->lifestyleImages[1]->translationByLanguage($lang->id)->first()->title}}">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="productsImg" id="scrollOneItem">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12 col-6">
                <img class="marginBott mainImg" src="{{ asset('images/products/og/'.$product->lifestyleImages[2]->src ) }}" alt="{{$product->lifestyleImages[2]->translationByLanguage($lang->id)->first()->alt}}" title="{{$product->lifestyleImages[2]->translationByLanguage($lang->id)->first()->title}}">
              </div>
              <div class="col-md-12 col-6">
                <img class="mainImg" src="{{ asset('images/products/og/'.$product->lifestyleImages[3]->src ) }}" alt="{{$product->lifestyleImages[3]->translationByLanguage($lang->id)->first()->alt}}" title="{{$product->lifestyleImages[3]->translationByLanguage($lang->id)->first()->title}}">
              </div>
            </div>
          </div>
          <div class="col-md-8 col-12">
            <img class="mainImg2 mainImg" src="{{ asset('images/products/og/'.$product->lifestyleImages[4]->src ) }}" alt="{{$product->lifestyleImages[4]->translationByLanguage($lang->id)->first()->alt}}" title="{{$product->lifestyleImages[4]->translationByLanguage($lang->id)->first()->title}}">
          </div>
        </div>
      </div>
    </div>
  @endif
  <div class="collectionSlide">
    <div class="container">
      @if (count($anotherSet) > 0)
        <div class="row">
          <div class="col-12 titleSlide">
            {{trans('front.collections.discover')}} {{$anotherSet->translationByLanguage($lang->id)->first()->name}}
          </div>
          <div class="col-12">
            <div class="slideColl">
              @foreach ($anotherSet->products as $product)
                  <div class="slideIt">
                    @if ($product->image()->first())
                        <img id="prOneBig1" src="{{ asset('images/products/og/'.$product->image()->first()->src ) }}">
                    @else
                        <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                    @endif
                    <div class="onHover">
                      <div class="addToWish {{$product->inWishList ? 'addedWishList' : ''}}" data-product_id="{{$product->id}}">{{trans('front.general.addToWish')}}</div>
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
                                            <b class="notInStock">{{trans('front.general.notInStock')}}</b>
                                          </span>
                                      @endif

                                    @endif
                                @endforeach
                            @endforeach
                          </div>
                        @endif
                      </div>
                      <a class="buttSilver addToCart" data-product_id="{{$product->id}}">{{$product->subproducts->first()->cart ? 'Added to Cart' : trans('front.general.addToCart')}}</a>
                      <a class="buttViewCollection" href="{{url($lang->lang.'/catalog/'.$anotherSet->alias.'/'.$product->alias)}}">{{trans('front.general.viewProduct')}}</a>
                    </div>
                  </div>
              @endforeach
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex justify-content-center">
              <a href="{{url($lang->lang.'/catalog/'.$anotherSet->alias)}}" class="buttViewCollection">{{trans('front.collections.viewLook')}} {{$anotherSet->translationByLanguage($lang->id)->first()->name}}</a>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>

@include('front.layouts.footer')
@include('front.modals.modals')
@stop
