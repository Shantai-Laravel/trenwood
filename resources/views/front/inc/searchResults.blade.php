@if (!empty($findProducts) && count($findProducts) > 0)
    @foreach ($findProducts as $key => $findProduct)
        <div class="col-12 hui">
          <div class="row cartMenuItem">
            <div class="col-4">
              @if ($findProduct->withoutBack()->first())
                  <img id="prOneBig1" src="{{ asset('images/products/og/'.$findProduct->withoutBack()->first()->src ) }}">
              @else
                  <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
              @endif
            </div>
            <div class="col-8 descrItem">
                <a href="{{ url($lang->lang.'/catalog/'.$findProduct->setProduct->set->alias.'/'.$findProduct->alias ) }}">
                  {!! str_ireplace($search, '<i>'.$search.'</i>', $findProduct->translationByLanguage($lang->id)->first()->name) !!}
                </a>
            </div>
          </div>
        </div>
    @endforeach
@endif
@if (!empty($findSets) && count($findSets) > 0)
    @foreach ($findSets as $key => $findSet)
        <div class="col-12 hui">
          <div class="row cartMenuItem">
            <div class="col-4">
              @if ($findSet->withoutBack()->first())
                  <img id="prOneBig1" src="{{ asset('images/sets/og/'.$findSet->withoutBack()->first()->src ) }}">
              @else
                  <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
              @endif
            </div>
            <div class="col-8 descrItem">
                <a href="{{ url($lang->lang.'/catalog/'.$findSet->alias) }}">
                  {!! str_ireplace($search, '<i>'.$search.'</i>', $findSet->translationByLanguage($lang->id)->first()->name) !!}
                </a>
            </div>
          </div>
        </div>
    @endforeach
@endif
