<thead>
    <tr>
        <th class="width-auto">#</th>
        <th class="width-auto">Lang</th>
        <th>Titlu <small>*required</small> </th>
        <th>Descriere</th>
        <th class="width-auto">Pret</th>
        <th class="width-auto">Discount</th>
        <th>Brand</th>
        <th>Promotie</th>
        @if (!empty($properties))
        @foreach ($properties as $key => $property)
        <th>{{ $property->translation->first()->name }}</th>
        @endforeach
        @endif
        <th>Info Aditional</th>
        <th>Imagini</th>
    </tr>
</thead>
<tbody>
    @if (!empty($products))
    @foreach ($products as $key => $product)
    @if (!empty($langs))
    @foreach ($langs as $keyLang => $oneLang)
    @if ($keyLang == 0)
    <tbody class="item-row" data-id={{ $product->id }}>
    <tr>
        <td rowspan="2"  class="width-auto">{{ $key + 1 }}</td>
        <td  class="width-auto">{{ $oneLang->lang }}</td>
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
        <td rowspan="2">
            <select class="form-control input-brand_id">
                <option value="0">---</option>
                @if (count($brands) > 0)
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->translationByLanguage($lang->id)->first()->name }}</option>
                @endforeach
                @endif
            </select>
        </td>
        <td rowspan="2">
            <select class="form-control input-promo_id">
                <option value="0">---</option>
                @if (count($promotions) > 0)
                @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}" {{ $product->promotion_id == $promotion->id ? 'selected' : '' }}>{{ $promotion->translationByLanguage($lang->id)->first()->name }}</option>
                @endforeach
                @endif
            </select>
        </td>
        @if (!empty($properties))
        @foreach ($properties as $key => $property)
        <td rowspan="2">
            @if ($property->type == 'select')
            <select name="prop[{{ $property->id }}]" class="form-control prop-input" data-id="{{ $property->id }}">
                <option value="0">---</option>
                @if (!empty($property->multidata)))
                @foreach ($property->multidata as $key => $multidata)
                <?php $value = getMultiDataList($multidata->id, 1); ?>
                <option value="{{ $value->property_multidata_id }} " {{ getPropertiesData($product->id, $property->id) ==  $value->property_multidata_id ? 'selected' : ''  }}>{{ $value->name}} {{ $property->translationByLanguage($lang->id)->first()->unit }}</option>
                @endforeach
                @endif
            </select>
            @endif
        </td>
        @endforeach
        @endif
        <td rowspan="2">
            <input type="file" data-name="files" value="">
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
    </tr>
    </tbody>
    @endif
    @endforeach
    @endif
    @endforeach
    @endif


    <tbody class="item-row">
    @foreach ($langs as $keyLang => $oneLang)
    @if ($keyLang == 0)
    <tr>
        <td rowspan="2"  class="width-auto"><small><i>new</i></small></td>
        <td  class="width-auto">{{ $oneLang->lang }}</td>
        <td>
            <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="">
        </td>
        <td>
            <input type="text" class="input-body" data-lang="{{ $oneLang->id }}" value="">
        </td>
        <td rowspan="2" class="width-auto">
            <input type="number" class="input-price" value="">
        </td>
        <td rowspan="2" class="width-auto">
            <input type="number" class="input-discount" value="">
        </td>
        <td rowspan="2">
            <select class="form-control input-brand_id">
                <option value="0">---</option>
                @if (count($brands) > 0)
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->translationByLanguage($lang->id)->first()->name }}</option>
                @endforeach
                @endif
            </select>
        </td>
        <td rowspan="2">
            <select class="form-control input-promo_id">
                <option value="0">---</option>
                @if (count($promotions) > 0)
                @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}">{{ $promotion->translationByLanguage($lang->id)->first()->name }}</option>
                @endforeach
                @endif
            </select>
        </td>
        @if (!empty($properties))
        @foreach ($properties as $key => $property)
        <td rowspan="2">
            @if ($property->type == 'select')
            <select name="prop[{{ $property->id }}]" class="form-control prop-input" data-id="{{ $property->id }}">
                <option value="0">---</option>
                @if (!empty($property->multidata)))
                @foreach ($property->multidata as $key => $multidata)
                <?php $value = getMultiDataList($multidata->id, 1); ?>
                <option value="{{ $value->property_multidata_id }}" {{ getPropertiesData(Request::segment(3), $property->id) ==  $value->property_multidata_id ? 'selected' : ''  }}>{{ $value->name}} {{ $property->translationByLanguage($lang->id)->first()->unit }}</option>
                @endforeach
                @endif
            </select>
            @endif
        </td>
        @endforeach
        @endif
        <td rowspan="2">
            <input type="file" data-name="files" value="">
        </td>
    </tr>
    @else
    <tr>
        <td  class="width-auto">{{ $oneLang->lang }}</td>
        <td>
            <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="">
        </td>
        <td>
            <input type="text" class="input-body" data-lang="{{ $oneLang->id }}" value="">
        </td>
    </tr>
    @endif
    @endforeach

    </tbody>


</tbody>
