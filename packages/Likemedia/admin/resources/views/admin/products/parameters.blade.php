<div class="row">
    @if (!empty($poperties))
    <div class="col-md-12">
        <div class="list-content part full-part">
            @foreach ($poperties as $key => $property)
            <div class="form-group">

                <label>
                    <a href="{{ route('properties.edit', [$property->id]) }}" target="_blank"><i class="fa fa-eye"></i></a>
                    {{ $property->translation->first()->name }}
                    ({{ $property->translationByLanguage($lang->id)->first()->unit }})
                    <b>{{ $property->group->translationByLanguage($lang->id)->first()->name }}</b>
                </label>

                @if (!is_array(getPropertiesData(Request::segment(3), $property->id)))
                @endif
                @if ($property->type == 'select')
                <select name="prop[{{ $property->id }}]" class="form-control">
                    <option value="0">--</option>
                    @if (!empty($property->multidata)))
                    @foreach ($property->multidata as $key => $multidata)
                        <?php $value = getMultiDataList($multidata->id, 1); ?>
                        <option value="{{ $value->property_multidata_id }}" {{ getPropertiesData(Request::segment(3), $property->id) ==  $value->property_multidata_id ? 'selected' : ''  }}>{{ $value->name}} {{ $property->translationByLanguage($lang->id)->first()->unit }}</option>
                    @endforeach
                    @endif
                </select>
            @elseif ($property->type == 'text')
                <div class="row">
                    @foreach ($langs as $key => $oneLang)
                        <div class="col-md-6">
                            [{{ $oneLang->lang }}]<input type="text" name="propText[{{ $property->id }}][{{ $oneLang->id }}]" value="{{ getPropertiesDataByLang(Request::segment(3), $property->id, $oneLang->id) }}" class="form-control">
                        </div>
                    @endforeach
                </div>

            @elseif ($property->type == 'textarea')
                <div class="row">
                    @foreach ($langs as $key => $oneLang)
                        <div class="col-md-6">
                            [{{ $oneLang->lang }}]<textarea name="propText[{{ $property->id }}][{{ $oneLang->id }}]" class="form-control">{{ getPropertiesData(Request::segment(3), $property->id, $oneLang->id) ?? $property->translation->first()->value }}</textarea>
                        </div>
                    @endforeach
                </div>
                @elseif ($property->type == 'number')
                    <input type="number" min="1" step="any" name="prop[{{ $property->id }}]" value="{{ getPropertiesData(Request::segment(3), $property->id, 0) ?? $property->translation->first()->value }}" class="form-control">
                @elseif ($property->type == 'checkbox')

                @if (!empty($property->multidata))
                <?php $chekboxArray =  getPropertiesData(Request::segment(3), $property->id, 0); ?>
                <ul>
                    @foreach ($property->multidata as $key => $multidata)
                        <?php $value = getMultiDataList($multidata->id, 1);
                              if (is_array(getPropertiesData(Request::segment(3), $property->id))) {
                                  $selected = getPropertiesData(Request::segment(3), $property->id);
                              }else{
                                  $selected = [];
                              }
                        ?>

                    <ol>
                        @if (is_array($chekboxArray))
                        <label>
                        <input class="checkbox" type="checkbox" name="prop[{{ $property->id }}][]" value="{{ $value->property_multidata_id }}"
                        {{ in_array($value->property_multidata_id, $selected) ? 'checked' : $selected[0]  }} >
                        <span>{{ $value->name }}</span>
                        </label>
                        @else
                        <label>
                        <input class="checkbox" type="checkbox" name="prop[{{ $property->id }}][]" value="{{ $value->property_multidata_id }}"
                            {{ in_array($value->property_multidata_id, $selected) ? 'checked' : $value->id  }} >
                        <span>{{ $value->name }}</span>
                        </label>
                        @endif
                    </ol>
                    @endforeach
                </ul>
                @endif
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
