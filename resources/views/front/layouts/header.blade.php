@php
    $blog1 = getPage('blog1', $lang->id);
    $blog2 = getPage('blog2', $lang->id);
    $blog3 = getPage('blog3', $lang->id);
@endphp

<div class="header">
  <div class="menuClient">
    <div class="menuOpen cartOpen">
      <div class="contClose">
        <div class="closeMenu"></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-auto headMenuOpen">
          {{trans('front.general.cart')}}
        </div>
      </div>
      <div class="row">
        <div class="col-12 cartBox">
          <div class="row justify-content-center" style="margin: 0;">
            @include('front.inc.cartBox')
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="footerCartOpen">
            <a href="{{url($lang->lang.'/cart')}}" class="butt buttTransparent">
              {{trans('front.header.cartView')}}
            </a>
            <a href="#" class="butt buttSilver shopNow">
              {{trans('front.general.buyBtn')}}
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="menuOpen wishOpen">
      <div class="contClose">
        <div class="closeMenu"></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-auto headMenuOpen">
          {{trans('front.general.wishList')}}
        </div>
      </div>
      <div class="row">
            <div class="col-12 wishListBox">
              <div class="row justify-content-center" style="margin: 0;">
                @include('front.inc.wishListBox')
              </div>
            </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="footerCartOpen">
            <a href="{{url($lang->lang.'/wishList')}}" class="butt buttTransparent">
              {{trans('front.header.wishListView')}}
            </a>
            <a href="#" class="butt buttSilver shopNow">
              {{trans('front.general.buyBtn')}}
            </a>
          </div>
        </div>
      </div>
    </div>
    @if(Auth::guard('persons')->check())
      <div class="menuOpen loginOpen logged">
        <div class="contClose">
          <div class="closeMenu"></div>
        </div>
        <div class="row justify-content-center">
          <div class="col-auto headMenuOpen">
            {{trans('front.header.loggedWelcome')}} {{Auth::guard('persons')->user()->name}} {{Auth::guard('persons')->user()->surname}}
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12">
            <ul class="loggedList">
              <li><a href="{{route('cabinet')}}">{{trans('front.cabinet.userdata')}}</a></li>
              <li><a href="{{route('cart')}}">{{trans('front.cabinet.cart')}}</a></li>
              <li><a href="{{route('cabinet.wishList')}}">{{trans('front.cabinet.wishList')}}</a></li>
              <li><a href="{{route('cabinet.history')}}">{{trans('front.cabinet.history')}}</a></li>
              <li><a href="{{route('cabinet.return')}}">{{trans('front.cabinet.return')}}</a></li>
              <li><a href="{{url($lang->lang.'/logout')}}">{{trans('front.cabinet.logout')}}</a></li>
            </ul>
          </div>
        </div>
      </div>
    @else
      <div class="menuOpen loginOpen">
        <div class="contClose">
          <div class="closeMenu"></div>
        </div>
        <div class="row justify-content-center">
          <div class="col-auto headMenuOpen">
            {{trans('front.header.unlogged')}}
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="loginContent">
              <div class="titleLogin">
                {{trans('front.header.unloggedWelcome')}}
              </div>
              <a href="{{url($lang->lang.'/login')}}"><div class="butt buttSilver">{{trans('front.header.unloggedBtn')}}</div></a>
              <div class="logRet">{{trans('front.header.unloggedSocial')}}
                <span>
                  <a href="{{url($lang->lang.'/login/facebook')}}"><img src="{{asset('fronts/img/icons/face.svg')}}" alt=""></a>
                  <a href="{{url($lang->lang.'/login/google')}}"><img src="{{asset('fronts/img/icons/gmail.svg')}}" alt=""></a>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12">
            <div class="footerCartOpen">
              <a href="{{url($lang->lang.'/register')}}" class="butt buttTransparent">
                {{trans('front.header.unloggedRegister')}}
              </a>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="menuOpen searchOpen">
      <div class="contClose">
        <div class="closeMenu"></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-auto headMenuOpen">
          {{trans('front.header.search')}}
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-10" >
          <form action="{{url($lang->lang.'/search')}}" method="get">
            <input type="text" name="value" value="" class="search-field" placeholder="{{trans('front.header.searchText')}}">
            <div class="searchResult col-12">
              @include('front.inc.searchResults')
            </div>
            <input type="submit" class="buttSilver" value="{{trans('front.header.search')}}">
          </form>
        </div>
      </div>
    </div>
    <div class="menuOpen burgerOpen">
      <div class="contClose">
        <div class="closeMenu"></div>
      </div>
      <ul class="navCenter">
        <li><a href="{{url($lang->lang.'/catalog')}}">{{trans('front.header.products')}}</a></li>
        <li>{{trans('front.header.collections')}}
          @if (count($collections) > 0)
            <ul class="ulHover">
              @foreach ($collections as $collection)
                <li><a href="{{url($lang->lang.'/catalog/'.$collection->sets->first()->alias)}}">{{$collection->translationByLanguage($lang->id)->first()->name}}</a></li>
              @endforeach
            </ul>
          @endif
        </li>
        <li><a href="{{ url($lang->lang.'/inspiration') }}">{{trans('front.header.inspiration')}}</a></li>
        {{-- <li>{{ trans('front.blog') }}
            <ul class="ulHover">
                @if (!is_null($blog1))
                    <li><a href="{{url($lang->lang.'/'.$blog1->alias)}}">{{ $blog1->title }}</a></li>
                @endif
                @if (!is_null($blog2))
                    <li><a href="{{url($lang->lang.'/'.$blog2->alias)}}">{{ $blog2->title }}</a></li>
                @endif
                @if (!is_null($blog3))
                    <li><a href="{{url($lang->lang.'/'.$blog3->alias)}}">{{ $blog3->title }}</a></li>
                @endif
            </ul>
        </li> --}}
      </ul>
      <ul class="navLeft">
        <li><a href="{{url($lang->lang.'/about')}}">{{trans('front.general.about')}}</a></li>
        <li><a href="{{url($lang->lang.'/contacts')}}">{{trans('front.general.contacts')}}</a></li>
        <li><a href="{{url($lang->lang.'/buyOnline')}}">{{trans('front.general.buyOnline')}}</a></li>
      </ul>
    </div>
  </div>
  <div class="headerDescktop">
    <div class="posRel">
      <a href="{{url($lang->lang)}}" class="logo">
      </a>
      <div class="row justify-content-between">
        <div class="col-auto">
          <ul class="navLeft">
            <li><a href="{{url($lang->lang.'/about')}}">{{trans('front.general.about')}}</a></li>
            <li><a href="{{url($lang->lang.'/contacts')}}">{{trans('front.general.contacts')}}</a></li>
            <li><a href="{{url($lang->lang.'/buyOnline')}}">{{trans('front.general.buyOnline')}}</a></li>
          </ul>
        </div>
        <div class="col-auto">
          <ul class="navRight">
            <li>
              <div class="btnm buttonSearch">
                {{trans('front.header.search')}}
              </div>
            </li>
            <li>
              @if (Auth::guard('persons')->check())
                <div class="btnm buttonLogin logged">
                  {{trans('front.header.loggedWelcome')}} {{Auth::guard('persons')->user()->name}} {{Auth::guard('persons')->user()->surname}}
                </div>
              @else
                <div class="btnm buttonLogin {{Auth::guard('persons')->check() ? 'logged': ''}}">
                  {{trans('front.header.unlogged')}}
                </div>
              @endif
            </li>
            <li>
              <div class="wishListCount">
                @include('front.inc.wishListCount')
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="row justify-content-end">
        <div class="cartCount col-auto">
          <div class="row">
            @include('front.inc.cartCount')
          </div>
        </div>
        <div class="col-auto">
          <?php $pathWithoutLang = pathWithoutLang(Request::path(), $langs);?>

          <div class="language">

            @if (Request::segment(1))
              {{Request::segment(1)}}
            @else
              {{$langs[0]->lang}}
            @endif

            <ul class="ulHover">
              @if (!empty($langs))
                  @foreach ($langs as $key => $oneLang)
                      <li> <a href="{{ url($oneLang->lang.'/'.$pathWithoutLang) }}">{{ $oneLang->lang }}</a> </li>
                  @endforeach
              @endif
            </ul>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-auto">
          <ul class="navCenter">
            <li><a href="{{url($lang->lang.'/catalog')}}">{{trans('front.header.products')}}</a></li>
            <li>{{trans('front.header.collections')}}
              @if (count($collections) > 0)
                <ul class="ulHover">
                  @foreach ($collections as $collection)
                    <li><a href="{{url($lang->lang.'/catalog/'.$collection->sets->first()->alias)}}">{{$collection->translationByLanguage($lang->id)->first()->name}}</a></li>
                  @endforeach
                </ul>
              @endif
            </li>
            <li><a href="{{url($lang->lang.'/inspiration')}}">{{trans('front.header.inspiration')}}</a></li>
            {{-- <li>{{ trans('front.blog') }}
            <ul class="ulHover">
                @if (!is_null($blog1))
                    <li><a href="{{url($lang->lang.'/'.$blog1->alias)}}">{{ $blog1->title }}</a></li>
                @endif
                @if (!is_null($blog2))
                    <li><a href="{{url($lang->lang.'/'.$blog2->alias)}}">{{ $blog2->title }}</a></li>
                @endif
                @if (!is_null($blog3))
                    <li><a href="{{url($lang->lang.'/'.$blog3->alias)}}">{{ $blog3->title }}</a></li>
                @endif
            </ul>
        </li> --}}
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="headerMobile">
    <div class="menuOpenBurger">

    </div>
    <a href="{{url($lang->lang)}}" class="logoMobile"></a>
    <div class="row">
      <div class="col-12">
        <div class="row justify-content-between align-items-center">
          <div class="col-auto">
            <div class="burger">

            </div>
          </div>
          <div class="col-auto">
            <div class="menuMobile">
              <ul class="navRight">
                <li>
                  <div class="btnm buttonSearch">
                  </div>
                </li>
                <li>
                  <div class="wishListCountMob">
                    @include('front.inc.wishListCountMob')
                  </div>
                </li>
                <li>
                  <div class="btnm buttonLogin {{Auth::guard('persons')->check() ? 'logged': ''}}">

                  </div>
                </li>
                <li>
                  <div class="buttonCartHeader cartCountMob">
                    @include('front.inc.cartCountMob')
                  </div>
                </li>
                <li>
                  <?php $pathWithoutLang = pathWithoutLang(Request::path(), $langs);?>
                    <div class="language">

                      @if (Request::segment(1))
                        {{Request::segment(1)}}
                      @else
                        {{$langs[0]->lang}}
                      @endif

                      <ul class="ulHover">
                        @if (!empty($langs))
                            @foreach ($langs as $key => $oneLang)
                                <li> <a href="{{ url($oneLang->lang.'/'.$pathWithoutLang) }}">{{ $oneLang->lang }}</a> </li>
                            @endforeach
                        @endif
                      </ul>
                    </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
