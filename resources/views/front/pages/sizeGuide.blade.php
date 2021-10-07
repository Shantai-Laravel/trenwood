@extends('front.app')
@section('content')
@include('front.layouts.header')
    {!!$page->translationByLanguage($lang->id)->body!!}
@include('front.layouts.footer')
@stop
