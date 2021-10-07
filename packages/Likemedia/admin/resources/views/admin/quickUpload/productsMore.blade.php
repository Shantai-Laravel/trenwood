@if (!empty($products))
@foreach ($products as $key => $product)
@if (!empty($langs))
@foreach ($langs as $keyLang => $oneLang)
@if ($keyLang == 0)
<tbody class="item-row" data-id={{ $product->id }}>
<tr>
    <td rowspan="2"  class="width-auto">{{ $product->id }}</td>
    <td  class="width-auto">{{ $oneLang->lang }}</td>
    <td  rowspan="2" class="width-auto">
        <input type="text" class="input-code" data-lang="{{ $oneLang->id }}" value="{{ $product->code }}">
    </td>
    <td>
        <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="{{ $product->translationByLanguage($oneLang->id)->first()->name }}">
    </td>
    <td>
        <input type="text" class="input-body" data-lang="{{ $oneLang->id }}" value="{{ $product->translationByLanguage($oneLang->id)->first()->body }}">
    </td>
    <td rowspan="2" class="width-auto">
        <input type="number" class="input-price" value="{{ $product->price }}">
    </td>
    <td rowspan="2" class="width-auto">
        <input type="number" class="input-discount" value="{{ $product->discount }}">
    </td>
    <td rowspan="2" class="width-auto">
        <input type="number" class="input-stock" value="{{ $product->stock }}">
    </td>
    @if (!empty($properties))
    @foreach ($properties as $key => $property)
        @if (($property->type == 'select') || ($property->type == 'checkbox'))
            <td rowspan="2">
                <select name="prop[{{ $property->id }}]" class="form-control prop-input" data-id="{{ $property->id }}">
                    <option value="0">---</option>
                    @if (!empty($property->multidata)))
                    @foreach ($property->multidata as $key => $multidata)
                    <?php $value = getMultiDataList($multidata->id, 1); ?>
                    <option value="{{ $value->property_multidata_id }}" {{ getPropertiesData($product->id, $property->id) ==  $value->property_multidata_id ? 'selected' : ''  }}>{{ $value->name}} {{ $property->translationByLanguage($lang->id)->first()->unit }}</option>
                    @endforeach
                    @endif
                </select>
            </td>
        @else
            <td>
                <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="{{ getPropertiesDataByLang($product->id, $property->id, $oneLang->id) }}">
            </td>
        @endif
    @endforeach
    @endif
    <td rowspan="2">
        <button type="button" name="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gallery-modal{{ $product->id }}"> <i class="fa fa-image"></i> Imagini</button>
        <p>{{ count($product->images) }}</p>
        @include('admin::admin.quickUpload.galleryModal', ['product' => $product])
    </td>
    <td rowspan="2">
        <button type="button" name="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#subproducts-modal{{ $product->id }}"> <i class="fa fa-image"></i> Subproduse</button>
        <p>{{ count($product->subproducts) }}</p>
        @include('admin::admin.quickUpload.subproductsModal', ['product' => $product])
    </td>
    <td rowspan="2">
        <button type="button" name="button" class="btn btn-primary btn-sm subproducts-btn" data-toggle="modal" data-target="#subproducts-images-modal{{ $product->id }}"> <i class="fa fa-image"></i> Imagini Subproduse</button>
        <p> - </p>
        @include('admin::admin.quickUpload.subproductsImagesModal', ['product' => $product])
    </td>
</tr>
@else
<tr>
    <td  class="width-auto">{{ $oneLang->lang }}</td>
    <td>
        <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="{{ $product->translationByLanguage($oneLang->id)->first()->name }}">
    </td>
    <td>
        <input type="text" class="input-body"  data-lang="{{ $oneLang->id }}" value="{{ $product->translationByLanguage($oneLang->id)->first()->body }}">
    </td>
    @if (!empty($properties))
    @foreach ($properties as $key => $property)
        @if (($property->type == 'select') || ($property->type == 'checkbox'))
        @else
            <td>
                <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="{{ getPropertiesDataByLang($product->id, $property->id, $oneLang->id) }}">
            </td>
        @endif
    @endforeach
    @endif
</tr>
</tbody>
@endif
@endforeach
@endif
@endforeach
@endif
