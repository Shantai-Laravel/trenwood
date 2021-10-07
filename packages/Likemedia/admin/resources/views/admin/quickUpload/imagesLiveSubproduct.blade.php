@if (!is_null($category->propertyMain->property))
    <label class="text-center">Parametrul Main - {{ $category->propertyMain->property->translationByLanguage(1)->first()->name }}</label>
    <div class="row"><br><br>
    @if (count($category->propertyMain->property->multidata))
        @foreach ($category->propertyMain->property->multidata as $key => $pramValue)
            <div class="col-md-4"><br>
                <p class="text-center"><b>{{ $pramValue->translationByLanguage(1)->first()->name }}</b></p>
                <input type="file" name="subprod_image[{{ $pramValue->id }}]" value="" onchange="preview_image_one(this);">
                <div class="image_preview">
                    @if (!is_null($pramValue->subProduct($product->id, $pramValue->id)))

                    @if ($pramValue->subProduct($product->id, $pramValue->id)->image()->first())
                        <img height="40px" src="/images/subproducts/og/{{ $pramValue->subProduct($product->id, $pramValue->id)->image()->first()->src }}" alt="">
                    @else
                        <img height="40px" src="/images/empty.png" alt="">
                    @endif
                @endif
                </div>
                <hr>
            </div>
        @endforeach
    @endif
</div>
@endif
