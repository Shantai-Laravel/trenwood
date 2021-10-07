<?php
    $footerText = getContactInfo('footertext')->translationByLanguage($lang->id)->first()->value;
    $phone = getContactInfo('phone')->translationByLanguage()->first()->value;
    $email = getContactInfo('emailFront')->translationByLanguage()->first()->value;
    $skype = getContactInfo('skype')->translationByLanguage()->first()->value;
    $whatsapp = getContactInfo('whatsapp')->translationByLanguage()->first()->value;
    $viber = getContactInfo('viber')->translationByLanguage()->first()->value;

    $pinterest = getContactInfo('pinterest')->translationByLanguage()->first()->value;
    $facebook = getContactInfo('facebook')->translationByLanguage()->first()->value;
    $instagram = getContactInfo('instagram')->translationByLanguage()->first()->value;
    $linkedin = getContactInfo('linkedin')->translationByLanguage()->first()->value;
    $twitter = getContactInfo('twitter')->translationByLanguage()->first()->value;
    $youtube = getContactInfo('youtube')->translationByLanguage()->first()->value;
?>
<div class="foter">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-md-auto col-12 dac">
        <h6 class="foterTitle">{{trans('front.general.aboutTrenwood')}}</h6>
        <ul class="foterUl">
          <li><a href="{{url($lang->lang.'/about')}}">{{trans('front.general.about')}}</a></li>
          <li><a href="{{url($lang->lang.'/contacts')}}">{{trans('front.general.contacts')}}</a></li>
          <li><a href="{{url($lang->lang.'/howToShop')}}">{{trans('front.general.howToShop')}}</a></li>
          <li><a href="{{url($lang->lang.'/inspiration')}}">{{trans('front.general.inspiration')}}</a></li>
        </ul>
      </div>
      <div class="col-md-auto col-12 dac">
        <h6 class="foterTitle">{{trans('front.general.collections')}}</h6>
        @if (count($collections) > 0)
          <ul class="foterUl">
            @foreach ($collections as $collection)
              <li><a href="{{url($lang->lang.'/catalog/'.$collection->sets->first()->alias)}}">{{$collection->translationByLanguage($lang->id)->first()->name}}</a></li>
            @endforeach
              <li><a href="{{url($lang->lang.'/catalog')}}">{{trans('front.general.allProducts')}}</a></li>
          </ul>
        @endif
      </div>
      <div class="col-md-auto col-12 dac">
        <h6 class="foterTitle">{{trans('front.footer.useful')}}</h6>
        <ul class="foterUl">
          <li><a href="{{url($lang->lang.'/refundPolicy')}}">{{trans('front.footer.refundPolicy')}}</a></li>
          <li><a href="{{url($lang->lang.'/conditions')}}">{{trans('front.general.conditions')}}</a></li>
          <li><a href="{{url($lang->lang.'/privacyPolicy')}}">{{trans('front.general.privacyPolicy')}}</a></li>
          <li><a href="{{url($lang->lang.'/cookiePolicy')}}">{{trans('front.general.cookiePolicy')}}</a></li>
        </ul>
      </div>
      <div class="col-md-auto col-12 dac">
        <h6 class="foterTitle">{{trans('front.footer.support')}}</h6>
        <ul class="foterUl">
          <li><a href="tel: {{$phone}}">{{trans('front.footer.phone')}} {{$phone}}</a></li>
          <li><a href="mailto: {{$email}}">{{trans('front.footer.mail')}} {{$email}}</a></li>
          <li><a href="#">{{trans('front.footer.whatsapp')}} {{$whatsapp}}</a></li>
          <li><a href="#">{{trans('front.footer.viber')}} {{$viber}}</a></li>
        </ul>
      </div>
    </div>
    <div class="row justify-content-center ftRet">
      <div class="col-auto">
        <ul>
          <li><a class="btnRet" target="_blank" href="{{$pinterest}}"></a></li>
          <li><a class="btnRet" target="_blank" href="{{$facebook}}"></a></li>
          <li><a class="btnRet" target="_blank" href="{{$instagram}}"></a></li>
          <li><a class="btnRet" target="_blank" href="{{$linkedin}}"></a></li>
          <li><a class="btnRet" target="_blank" href="{{$twitter}}"></a></li>
          <li><a class="btnRet" target="_blank" href="{{$youtube}}"></a></li>
        </ul>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-10">
        <p>{{$footerText}}</p>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-auto">
        <a href="{{url($lang->lang)}}" class="logoFooter"><img src="{{asset('fronts/img/icons/logo.svg')}}" alt=""></a>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-auto">
        <p>©{{date('Y')}} {{trans('front.footer.copyright')}}
            <a target="_blank" href="https://digitalmall.md/">LIKE-MEDIA</a>
        </p>
      </div>
    </div>
  </div>
</div>

<div class="cartPop">
</div>
