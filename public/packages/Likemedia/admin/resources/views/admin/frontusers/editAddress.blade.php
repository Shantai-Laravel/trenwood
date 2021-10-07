<ul>
  <input type="hidden" name="address_id" value="{{$address->id}}">
  @foreach ($userfields as $key => $userfield)
      @if ($userfield->field_group == 'address')
          @if ($userfield->type == 'text')
              <li>
                  <label for="{{$userfield->field}}">{{ucfirst($userfield->field)}}</label>
                  <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                  <?php $field = $userfield->field; ?>
                  <input type="{{$userfield->type}}" name="{{$userfield->field}}" class="name" id="{{$userfield->field}}" value="{{$address->$field}}">
              </li>
          @else
              <li>
                  <label for="{{$userfield->field}}">{{ucfirst($userfield->field)}}</label>
                  <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">

                  @if ($userfield->field == 'country')
                      <select name="{{$userfield->field}}" class="name filterCountries" data-id="{{$address->id}}" id="{{$userfield->field}}">
                          <option disabled selected>Выберите страну</option>
                          @foreach ($countries as $onecountry)
                              <option {{!empty($address) && $address->country == $onecountry->id ? 'selected' : '' }} value="{{$onecountry->id}}">{{$onecountry->name}}</option>
                          @endforeach
                      </select>
                  @endif

                  @if ($userfield->field == 'region')
                      <select name="{{$userfield->field}}" class="name filterRegions" data-id="{{$address->id}}" id="{{$userfield->field}}">
                          <option disabled selected>Выберите регион</option>
                          @foreach ($regions as $region)
                              @foreach ($region as $oneregion)
                                  <option {{!empty($address) && $address->region == $oneregion->id ? 'selected' : '' }} value="{{$oneregion->id}}">{{$oneregion->name}}</option>
                              @endforeach
                          @endforeach
                      </select>
                  @endif

                  @if ($userfield->field == 'location')
                      <select name="{{$userfield->field}}" class="name filterCities" data-id="{{$address->id}}" id="{{$userfield->field}}">
                          <option disabled selected>Выберите город</option>
                          @foreach ($cities as $city)
                              @foreach ($city as $onecity)
                                  <option {{!empty($address) && $address->location == $onecity->id ? 'selected' : '' }} value="{{$onecity->id}}">{{$onecity->name}}</option>
                              @endforeach
                          @endforeach
                      </select>
                  @endif

              </li>
          @endif
      @endif
  @endforeach
</ul>
