@extends('front.app')
@section('content')
@include('front.layouts.header')
<?php
  $pinterest = getContactInfo('pinterest')->translationByLanguage()->first()->value;
  $facebook = getContactInfo('facebook')->translationByLanguage()->first()->value;
  $instagram = getContactInfo('instagram')->translationByLanguage()->first()->value;
  $linkedin = getContactInfo('linkedin')->translationByLanguage()->first()->value;
  $twitter = getContactInfo('twitter')->translationByLanguage()->first()->value;
  $youtube = getContactInfo('youtube')->translationByLanguage()->first()->value;
?>
<div class="retAbs">
  <ul>
    <li><a class="btnRet" target="_blank" href="{{$pinterest}}"></a></li>
    <li><a class="btnRet" target="_blank" href="{{$facebook}}"></a></li>
    <li><a class="btnRet" target="_blank" href="{{$instagram}}"></a></li>
    <li><a class="btnRet" target="_blank" href="{{$linkedin}}"></a></li>
    <li><a class="btnRet" target="_blank" href="{{$twitter}}"></a></li>
    <li><a class="btnRet" target="_blank" href="{{$youtube}}"></a></li>
  </ul>
</div>

<main class="collectionDescktop">
      <div class="homeContent">
        @if (count($vintage) > 0)
          <section class="vintageSection">
            <div class="collectionInner">
              <div class="scroll-downs">
              <div class="mousey">
                <div class="scroller"></div>
              </div>
            </div>
                  <div class="videoContainer">
                    <video autoplay="autoplay" poster="{{ asset('images/products/og/'.@$vintage->products->first()->mainImage()->first()->src ) }}" loop="loop" muted defaultMuted playsinline>
                      <source src="{{asset('videos/sets/'.$vintage->video->src)}}" type="video/mp4">
                    </video>
                  </div>
                 <div  class=" asideBloc">
                  <a href="{{url($lang->lang.'/catalog/'.$vintage->alias.'/'.$vintage->products->first()->alias)}}" class="imgBloc">
                    @if ($vintage->products->first()->mainImage()->first())
                    <img src="{{ asset('images/products/og/'.$vintage->products->first()->mainImage()->first()->src ) }}">
                    @else
                        <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                    @endif
                  </a>
                  <a href="{{url($lang->lang.'/catalog/'.$vintage->alias.'/'.$vintage->products->first()->alias)}}" class="titleNew">{{$vintage->products->first()->translationByLanguage($lang->id)->first()->name}}</a>
                  <p>
                    {!! strip_tags($vintage->products->first()->translationByLanguage($lang->id)->first()->description) !!}
                  </p>
                  <div class="titleCollectionNew">
                      {{trans('front.home.vintage')}}
                  </div>
                  <a href="{{url($lang->lang.'/catalog/'.$vintage->alias)}}" class="buttShop">
                      {{trans('front.general.buyBtn')}}
                  </a>
                </div>

            </div>
          </section>
        @endif
        @if (count($business) > 0)
          <section class="businessSection">
            <div class="collectionInner">
              <div class="scroll-downs">
                <div class="mousey">
                  <div class="scroller"></div>
                </div>
              </div>
                <div class="asideBloc">
                    <a href="{{url($lang->lang.'/catalog/'.$business->alias.'/'.$business->products->first()->alias)}}" class="imgBloc">
                      @if ($business->products->first()->mainImage()->first())
                      <img src="{{ asset('images/products/og/'.$business->products->first()->mainImage()->first()->src ) }}">
                      @else
                          <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                      @endif
                    </a>
                    <div class="titleNew">{{$business->products->first()->translationByLanguage($lang->id)->first()->name}}</div>
                    <p>
                      {!!$business->products->first()->translationByLanguage($lang->id)->first()->description!!}
                    </p>
                    <div class="titleCollectionNew">
                        {{trans('front.home.business')}}
                    </div>
                    <a href="{{url($lang->lang.'/catalog/'.$business->alias)}}" class="buttShop">
                        {{trans('front.general.buyBtn')}}
                    </a>
                </div>
                <div class="videoContainer">
                    <video autoplay="autoplay" poster="{{ asset('images/products/og/'.@$business->products->first()->mainImage()->first()->src ) }}"  loop="loop" muted defaultMuted playsinline>
                      <source src="{{asset('videos/sets/'.$business->video->src)}}" type="video/mp4">
                    </video>
                </div>

            </div>
          </section>
        @endif
        @if (count($casual) > 0)
          <section  class="casualSection">
            <div class="collectionInner">
              <div class="scroll-downs">
                  <div class="mousey">
                    <div class="scroller"></div>
                  </div>
                </div>
              <div class="casualAbsBloc">
                    <div class="titleCollectionNew">
                      {{trans('front.home.casual')}}
                    </div>
                    <p>
                      {{$casual->translationByLanguage($lang->id)->first()->addInfo}}
                    </p>
                    <a href="{{url($lang->lang.'/catalog/casual-select')}}" class="butt buttArrow">
                      {{trans('front.general.discover')}}
                    </a>
                </div>
                <div class="videoContainer">
                  <video autoplay="autoplay" poster="{{ asset('images/sets/og/'.@$casual->mainPhoto->src ) }}"  loop="loop" muted defaultMuted playsinline>
                    <source src="{{asset('videos/sets/'.$casual->video->src)}}" type="video/mp4">
                  </video>
                </div>
                <div class="asideBloc">
                  <a href="{{url($lang->lang.'/catalog/'.$casual->alias)}}" class="imgBloc">
                    @if ($casual->mainPhoto)
                        <img id="prOneBig1" src="{{ asset('images/sets/og/'.$casual->mainPhoto->src ) }}">
                    @else
                        <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                    @endif
                  </a>
                    <div class="casualAside">
                      <div class="titleNew">
                        {{$casual->translationByLanguage($lang->id)->first()->name}}
                      </div>
                      <p>{{ str_limit($casual->translationByLanguage($lang->id)->first()->description, $limit = 150, $end = '...')}}</p>
                      <a href="{{url($lang->lang.'/catalog/'.$casual->alias)}}" class="butt buttArrow">
                        {{trans('front.general.discover')}}
                      </a>
                    </div>
                </div>
            </div>
          </section>
        @endif
        <section class="footerHome">
          @include('front.layouts.footer')
        </section>
      </div>
    </main>
<div class="collectionsMobile">
  @if (count($vintage) > 0)
    <div class="vintageMobile">
      <div class="row">
        <div class="col-12">
          @if ($vintage->mainPhoto)
              <img id="prOneBig1" class="noneMobile" src="{{ asset('images/sets/og/'.$vintage->mainPhoto->src ) }}">
          @else
              <img class="noneMobile" src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
          @endif
          <div class="videoBlock">
            <video width="100%" height="auto" autoplay="autoplay" poster="{{ asset('images/sets/og/'.@$vintage->mainPhoto->src ) }}" loop="loop" muted defaultMuted playsinline>
              <source src="{{asset('videos/sets/'.$vintage->video->src)}}" type="video/mp4">
            </video>
            <div class="zaglushka"></div>
          </div>
          <div class="title">{{$vintage->translationByLanguage($lang->id)->first()->name}}</div>
          <p>{{$vintage->translationByLanguage($lang->id)->first()->description}}</p>
          <a href="{{url($lang->lang.'/catalog/'.$vintage->alias)}}" class="buttShop">
            {{trans('front.general.buyBtn')}}
          </a>
        </div>
      </div>
    </div>
  @endif

  @if (count($business) > 0)
    <div class="bussinesMobile">
      <div class="row">
        <div class="col-12 titleCllection">
          bussines collection
        </div>
        <div class="col-12">
          @if ($business->mainPhoto)
              <img class="noneMobile" id="prOneBig1" src="{{ asset('images/sets/og/'.$business->mainPhoto->src ) }}">
          @else
              <img class="noneMobile" src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
          @endif
          <div class="videoBlock">
            <video width="100%" height="auto" autoplay="autoplay" poster="{{ asset('images/sets/og/'.@$business->mainPhoto->src ) }}" loop="loop" muted defaultMuted playsinline>
             <source src="{{asset('videos/sets/'.$business->video->src)}}" type="video/mp4">


            </video>
          <div class="zaglushka"></div>
          </div>
          <div class="title">{{$business->translationByLanguage($lang->id)->first()->name}}</div>
          <p>{{$business->translationByLanguage($lang->id)->first()->description}}</p>
          <a href="{{url($lang->lang.'/catalog/'.$business->alias)}}" class="buttShopBrun">
            {{trans('front.general.buyBtn')}}
          </a>
        </div>
      </div>
    </div>
  @endif

  @if (count($casual) > 0)
    <div class="casualMobile">
      <div class="row">
        <div class="col-12 titleCllection">
          Casual collection
        </div>
        <div class="col-12">
          @if ($casual->mainPhoto)
              <img class="noneMobile" id="prOneBig1" src="{{ asset('images/sets/og/'.$casual->mainPhoto->src ) }}">
          @else
              <img class="noneMobile" src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
          @endif
          <div class="videoBlock">
            <video width="100%" height="auto" poster="{{ asset('images/sets/og/'.@$casual->mainPhoto->src ) }}"  autoplay="autoplay" loop="loop" muted defaultMuted playsinline>
              <source src="{{asset('videos/sets/'.$casual->video->src)}}" type="video/mp4">



            </video>
            <div class="zaglushka"></div>
          </div>
          <div class="title">{{$casual->translationByLanguage($lang->id)->first()->name}}</div>
          <p>{{$casual->translationByLanguage($lang->id)->first()->description}}</p>
          <a href="{{url($lang->lang.'/catalog/'.$casual->alias)}}" class="butt buttArrow">
            {{trans('front.general.discover')}}
          </a>
        </div>
      </div>
    </div>
  @endif

</div>

  <div class="homeFoter homeFoterMobile">
    @include('front.layouts.footer')
  </div>

<div class="modal" id="modalPromocode">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4>{{trans('front.home.promocode')}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>
          @if (session()->has('promocode'))
            {{trans('front.home.promocodeMessage', ['user' => Auth::guard('persons')->user()->name, 'discount' => session('promocode')->discount])}}
          @endif
        </p>
      </div>
    </div>
  </div>
</div>

@if (session()->has('promocode'))
  <script type="text/javascript">
      $(window).on('load', function() {
          $('#modalPromocode').modal();
          @php
            session()->forget('promocode');
          @endphp
      });
  </script>
@endif

@stop
