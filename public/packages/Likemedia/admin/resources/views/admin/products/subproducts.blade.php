<hr>
<h6 class="text-center">Subproducts</h6>
@if (count($product->subproducts()->get()) > 0)
  @foreach ($product->subproducts()->get() as $key => $subproduct)
    <div class="col-md-4">
    <ul>
      <li>
        <hr>
        <h6>
            <input type="checkbox" name="subproduct_active[]" value="{{ $subproduct->id }}" {{ $subproduct->active ? 'checked' : '' }}>
            <small># {{ $key+1 }} subproduct, code -</small> {{ $subproduct->code }}
        </h6>
      </li>

      <?php
        $properties = $product->property()->where('show_property', 1)->orderBy('status', 'asc')->get();
        static $keyid = 0;
      ?>
      @if(!empty($properties))
        @foreach($properties as $key1 => $propertyItem)
          @if ($propertyItem->status == 'dependable')
              <?php  $key1++;  ?>
          <li>
            <?php $property = $propertyItem->property()->first();?>
            @if (!is_null($subproduct->combinationItem()->first()))

                <label>{{$property->translationByLanguage($lang->id)->first()->name}} <small>[dependable]</small> </label>
                <?php $value = getMultiDataList($subproduct->combinationItem()->first()->{ 'case_'. $key1 }, 1); ?>

                <input type="text" disabled name="" value="{{ $value->value }}">
                @if ($propertyItem->image == 1)
                    <div class="row">
                        <div class="col-md-7">
                            <input type="file" name="prop_images[{{ $subproduct->id }}]" value="">
                        </div>
                        <div class="col-md-5 text-center">
                            @if ($subproduct->image()->first())
                                <img height="40px" src="/images/subproducts/og/{{ $subproduct->image()->first()->src }}" alt="">
                            @else
                                <img height="40px" src="/images/empty.png" alt="">
                            @endif
                        </div>
                    </div>
                @endif
            @endif

          </li>
          @else
            <?php $property = $propertyItem->property()->first();?>
            @if(!is_null($property))
              <li>
                <label>{{$property->translationByLanguage($lang->id)->first()->name}} <small>[insertable]</small></label>
                <select name="subproductProp[{{ $subproduct->id }}][{{ $property->id }}]" class="form-control prop-input" data-id="{{ $property->id }}">
                    @if (!empty($property->multidata))
                      @foreach ($property->multidata as $key => $multidata)
                      <?php $value = getMultiDataList($multidata->id, 1); ?>
                        @if (!is_null($subproduct->value($property->id)->first()))
                          <option value="{{ $value->property_multidata_id }}" {{ $subproduct->value($property->id)->first()->property_value_id ==  $value->property_multidata_id ? 'selected' : ''  }}>{{ $value->value }} </option>
                        @else
                          <option value="{{ $value->property_multidata_id }}">{{ $value->value }}</option>
                        @endif
                      @endforeach
                    @endif
                </select>
              </li>

            @endif
          @endif
        @endforeach
      @endif

      <li>
        <label for="">Price lei<small>[insertable]</small> </label>
        <input type="number" class="form-control" name="subprod[{{$subproduct->id}}][price]" value="{{intval($subproduct->price)}}">
      </li>
      <li>
        <label for="">Discount %<small>[insertable]</small> </label>
        <input type="number" class="form-control" name="subprod[{{$subproduct->id}}][discount]" value="{{intval($subproduct->discount)}}">
      </li>
      <li>
        <label for="">Stock <small>[insertable]</small> </label>
        <input type="number" class="form-control" name="subprod[{{$subproduct->id}}][stock]" value="{{intval($subproduct->stock)}}">
      </li>
    </ul>
</div>

  @endforeach
@endif
