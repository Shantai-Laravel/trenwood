@extends('front.app')
@section('content')
@include('front.layouts.header')
    <div class="inspiration">
        {!!$page->translationByLanguage($lang->id)->body!!}
    </div>
@include('front.layouts.footer')
@stop
