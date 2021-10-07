@if(count($properties) > 0)
<div class="card">
    <div class="card-block">
      <form action="{{route('subproducts.store')}}" method="post">
        {{csrf_field()}}
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Parameter</th>
                    <th>Show Property</th>
                    <th>Status</th>
                    <th class="text-center">
                        <input type="radio" name="image" value="0"> <small>remove</small>
                        <br>
                        Image
                    </th>
                </tr>
            </thead>
            <tbody>
                <input type="hidden" name="category_id" value="{{$product_category->id}}">
                @if (count($properties) > 0)

                @foreach($properties as $key => $property)
                <?php
                    $subproductProperty = getParamCategory($property->id, $product_category->id);
                    // echo $product_category->id;
                    // dd($subproductProperty);
                ?>
                <input type="hidden" name="property_id[]" value="{{$property->id}}">
                <tr id="{{ $property->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{$property->translationByLanguage($lang->id)->first()->name}}
                    </td>
                    <td>
                      <div class="form-group">
                          <select name="show[]" class="form-control">
                              @if (!is_null($subproductProperty))
                                  <option value="1" {{ $subproductProperty->show_property == 1 ? 'selected' : '' }}>Yes</option>
                                  <option value="0" {{ $subproductProperty->show_property == 0 ? 'selected' : '' }}>No</option>
                              @else
                                  <option value="1">Yes</option>
                                  <option value="0">No a</option>
                              @endif

                          </select>
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <select name="status[]" class="form-control">
                         @if (!is_null($subproductProperty))
                            <option value="dependable" {{ $subproductProperty->status == 'dependable' ? 'selected' : ''}}>Dependable</option>
                            <option value="insertable" {{ $subproductProperty->status == 'insertable' ? 'selected' : ''}}>Insertable</option>
                          @else
                            @if ($property->type == 'select' || $property->type == 'checkbox')
                                <option value="dependable" selected>Dependable</option>
                            @endif
                            <option value="insertable">Insertable</option>
                          @endif
                        </select>
                      </div>
                    </td>
                    <td>
                      <div class="form-group text-center">
                          @if (!is_null($subproductProperty))
                          <input type="radio" name="image" value="{{ $property->id }}" {{ $subproductProperty->image == 1 ? 'checked' : ''}}>
                          @else
                            <input type="radio" name="image" value="{{ $property->id }}">
                          @endif
                      </div>
                    </td>
                </tr>
                @endforeach
            @endif

            </tbody>
            <tfoot>
                <tr>
                    <td colspan=7></td>
                </tr>
            </tfoot>
        </table>
        <input type="submit" class="form-control" value="{{trans('variables.save_it')}}">
      </form>
    </div>
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
