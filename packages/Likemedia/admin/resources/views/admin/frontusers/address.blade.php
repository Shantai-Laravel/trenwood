<ul>
  @foreach ($userfields as $key => $userfield)
      @if ($userfield->field_group == 'address')
          @if ($userfield->type == 'text')
              <li>
                  <label for="{{$userfield->field}}">{{ucfirst($userfield->field)}}</label>
                  <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                  <input type="{{$userfield->type}}" name="{{$userfield->field}}" class="name" id="{{$userfield->field}}" value="{{old($userfield->field)}}">
              </li>
          @else
              <li>
                  <label for="{{$userfield->field}}">{{ucfirst($userfield->field)}}</label>
                  <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">

                  @if ($userfield->field == 'country')
                      <select name="{{$userfield->field}}" class="name filterCountries" data-id="0" id="{{$userfield->field}}">
                          <option disabled selected>Выберите страну</option>
                          @foreach ($countries as $onecountry)
                              <option value="{{$onecountry->id}}">{{$onecountry->name}}</option>
                          @endforeach
                      </select>
                  @endif

                  @if ($userfield->field == 'region')
                      <select name="{{$userfield->field}}" class="name filterRegions" data-id="0" id="{{$userfield->field}}">
                          <option disabled selected>Выберите регион</option>
                      </select>
                  @endif

                  @if ($userfield->field == 'location')
                      <select name="{{$userfield->field}}" class="name filterCities" data-id="0" id="{{$userfield->field}}">
                          <option disabled selected>Выберите город</option>
                      </select>
                  @endif

              </li>
          @endif
      @endif
  @endforeach
</ul>
