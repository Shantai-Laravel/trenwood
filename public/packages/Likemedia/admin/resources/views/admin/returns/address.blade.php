<input type="hidden" name="addressCourier" value="{{isset($address) ? $address->id : 0}}">
@foreach ($userfields as $key => $userfield)
    @if ($userfield->field_group == 'address')
        @if ($userfield->type == 'text')
            <label for="{{$userfield->field}}">{{ucfirst($userfield->field)}}</label>
            <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
            <?php $field = $userfield->field; ?>
            <input type="{{$userfield->type}}" name="{{$userfield->field}}" class="name" id="{{$userfield->field}}" value="{{!empty($address) ? $address->$field : old($userfield->field)}}">
        @else
          <label for="{{$userfield->field}}">{{ucfirst($userfield->field)}}</label>
          <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">

          @if ($userfield->field == 'country')
              <select name="{{$userfield->field}}" class="name filterCountries" data-id="{{!empty($address) ? $address->id: '0'}}" id="{{$userfield->field}}">
                  <option disabled selected>Выберите страну</option>
                  @foreach ($countries as $onecountry)
                      <option {{!empty($address) && $address->country == $onecountry->id ? 'selected' : '' }} value="{{$onecountry->id}}">{{$onecountry->name}}</option>
                  @endforeach
              </select>
          @endif

          @if ($userfield->field == 'region')

              <select name="{{$userfield->field}}" class="name filterRegions" data-id="{{!empty($address) ? $address->id: '0'}}" id="{{$userfield->field}}">
                  <option disabled selected>Выберите регион</option>
                  @if (!empty($regions))
                      @foreach ($regions as $region)
                          @foreach ($region as $oneregion)
                              <option {{!empty($address) && $address->region == $oneregion->id ? 'selected' : '' }} value="{{$oneregion->id}}">{{$oneregion->name}}</option>
                          @endforeach
                      @endforeach
                  @endif
              </select>
          @endif

          @if ($userfield->field == 'location')
              <select name="{{$userfield->field}}" class="name filterCities" data-id="{{!empty($address) ? $address->id: '0'}}" id="{{$userfield->field}}">
                  <option disabled selected>Выберите город</option>
                  @if (!empty($cities))
                      @foreach ($cities as $city)
                          @foreach ($city as $onecity)
                              <option {{!empty($address) && $address->location == $onecity->id ? 'selected' : '' }} value="{{$onecity->id}}">{{$onecity->name}}</option>
                          @endforeach
                      @endforeach
                  @endif
              </select>
          @endif
      @endif
    @endif
@endforeach
