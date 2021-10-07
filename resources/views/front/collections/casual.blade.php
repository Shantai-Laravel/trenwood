@extends('front.app')
@section('content')
@include('front.layouts.header')
@if (count($set) > 0)
    @php
        $neiberSet = $set->collection->nieberSet($set->id)->first();
    @endphp
  <div class="collectionOne casualOne">
    <div class="collectionDescktop homeContent">
      <section  class="casualSection">
        <div class="titleNew text-center">
           <h2>{{ mb_strtoupper(trans('front.collections.discover_')) }} {{ mb_strtoupper($set->translationByLanguage($lang->id)->first()->name) }} COLLECTION</h2>
        </div>
        <div class="collectionInner">
            <div class="videoContainer">
              <video autoplay="autoplay"   loop="loop" muted defaultMuted playsinline>
                <source src="{{asset('videos/sets/'.$set->video->src)}}" type="video/mp4">
              </video>
            </div>
            {{-- <div class="asideBloc">
              <a href="{{url($lang->lang.'/catalog/'.$neiberSet->alias)}}" class="imgBloc">
                @if ($set->mainPhoto)
                    <img id="prOneBig1" src="{{ asset('images/sets/og/'.$set->mainPhoto->src ) }}">
                @else
                    <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                @endif
              </a>
                <div class="casualAside">
                  <div class="titleNew">
                    {{$neiberSet->translationByLanguage($lang->id)->first()->name}}
                  </div>
                  <p>{{ str_limit($neiberSet->translationByLanguage($lang->id)->first()->description, $limit = 150, $end = '...')}}</p>
                  <a href="{{url($lang->lang.'/catalog/'.$neiberSet->alias)}}" class="butt buttArrow">
                    {{trans('front.general.discover')}}
                  </a>
                </div>
            </div> --}}
            <div  class=" asideBloc">
                 <a href="{{url($lang->lang.'/catalog/'.$set->alias.'/'.$set->products->first()->alias)}}" class="imgBloc">
                   @if ($set->products->first()->mainImage()->first())
                   <img src="{{ asset('images/products/og/'.$set->products->first()->mainImage()->first()->src ) }}">
                   @else
                       <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                   @endif
                 </a>
                 <a href="{{url($lang->lang.'/catalog/'.$set->alias.'/'.$set->products->first()->alias)}}" class="titleNew">{{$set->products->first()->translationByLanguage($lang->id)->first()->name}}</a>
                 <p>
                   {!! strip_tags($set->products->first()->translationByLanguage($lang->id)->first()->description) !!}
                 </p>
           </div>
        </div>
      </section>
    </div>
    <div class="collectionsMobile">
      <div class="casualMobile">
        <div class="row">
          <div class="col-12 titleCllection">
            Casual collection
          </div>
          <div class="col-12">
            @if ($set->mainPhoto)
                <img class="noneMobile" id="prOneBig1" src="{{ asset('images/sets/og/'.$set->mainPhoto->src ) }}">
            @else
                <img class="noneMobile" src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
            @endif
            <div class="videoBlock">
              <video width="100%" height="auto" src="{{asset('videos/sets/'.$set->video->src)}}" autoplay muted loop>
              </video>
              <div class="zaglushka"></div>
            </div>
            <div class="title">{{$neiberSet->translationByLanguage($lang->id)->first()->name}}</div>
            {{-- <p>{{$set->translationByLanguage($lang->id)->first()->description}}</p> --}}
            <a href="{{url($lang->lang.'/catalog/'.$neiberSet->alias)}}" class="butt buttArrow">
              {{trans('front.general.discover')}}
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-auto">
          <div class="crumbs">
            <ul>
              <li><a href="{{url($lang->lang)}}">{{trans('front.general.homePage')}}</a> / </li>
              <li><a href="{{url($lang->lang.'/catalog/'.$set->alias)}}">{{$set->translationByLanguage($lang->id)->first()->name}}</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="collectionDescription">
        <h3 class="titleDescr">{{$set->translationByLanguage($lang->id)->first()->name}}</h3>
        <div class="container"><p>{{$set->translationByLanguage($lang->id)->first()->description}} </p></div>
    </div>
    <div class="collectionOneItems">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-7 col-md-6 col-12">
              @if (count($set->photos) > 0)
                <div class="row justify-content-center">
                  <div class="col-12">
                    <div class="mainSlide">
                      @foreach ($set->photos as $image)
                        <div>
                          <img class="zoom" src="{{asset('images/sets/og/'.$image->src)}}" alt="" data-zoom-image="{{asset('images/sets/og/'.$image->src)}}">
                        </div>
                      @endforeach
                    </div>
                  </div>
                  <div class="col-md-10 col-12">
                    <div class="slideNav">
                      @foreach ($set->photos as $image)
                        <div>
                          <img src="{{asset('images/sets/og/'.$image->src)}}" alt="">
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endif
          </div>
          <div class="col-lg-5 col-md-6 col-12 subproducts">
            @include('front.inc.products')
          </div>
        </div>
      </div>
    </div>
    <div class="collectionSlide">
      <div class="container-fluid">
        @if (count($anotherSet) > 0)
          <div class="row">
            <div class="col-12 titleSlide">
              {{trans('front.collections.discover')}} {{$anotherSet->translationByLanguage($lang->id)->first()->name}}
            </div>
            <div class="col-12">
              <div class="slideColl">
                @foreach ($anotherSet->products as $product)
                  <div class="slideIt changeSubProduct">
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
                                            <b class="notInStock">{{trans('front.general.inStock')}}</b>
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
@endif
@include('front.layouts.footer')
@include('front.modals.modals')
@stop
