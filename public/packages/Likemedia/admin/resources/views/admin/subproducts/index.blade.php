@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="brand">Subproducts </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Subproducts </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
      trans('variables.elements_list') => route('subproducts.index')
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="row">
  <div class="col-md-2">
      <label>Categorie</label>
  </div>
  <div class="col-md-8">
      <select name="category" class="form-control category-select">
          @if (count($categories) > 0)
              @foreach ($categories as $key => $category)
                <option value="{{ $category->id }}">{{ $category->translationByLanguage($lang->id)->first()->name }}</option>
              @endforeach
          @endif
      </select>
  </div>
</div>
<hr>

<div class="properties">
  @include('admin::admin.subproducts.properties')
</div>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
