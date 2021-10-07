<div class="row">
   <div class="col-12">
      <div class="row">
         <div class="col-12">
            {{count($wishListProducts) + count($wishListSets)}} {{trans('front.wishList.products')}}
         </div>

         @if (count($wishListProducts) > 0)
             @foreach ($wishListProducts as $wishListProduct)
                 <div class="col-12 wishProduct">
                    <div class="wishItem">
                       <div class="row">
                          <div class="col-md-2 col-3">
                            @if ($wishListProduct->product->withoutBack()->first())
                                <img src="{{ asset('images/products/og/'.$wishListProduct->product->withoutBack()->first()->src ) }}">
                            @else
                                <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                            @endif
                          </div>
                          <div class="col-8">
                             <div class="denWish">{{$wishListProduct->product->translationByLanguage($lang->id)->first()->name}}</div>
                             @if ($wishListProduct->subproduct)
                               <div class="txtWish">{{trans('front.wishList.stock')}}<span class="stock"> {{$wishListProduct->subproduct->stock}}</span>!</div>
                               <div class="txtWish">{{trans('front.wishList.cod')}} <strong class="code">{{$wishListProduct->subproduct->code}}</strong></div>
                             @else
                               <div class="txtWish" style="display: none;">{{trans('front.wishList.stock')}} <span class="stock"></span>!</div>
                               <div class="txtWish" style="display: none;">{{trans('front.wishList.cod')}} <strong class="code"></strong></div>
                             @endif
                             <div class="colorWish">
                               <?php
                                 $propertyValueID = getPropertiesData($wishListProduct->product->id, ParameterId('color'));
                               ?>
                               @if (!is_null($propertyValueID) && $propertyValueID !== 0)
                                 <?php
                                   $propertyValue = getMultiDataList($propertyValueID, $lang->id)->value;
                                 ?>
                                {{GetParameter('color', $lang->id)}} : {{$propertyValue}}
                               @endif
                             </div>
                             <div class="row justify-content-center">
                                <div class="col-sm-4 col-12">
                                   <div class="selWish">
                                     <select name="subproductSize" data-id="{{$wishListProduct->id}}">
                                         <option value="">{{trans('front.wishList.chooseSize')}}</option>
                                         @foreach ($wishListProduct->product->subproducts as $subKey => $subproduct)
                                             @foreach (json_decode($subproduct->combination) as $key => $combination)
                                                 @if ($key != 0)
                                                   <?php $property = getMultiDataList($combination, $lang->id); ?>

                                                   @if ($subproduct->stock > 0)
                                                       <option {{$wishListProduct->subproduct && $wishListProduct->subproduct->id === $subproduct->id ? 'selected' : ''}} value="{{$subproduct->id}}">{{$property->value}} - {{trans('front.general.inStock')}}</option>
                                                   @else
                                                       <option disabled>{{$property->value}} - {{trans('front.general.notInStock')}}</option>
                                                   @endif

                                                 @endif
                                             @endforeach
                                         @endforeach
                                       </select>
                                   </div>
                                </div>
                                <div class="col-sm-8 col-12">
                                  <a class="buttonCartLogged moveFromWishListToCart" href="#" data-id="{{$wishListProduct->id}}">{{trans('front.general.addToCart')}}</a>
                                </div>
                             </div>
                          </div>
                          <div class="col-md-2 col-12 delWishAbs">
                             <div class="delWish removeItemWishList" data-id="{{$wishListProduct->id}}"></div>
                          </div>
                       </div>
                    </div>
                 </div>
             @endforeach
         @endif

         @if (count($wishListSets) > 0)
             @foreach ($wishListSets as $wishListSet)
                 <div class="col-12">
                   <div class="wishItem wishItemSet">
                     <div class="row">
                       <div class="col-md-2 col-3">
                         @if ($wishListSet->set()->first())
                           <img src="/images/sets/og/{{ $wishListSet->set()->first()->withoutBack()->first()->src }}" alt="">
                         @else
                           <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                         @endif
                       </div>
                       <div class="col-8">
                         <div class="denWishSet"><span>{{ $wishListSet->set->translationByLanguage($lang->id)->first()->name }}{{trans('front.wishList.oneSet')}}</span></div>

                       </div>
                       <div class="col-md-2 col-12 delWishAbs">
                         <div class="delWish removeSetWishList" data-id="{{$wishListSet->id}}"></div>
                       </div>
                     </div>
                     <div class="row wishSet justify-content-center">
                       @if (count($wishListSet->wishlist) > 0)
                         @foreach ($wishListSet->wishlist as $wishListProduct)
                           <div class="col-md-11 col-12 wishProduct">
                             <div class="row justify-content-center">
                               <div class="col-md-2 col-3">
                                 @if ($wishListProduct->product->withoutBack()->first())
                                     <img src="{{ asset('images/products/og/'.$wishListProduct->product->withoutBack()->first()->src ) }}">
                                 @else
                                     <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                                 @endif
                               </div>
                               <div class="col-8 setDesc">
                                 <div class="row">
                                   <div class="col-12">
                                     <div class="denWish">{{$wishListProduct->product->translationByLanguage($lang->id)->first()->name}}</div>
                                   </div>
                                 </div>
                                 @if ($wishListProduct->subproduct)
                                   <div class="txtWish">{{trans('front.wishList.stock')}}<span class="stock"> {{$wishListProduct->subproduct->stock}}</span>!</div>
                                   <div class="txtWish">{{trans('front.wishList.cod')}} <strong class="code"> {{$wishListProduct->subproduct->code}}</strong></div>
                                 @else
                                   <div class="txtWish" style="display: none;">{{trans('front.wishList.stock')}} <span class="stock"></span>!</div>
                                   <div class="txtWish" style="display: none;">{{trans('front.wishList.cod')}} <strong class="code"></strong></div>
                                 @endif
                                 <div class="colorWish">
                                     <?php
                                       $propertyValueID = getPropertiesData($wishListProduct->product->id, ParameterId('color'));
                                     ?>
                                     @if (!is_null($propertyValueID) && $propertyValueID !== 0)
                                       <?php
                                         $propertyValue = getMultiDataList($propertyValueID, $lang->id)->value;
                                       ?>
                                      {{GetParameter('color', $lang->id)}} : {{$propertyValue}}
                                     @endif
                                 </div>
                                 <div class="row">
                                   <div class="col-sm-4 col-12">
                                     <div class="selWish">
                                       <select name="subproductSize" data-id="{{$wishListProduct->id}}">
                                         <option value="">{{trans('front.wishList.chooseSize')}}</option>
                                         @foreach ($wishListProduct->product->subproducts as $subKey => $subproduct)
                                             @foreach (json_decode($subproduct->combination) as $key => $combination)
                                                 @if ($key != 0)
                                                   <?php $property = getMultiDataList($combination, $lang->id); ?>

                                                   @if ($subproduct->stock > 0)
                                                       <option {{$wishListProduct->subproduct && $wishListProduct->subproduct->id === $subproduct->id ? 'selected' : ''}} value="{{$subproduct->id}}">{{$property->value}} - {{trans('front.general.inStock')}}</option>
                                                   @else
                                                       <option disabled>{{$property->value}} - {{trans('front.general.notInStock')}}</option>
                                                   @endif

                                                 @endif
                                             @endforeach
                                         @endforeach
                                       </select>
                                     </div>
                                   </div>
                                 </div>
                               </div>
                             </div>
                           </div>
                         @endforeach
                       @endif
                     </div>
                     <div class="row">
                       <div class="col-sm-8 col-12">
                         <a class="buttonCartLogged moveSetFromWishListToCart" href="#" data-id="{{$wishListSet->id}}">{{trans('front.general.addToCart')}}</a>
                       </div>
                     </div>
                   </div>
                 </div>
             @endforeach
         @endif
      </div>
   </div>
</div>
