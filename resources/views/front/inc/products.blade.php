<?php
  $amount = 0;
  $addToSet = true;
?>
@if (count($set->products) > 0)
  <div class="collectionAside">
    <div class="asideTitle">{{trans('front.collections.look')}} {{$set->translationByLanguage($lang->id)->first()->name}}</div>
    <div class="asideItems">
      @foreach ($set->products as $key => $product)
        <div class="asideItem">
          <a href="{{url($lang->lang.'/catalog/'.$set->alias.'/'.$product->alias)}}">
            @if ($product->image()->first())
                <img src="{{ asset('images/products/og/'.$product->image()->first()->src ) }}">
            @else
                <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
            @endif
          </a>
          <div class="asideDescr">
            <div><a href="{{url($lang->lang.'/catalog/'.$set->alias.'/'.$product->alias)}}">{{$product->translationByLanguage($lang->id)->first()->name}}</a></div>
            <?php
              $propertyValueID = getPropertiesData($product->id, ParameterId('color'));
            ?>
            @if (!is_null($propertyValueID) && $propertyValueID !== 0)
              <?php
                $propertyValue = getMultiDataList($propertyValueID, $lang->id)->value;
              ?>
              <div class="d-flex blockItem"><div>{{GetParameter('color', $lang->id)}}:</div> <span>{{$propertyValue}}</span></div>
            @endif
            <div class="d-flex blockItem">
              <div>{{ ucfirst(trans('front.products.price')) }}:</div>
              @if (isset(session()->get('subproductsId')[$product->id]))
                <?php
                  $subproductId = session()->get('subproductsId')[$product->id];
                  $productsId['subprods'][] = $product->subproductById($subproductId)->first()->id;

                  $amount += $product->subproductById($subproductId)->first()->price - ($product->subproductById($subproductId)->first()->price * $product->subproductById($subproductId)->first()->discount / 100);
                ?>
                <span>{{$product->subproductById($subproductId)->first()->price - ($product->subproductById($subproductId)->first()->price * $product->subproductById($subproductId)->first()->discount / 100)}} {{trans('front.general.currency')}}</span>
              @else
                <?php
                  $productsId['prods'][] = $product->id;
                  $addToSet = false;

                  $amount += $product->price - ($product->price * $product->discount / 100);
                ?>
                <span>{{$product->price - ($product->price * $product->discount / 100)}} {{trans('front.general.currency')}}</span>
              @endif
            </div>
            <div class="parentRelative">
              @if (count($product->subproducts) > 0)
                <div class="selSize">
                 {{trans('front.collections.select')}}  {{GetParameter('size', $lang->id)}}:
                </div>
                <div class="selSizeOpen {{$set->collection->alias}}Size">
                  <div class="sizeDelivery">
                    <div class="sizeGuide" data-toggle="modal" data-target="#modalSize">{{trans('front.general.size')}}</div>
                    <div class="deliveryGuide" data-toggle="modal" data-target="#modalDelivery">{{trans('front.general.delivery')}}</div>
                  </div>
                  @foreach ($product->subproducts as $subKey => $subproduct)
                      @foreach (json_decode($subproduct->combination) as $key => $combination)
                          @if ($key != 0)
                            <?php $property = getMultiDataList($combination, $lang->id); ?>

                            @if ($subproduct->stock > 0)
                              @if (isset(session()->get('subproductsId')[$product->id]))
                                <span class="sect {{session()->get('subproductsId')[$product->id] === $subproduct->id ? 'checked' : ''}} changeSubProductSize" data-product_id="{{$product->id}}" data-subproduct_id="{{$subproduct->id}}"><b class="sizeText">{{$property->value}}</b> - {{trans('front.general.inStock')}}</span>
                              @else
                                <span class="sect changeSubProductSize" data-product_id="{{$product->id}}" data-subproduct_id="{{$subproduct->id}}"><b class="sizeText">{{$property->value}}</b> - {{trans('front.general.inStock')}}</span>
                              @endif
                            @else
                                <span class="sect changeSubProductSize" style="pointer-events: none;"><b class="sizeText">{{$property->value}}</b> - {{trans('front.general.notInStock')}}</span>
                            @endif

                          @endif
                      @endforeach
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="row price priceSet">
      <div class="col-auto">{{trans('front.collections.setPrice')}}</div>
      <div class="col bb"></div>
      <div class="col-auto" style="padding-right: 0"><span class="reduce">{{$amount}}</span> {{$set->price}} {{trans('front.general.currency')}}</div>
    </div>
    <div class="buttWith">
      @if ($addToSet)
        <a class="buttSilver addSetToCart" data-subproducts_id="{{!empty($productsId['subprods']) ? json_encode($productsId['subprods']) : ''}}" data-set_id="{{$set->id}}"></a>
      @else
        <a class="buttSilver addSetToCart"></a>
      @endif
      <a class="buttSilver addSetToWish {{$set->inWishList ? 'addedWishList' : ''}}" data-set_id="{{$set->id}}"></a>
    </div>
  </div>
@endif
