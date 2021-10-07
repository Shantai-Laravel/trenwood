@extends('front.app')
@section('content')
@include('front.layouts.header')

@php
    $i = 0;
    $s = 0;
    $productsArr = [];
    if (!$products->isEmpty()) {
        foreach ($products as $key => $value) {
            if (($key + 1) % 3 == 1) {
                $i = 0;
                $s++;
            }
            $i++;
            $productsArr[$s][$i] = $value;
        }
    }
@endphp
<div class="collectionOne vintageOne products">
  <div class="collectionHeader">
    <div class="container-fluid">
      <div class="row">
        <div class="col-auto">
          <div class="crumbs">
            <ul>
              <li><a href="{{url($lang->lang)}}">{{trans('front.general.homePage')}}</a> / </li>
              <li><a href="{{url($lang->lang.'/catalog/')}}">{{trans('front.general.allProducts')}}</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="filterProducts">
      @include('front.filters.categoryFilter')
    </div>
    <div class="row justify-content-center">
      <div class="col-md-auto col-12 bag">
        {{trans('front.sort.sort')}}
      </div>
      <div class="col-md-auto col-12 text-center radSer">
        <label class="radioSearch">
          <input type="radio" class="order-products" data="actual_price" value="desc" name="radioSearch">
          <span class="checkSearch">{{trans('front.sort.high')}}</span>
        </label>
        <label class="radioSearch">
          <input type="radio" class="order-products" data="actual_price" value="asc" name="radioSearch">
          <span class="checkSearch">{{trans('front.sort.low')}}</span>
        </label>
      </div>
    </div>
  </div>
  <div class="productsTemplates container-fluid responseProducts">
      @php $i = 0; @endphp
      @if (count($productsArr) > 0)
          @foreach ($productsArr as $key => $productArr)
              @if ($key % 2 == 1)
                  @php
                      $i = 0;
                  @endphp
              @endif

              @php $i++; @endphp

              @include('front.productTemplates.template'.$i, ['products' => $productArr])
          @endforeach
      @endif

      <div class="load-more-area"></div>

      {{-- {{ dd($products->nextPageUrl()) }} --}}

      @if ($products->nextPageUrl())
       <div class="row justify-content-center">
              <div class="col-auto">
                  <a href="#" class="load-more-btn buttSilver" data-url="{{ $products->nextPageUrl() }}">{{trans('front.products.loadMore')}}</a>
              </div>
          </div>
      @endif
  </div>
</div>

@include('front.layouts.footer')
@include('front.modals.modals')
@stop
