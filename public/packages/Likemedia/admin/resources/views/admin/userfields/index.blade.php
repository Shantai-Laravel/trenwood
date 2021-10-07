@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="brand">User Fields </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> User Fields </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
      trans('variables.elements_list') => route('userfields.index')
    ]
    ])
</div>

@include('admin::admin.alerts')

@if(count($userfields) > 0)
<div class="card userfields">
    <div class="card-block">
      <form action="{{route('userfields.store')}}" method="post">
        {{csrf_field()}}
        <h3>Personal Data</h3>
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Field For Registration</th>
                    <th>Field For Cabinet</th>
                    <th>Field For Cart</th>
                    <th>Field For Auth</th>
                    <th>Unique Field</th>
                    <th>Required Field</th>
                    <th>Return Field</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userfields as $key => $userfield)
                    @if ($userfield->field_group == 'personaldata')
                      <tr>
                          <td>
                              {{ $key + 1 }}
                          </td>
                          <td>
                              {{$userfield->field}}
                          </td>
                          <td>
                              <input type="hidden" name="in_register[{{$userfield->id}}]"  value="0">
                              <input type="checkbox" class="register" {{$userfield->in_register == 1 ? 'checked' : ''}} name="in_register[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_cabinet[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_cabinet == 1 ? 'checked' : ''}} name="in_cabinet[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden"name="in_cart[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_cart == 1 ? 'checked' : ''}} name="in_cart[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_auth[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_auth == 1 ? 'checked' : ''}} name="in_auth[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                            @if ($userfield->field == 'email' || $userfield->field == 'phone')
                              <input type="hidden" name="unique_field[{{$userfield->id}}]"  value="0">
                              <input type="checkbox" {{$userfield->unique_field == 1 ? 'checked' : ''}} name="unique_field[{{$userfield->id}}]" value="1">
                            @endif
                          </td>
                          <td>
                              <input type="hidden" name="required_field[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->required_field == 1 ? 'checked' : ''}} name="required_field[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="return_field[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->return_field == 1 ? 'checked' : ''}} name="return_field[{{$userfield->id}}]" value="1">
                          </td>
                      </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=7></td>
                </tr>
            </tfoot>
        </table>
        <h3>Address</h3>
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Field For Registration</th>
                    <th>Field For Cabinet</th>
                    <th>Field For Cart</th>
                    <th>Field For Auth</th>
                    <th>Required Field</th>
                    <th>Return Field</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userfields as $key => $userfield)
                    @if ($userfield->field_group == 'address')
                      <tr>
                          <td>
                              {{ $key + 1 }}
                          </td>
                          <td>
                              {{$userfield->field}}
                          </td>
                          <td>
                              <input type="hidden" name="in_register[{{$userfield->id}}]" value="0">
                              <input type="checkbox" class="register" {{$userfield->in_register == 1 ? 'checked' : ''}} name="in_register[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_cabinet[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_cabinet == 1 ? 'checked' : ''}} name="in_cabinet[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_cart[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_cart == 1 ? 'checked' : ''}} name="in_cart[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_auth[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_auth == 1 ? 'checked' : ''}} name="in_auth[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="required_field[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->required_field == 1 ? 'checked' : ''}} name="required_field[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="return_field[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->return_field == 1 ? 'checked' : ''}} name="return_field[{{$userfield->id}}]" value="1">
                          </td>
                      </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=7></td>
                </tr>
            </tfoot>
        </table>
        <h3>Company</h3>
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Field For Registration</th>
                    <th>Field For Cabinet</th>
                    <th>Field For Cart</th>
                    <th>Field For Auth</th>
                    <th>Required Field</th>
                    <th>Return Field</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userfields as $key => $userfield)
                    @if ($userfield->field_group == 'company')
                      <tr>
                          <td>
                              {{ $key + 1 }}
                          </td>
                          <td>
                              {{$userfield->field}}
                          </td>
                          <td>
                              <input type="hidden" name="in_register[{{$userfield->id}}]" value="0">
                              <input type="checkbox" class="register" {{$userfield->in_register == 1 ? 'checked' : ''}} name="in_register[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_cabinet[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_cabinet == 1 ? 'checked' : ''}} name="in_cabinet[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_cart[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_cart == 1 ? 'checked' : ''}} name="in_cart[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_auth[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_auth == 1 ? 'checked' : ''}} name="in_auth[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="required_field[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->required_field == 1 ? 'checked' : ''}} name="required_field[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="return_field[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->return_field == 1 ? 'checked' : ''}} name="return_field[{{$userfield->id}}]" value="1">
                          </td>
                      </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=7></td>
                </tr>
            </tfoot>
        </table>
        <h3>Priority address</h3>
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Field For Registration</th>
                    <th>Field For Cabinet</th>
                    <th>Field For Cart</th>
                    <th>Field For Auth</th>
                    <th>Required Field</th>
                    <th>Return Field</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userfields as $key => $userfield)
                    @if ($userfield->field_group == 'priorityaddress')
                      <tr>
                          <td>
                              {{ $key + 1 }}
                          </td>
                          <td>
                              {{$userfield->field}}
                          </td>
                          <td>
                              <input type="hidden" name="in_register[{{$userfield->id}}]" value="0">
                              <input type="checkbox" class="register" {{$userfield->in_register == 1 ? 'checked' : ''}} name="in_register[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_cabinet[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_cabinet == 1 ? 'checked' : ''}} name="in_cabinet[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_cart[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_cart == 1 ? 'checked' : ''}} name="in_cart[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="in_auth[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->in_auth == 1 ? 'checked' : ''}} name="in_auth[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="required_field[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->required_field == 1 ? 'checked' : ''}} name="required_field[{{$userfield->id}}]" value="1">
                          </td>
                          <td>
                              <input type="hidden" name="return_field[{{$userfield->id}}]" value="0">
                              <input type="checkbox" {{$userfield->return_field == 1 ? 'checked' : ''}} name="return_field[{{$userfield->id}}]" value="1">
                          </td>
                      </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=7></td>
                </tr>
            </tfoot>
        </table>
        <h3>General</h3>
        @foreach($userfields as $key => $userfield)
            @if ($userfield->field == 'maxaddress')
              <label>{{$userfield->field}}</label>
                <select class="form-control" name="value[{{$userfield->id}}]">
                    @for ($i = 1; $i <= 15; $i++)
                        <option {{$userfield->value == $i ? 'selected' : ''}} value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            @elseif ($userfield->field == 'countries')
            <?php $values = $userfield->value != '' ? json_decode($userfield->value) : []; ?>
              <label>{{$userfield->field}}</label>
                <select class="form-control" size="7" name="value[{{$userfield->id}}][]" multiple>
                    @if (count($countries) > 0)
                        @foreach ($countries as $key => $country)
                            <option {{in_array($country->id, $values) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    @endif
                </select>
            @endif
        @endforeach

        <label for=""></label>
        <input type="submit" class="form-control" value="{{trans('variables.save_it')}}">
      </form>
    </div>
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif


@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
