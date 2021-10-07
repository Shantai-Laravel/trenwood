@extends('front.app')
@section('content')
@include('front.layouts.header')

<div class="collectionOne about">
  <div class="container">

    <div class="aboutBlock row">
        <h3>{{ $page->translationByLanguage($lang->id)->title }}</h3>

        <img src="/images/pages/{{ $page->translationByLanguage($lang->id)->image }}" alt="">

        <p>{!!$page->translationByLanguage($lang->id)->body!!}</p>

    </div>
</div>
</div>
@include('front.layouts.footer')
@stop
