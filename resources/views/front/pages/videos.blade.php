@extends('front.app')
@section('content')
@include('front.layouts.header')


    <div class="collectionOne about">
      <div class="container">

        <div class="aboutBlock row">

        {{-- <div class="col-xs-6">
            @foreach ($products as $key => $product)
                <div class="col-12">
                    <div class="videoBlock">
                      <p><b>#{{ $key + 1 }}</b> {{ $product->video }}</p>
                      <video width="100%" height="auto" src="{{asset('videos/products/'.$product->video)}}" autoplay muted loop></video>
                      <div class="zaglushka"></div>
                    </div>
                </div
            @endforeach
        </div> --}}

        <div class="row">
            @for ($i=0; $i < 10; $i++)
                <div class="col-md-6">
                    <div class="videoBlock">
                    <p><b>#{{ $i + 1 }}</b> /video/bus2.webm</p>
                      <video width="100%" height="auto" src="{{asset('fronts/video/vin1.webm')}}" autoplay muted loop></video>
                      <div class="zaglushka"></div>
                    </div>
                </div>
            @endfor

            @for ($i=0; $i < 10; $i++)
                <div class="col-md-6">
                    <div class="videoBlock">
                        <p><b>#{{ $i + 1 }}</b> /video/bus2.webm</p>
                      <video width="100%" height="auto" src="{{asset('fronts/video/cas1.webm')}}" autoplay muted loop></video>
                      <div class="zaglushka"></div>
                    </div>
                </div>
            @endfor

            @for ($i=0; $i < 10; $i++)
                <div class="col-md-6">
                    <div class="videoBlock">
                        <p><b>#{{ $i + 1 }}</b> /video/bus2.webm</p>
                      <video width="100%" height="auto" src="{{asset('fronts/video/cas2.webm')}}" autoplay muted loop></video>
                      <div class="zaglushka"></div>
                    </div>
                </div>
            @endfor

            @for ($i=0; $i < 10; $i++)
                <div class="col-md-6">
                    <div class="videoBlock">
                        <p><b>#{{ $i + 1 }}</b> /video/bus2.webm</p>
                      <video width="100%" height="auto" src="{{asset('fronts/video/bus1.webm')}}" autoplay muted loop></video>
                      <div class="zaglushka"></div>
                    </div>
                </div>
            @endfor

            @for ($i=0; $i < 10; $i++)
                <div class="col-md-6">
                    <div class="videoBlock">
                        <p><b>#{{ $i + 1 }}</b> /video/bus2.webm</p>
                      <video width="100%" height="auto" src="{{asset('fronts/video/bus2.webm')}}" autoplay muted loop></video>
                      <div class="zaglushka"></div>
                    </div>
                </div>
            @endfor
        </div>




        </div>
    </div>
    </div>


@include('front.layouts.footer')
@stop
