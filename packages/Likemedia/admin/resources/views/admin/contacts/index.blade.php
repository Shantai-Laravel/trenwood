@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">General settings</li>
    </ol>
</nav>

@include('admin::admin.alerts')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-block">
                <div class="title-block">
                    <h3 class="title"> Adauga un parametru </h3>
                </div>
                <form method="post" action="{{ route('contacts.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="multiDataWrapp" style="display:block">
                      <div class="form-group hide to-clone" data-id="0">
                          <label>Phone</label>
                          <input type="text" name="phone[]"  class="form-control"><i class="del-field fa fa-trash"></i>
                      </div>
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'phone')
                                  @if(count($contact->translations()->get()) > 0)
                                      @foreach ($contact->translations()->get() as $contact_translation)
                                      <div class="form-group" data-id="{{ $contact->id }}">
                                          <label>Phone</label>
                                          <input type="text" name="phone[]"  class="form-control" value="{{$contact_translation->value}}">
                                      </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label>Phone</label>
                                          <input type="text" name="phone[]" class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                      <div class="text-center">
                          <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                      <div class="form-group hide to-clone" data-id="0">
                          <label>Emails Front</label>
                          <input type="text" name="emailfront[]"  class="form-control">
                      </div>
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'emailfront')
                                  @if(count($contact->translations()->get()) > 0)
                                      @foreach ($contact->translations()->get() as $contact_translation)
                                          <div class="form-group" data-id="{{ $contact->id }}">
                                              <label>Emails Front</label>
                                              <input type="text" name="emailfront[]"  class="form-control" value="{{$contact_translation->value}}">
                                          </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label>Emails Front</label>
                                          <input type="text" name="emailfront[]"  class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                      <div class="text-center">
                          <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                        @if (count($contacts) > 0)
                            @foreach ($contacts as $key => $contact)
                                @if ($contact->title == 'adminname')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Admin Name</label>
                                          <input type="text" name="adminname" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Admin Name</label>
                                          <input type="text" name="adminname"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                        @if (count($contacts) > 0)
                            @foreach ($contacts as $key => $contact)
                                @if ($contact->title == 'site')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Site</label>
                                          <input type="text" name="site" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Site</label>
                                          <input type="text" name="site"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                      <div class="form-group hide to-clone" data-id="0">
                          <label>Emails Administrators</label>
                          <input type="text" name="emailadmin[]"  class="form-control">
                      </div>
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'emailadmin')
                                  @if(count($contact->translations()->get()) > 0)
                                      @foreach ($contact->translations()->get() as $contact_translation)
                                        <div class="form-group" data-id="{{ $contact->id }}">
                                            <label>Emails Administrators</label>
                                            <input type="text" name="emailadmin[]"  class="form-control" value="{{$contact_translation->value}}">
                                        </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label>Emails Administrators</label>
                                          <input type="text" name="emailadmin[]"  class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                      <div class="text-center">
                          <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                      <div class="form-group hide to-clone" data-id="0">
                          <label>Skype</label>
                          <input type="text" name="skype[]"  class="form-control">
                      </div>
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'skype')
                                  @if(count($contact->translations()->get()) > 0)
                                      @foreach ($contact->translations()->get() as $contact_translation)
                                        <div class="form-group" data-id="{{ $contact->id }}">
                                            <label>Skype</label>
                                            <input type="text" name="skype[]"  class="form-control" value="{{$contact_translation->value}}">
                                        </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label>Skype</label>
                                          <input type="text" name="skype[]"  class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                      <div class="text-center">
                          <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                      <div class="form-group hide to-clone" data-id="0">
                          <label>Whatsapp</label>
                          <input type="text" name="whatsapp[]"  class="form-control">
                      </div>
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'whatsapp')
                                  @if(count($contact->translations()->get()) > 0)
                                      @foreach ($contact->translations()->get() as $contact_translation)
                                        <div class="form-group" data-id="{{ $contact->id }}">
                                            <label>Whatsapp</label>
                                            <input type="text" name="whatsapp[]"  class="form-control" value="{{$contact_translation->value}}">
                                        </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label>Whatsapp</label>
                                          <input type="text" name="whatsapp[]"  class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                      <div class="text-center">
                          <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                      <div class="form-group hide to-clone" data-id="0">
                          <label>Viber</label>
                          <input type="text" name="viber[]"  class="form-control">
                      </div>
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'viber')
                                  @if(count($contact->translations()->get()) > 0)
                                      @foreach ($contact->translations()->get() as $contact_translation)
                                        <div class="form-group" data-id="{{ $contact->id }}">
                                            <label>Viber</label>
                                            <input type="text" name="viber[]"  class="form-control" value="{{$contact_translation->value}}">
                                        </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label>Viber</label>
                                          <input type="text" name="viber[]"  class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                      <div class="text-center">
                          <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                      <div class="form-group hide to-clone" data-id="0">
                          <label>Social links</label>
                          <input type="text" name="social[]"  class="form-control">
                      </div>
                        @if (count($contacts) > 0)
                            @foreach ($contacts as $key => $contact)
                                @if ($contact->title == 'facebook')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Facebook</label>
                                          <input type="text" name="facebook" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Facebook</label>
                                          <input type="text" name="facebook"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                                @if ($contact->title == 'instagram')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Instagram</label>
                                          <input type="text" name="instagram" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Instagram</label>
                                          <input type="text" name="instagram"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                                @if ($contact->title == 'twitter')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Twitter</label>
                                          <input type="text" name="twitter" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Twitter</label>
                                          <input type="text" name="twitter"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                                @if ($contact->title == 'google')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Google</label>
                                          <input type="text" name="google" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Google</label>
                                          <input type="text" name="google"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                                @if ($contact->title == 'youtube')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Youtube</label>
                                          <input type="text" name="youtube" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Youtube</label>
                                          <input type="text" name="youtube"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                                @if ($contact->title == 'linkedin')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Linkedin</label>
                                          <input type="text" name="linkedin" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Linkedin</label>
                                          <input type="text" name="linkedin"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                                @if ($contact->title == 'pinterest')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Pinterest</label>
                                          <input type="text" name="pinterest" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Pinterest</label>
                                          <input type="text" name="pinterest"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                                @if ($contact->title == 'social')
                                    @if(count($contact->translations()->get()) > 0)
                                        @foreach ($contact->translations()->get() as $contact_translation)
                                          <div class="form-group" data-id="{{ $contact->id }}">
                                              <label>Social links</label>
                                              <input type="text" name="social[]"  class="form-control" value="{{$contact_translation->value}}">
                                          </div>
                                        @endforeach
                                    @else
                                        <div class="form-group" data-id="1">
                                            <label>Social links</label>
                                            <input type="text" name="social[]"  class="form-control">
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                      <div class="text-center">
                          <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                        @if (count($contacts) > 0)
                            @foreach ($contacts as $key => $contact)
                                @if ($contact->title == 'workWeekdays')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Work on weekdays</label>
                                          <input type="text" name="workWeekdays" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Work on weekdays</label>
                                          <input type="text" name="workWeekdays"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="multiDataWrapp" style="display:block">
                        @if (count($contacts) > 0)
                            @foreach ($contacts as $key => $contact)
                                @if ($contact->title == 'workWeekends')
                                    @if(count($contact->translations()->first()) > 0)
                                      <div class="form-group">
                                          <label>Work on weekends</label>
                                          <input type="text" name="workWeekends" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                      </div>
                                    @else
                                      <div class="form-group">
                                          <label>Work on weekends</label>
                                          <input type="text" name="workWeekends"  class="form-control">
                                      </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group text-center">
                        <hr>
                    </div>
                    <div class="title-block">
                        <h3 class="title"> Informații juridice </h3>
                    </div>


                    @if (count($contacts) > 0)
                        @foreach ($contacts as $key => $contact)
                            @if ($contact->title == 'fisc')
                                @if(count($contact->translations()->first()) > 0)
                                  <div class="form-group">
                                      <label>Fisc cod</label>
                                      <input type="number" name="fisc" value="{{$contact->translations()->first()->value}}"  class="form-control">
                                  </div>
                                @else
                                  <div class="form-group">
                                      <label>Fisc cod</label>
                                      <input type="number" name="fisc"  class="form-control">
                                  </div>
                                @endif
                            @endif
                            @if ($contact->title == 'nds')
                                @if(count($contact->translations()->first()) > 0)
                                  <div class="form-group">
                                      <label>NDS</label>
                                      <input type="number" name="nds" value="{{$contact->translations()->first()->value}}" class="form-control">
                                  </div>
                                @else
                                  <div class="form-group">
                                      <label>NDS</label>
                                      <input type="number" name="nds"  class="form-control">
                                  </div>
                                @endif
                            @endif
                            @if ($contact->title == 'iban')
                                @if(count($contact->translations()->first()) > 0)
                                  <div class="form-group">
                                      <label>IBAN</label>
                                      <input type="text" name="iban" value="{{$contact->translations()->first()->value}}" class="form-control">
                                  </div>
                                @else
                                  <div class="form-group">
                                      <label>IBAN</label>
                                      <input type="text" name="iban"  class="form-control">
                                  </div>
                                @endif
                            @endif
                            @if ($contact->title == 'codbank')
                                @if(count($contact->translations()->first()) > 0)
                                  <div class="form-group">
                                      <label>Cod bank</label>
                                      <input type="text" name="codbank" value="{{$contact->translations()->first()->value}}" class="form-control">
                                  </div>
                                @else
                                  <div class="form-group">
                                      <label>Cod bank</label>
                                      <input type="text" name="codbank"  class="form-control">
                                  </div>
                                @endif
                            @endif
                            @if ($contact->title == 'phonePayment')
                                @if(count($contact->translations()->first()) > 0)
                                  <div class="form-group">
                                      <label>Phone</label>
                                      <input type="text" name="phonePayment" value="{{$contact->translations()->first()->value}}" class="form-control">
                                  </div>
                                @else
                                  <div class="form-group">
                                      <label>Phone</label>
                                      <input type="text" name="phonePayment"  class="form-control">
                                  </div>
                                @endif
                            @endif
                        @endforeach
                    @endif

                    <div class="form-group text-center">
                        <hr>
                    </div>
                    <div class="title-block">
                        <h3 class="title"> Informații Delivery </h3>
                    </div>

                    @if (count($contacts) > 0)
                        @foreach ($contacts as $key => $contact)
                            @if ($contact->title == 'delivery')
                                @if(count($contact->translations()->first()) > 0)
                                  <div class="form-group">
                                      <label>Delivery Price</label>
                                      <input type="number" name="delivery" value="{{$contact->translations()->first()->value}}" class="form-control">
                                  </div>
                                @else
                                  <div class="form-group">
                                      <label>Delivery Price</label>
                                      <input type="number" name="delivery"  class="form-control">
                                  </div>
                                @endif
                            @endif

                            @if ($contact->title == 'treshold')
                                @if(count($contact->translations()->first()) > 0)
                                  <div class="form-group">
                                      <label>Threshold Delivery</label>
                                      <input type="number" name="treshold" value="{{$contact->translations()->first()->value}}" class="form-control">
                                  </div>
                                @else
                                  <div class="form-group">
                                      <label>Threshold Delivery</label>
                                      <input type="number" name="treshold"  class="form-control">
                                  </div>
                                @endif
                            @endif

                        @endforeach
                    @endif

                    <div class="form-group">
                      <label for="img">Upload Image with signature and stamp</label>
                      <input type="file" name="sign" id="img"/>
                    </div>

                    <div class="form-group text-center">
                        <hr>
                        <input type="submit" value="Save" class="btn btn-primary form-control">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
      <div class="card">
          <div class="card-block">
              <div class="title-block">
                  <h3 class="title"> Adauga un parametru multilang </h3>
              </div>
              <form method="post" action="{{ route('contacts.storeMultilang') }}">
                  {{ csrf_field() }}
                  <div class="tab-area">
                      <ul class="nav nav-tabs nav-tabs-bordered">
                          @if (!empty($langs))
                          @foreach ($langs as $key => $lang)
                          <li class="nav-item">
                              <a href="#{{ $lang->lang }}" class="nav-link  {{ $key == 0 ? ' open active' : '' }}"
                                  data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                          </li>
                          @endforeach
                          @endif
                      </ul>
                  </div>
                  @if (!empty($langs))
                  @foreach ($langs as $lang)
                  <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->
                      lang }}><br>

                      <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                          <div class="form-group hide to-clone" data-id="0">
                              <label> Address [{{ $lang->lang }}]</label>
                              <input type="text" name="address_{{ $lang->lang }}[]"  class="form-control">
                          </div>
                          @if (count($contacts) > 0)
                              @foreach ($contacts as $key => $contact)
                                  @if ($contact->title == 'address')
                                      @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                          @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                            <div class="form-group" data-id="1">
                                                <label> Address [{{ $lang->lang }}]</label>
                                                <input type="text" name="address_{{ $lang->lang }}[{{ $contact_translation->id }}]" value="{{$contact_translation->value}}"  class="form-control">
                                            </div>
                                          @endforeach
                                      @else
                                          <div class="form-group" data-id="1">
                                              <label> Address [{{ $lang->lang }}]</label>
                                              <input type="text" name="address_{{ $lang->lang }}[]"  class="form-control">
                                          </div>
                                      @endif
                                  @endif
                              @endforeach
                          @endif
                          <div class="text-center">
                              <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                          </div>
                      </div>


                    <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                        <div class="form-group hide to-clone" data-id="0">
                            <label> Shipping and Returns [{{ $lang->lang }}]</label>
                            <input type="text" name="shipping_and_returns_{{ $lang->lang }}[]"  class="form-control">
                        </div>
                        @if (count($contacts) > 0)
                            @foreach ($contacts as $key => $contact)
                                @if ($contact->title == 'shipping_and_returns')
                                    @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                        @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                          <div class="form-group" data-id="1">
                                              <label> Shipping and Returns [{{ $lang->lang }}]</label>
                                              <textarea name="shipping_and_returns_{{ $lang->lang }}[{{ $contact_translation->id }}]" class="form-control">{{$contact_translation->value}}</textarea>
                                          </div>
                                        @endforeach
                                    @else
                                        <div class="form-group" data-id="1">
                                            <label> Shipping and Returns [{{ $lang->lang }}]</label>
                                            <textarea name="shipping_and_returns_{{ $lang->lang }}[]"  class="form-control"></textarea>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        <div class="text-center">
                            <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>

                      <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                          <div class="form-group hide to-clone" data-id="0">
                              <label> Magazins [{{ $lang->lang }}]</label>
                              <input type="text" name="magazins_{{ $lang->lang }}[]"  class="form-control">
                          </div>
                          @if (count($contacts) > 0)
                              @foreach ($contacts as $key => $contact)
                                  @if ($contact->title == 'magazins')
                                      @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                          @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                            <div class="form-group" data-id="1">
                                                <label> Magazins [{{ $lang->lang }}]</label>
                                                <input type="text" name="magazins_{{ $lang->lang }}[{{ $contact_translation->id }}]" value="{{$contact_translation->value}}"  class="form-control">
                                            </div>
                                          @endforeach
                                      @else
                                          <div class="form-group" data-id="1">
                                              <label> Magazins [{{ $lang->lang }}]</label>
                                              <input type="text" name="magazins_{{ $lang->lang }}[]"  class="form-control">
                                          </div>
                                      @endif
                                  @endif
                              @endforeach
                          @endif
                          <div class="text-center">
                              <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                          </div>
                      </div>

                      <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                          <div class="form-group hide to-clone" data-id="0">
                              <label> Footer text [{{ $lang->lang }}]</label>
                              <input type="text" name="footertext_{{ $lang->lang }}[]"  class="form-control">
                          </div>
                          @if (count($contacts) > 0)
                              @foreach ($contacts as $key => $contact)
                                  @if ($contact->title == 'footertext')
                                      @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                          @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                            <div class="form-group" data-id="1">
                                                <label> Footer text [{{ $lang->lang }}]</label>
                                                <textarea name="footertext_{{ $lang->lang }}[{{ $contact_translation->id }}]" class="form-control" rows="8" cols="80">{{$contact_translation->value}}</textarea>
                                            </div>
                                          @endforeach
                                      @else
                                          <div class="form-group" data-id="1">
                                              <label> Footer text [{{ $lang->lang }}]</label>
                                              <textarea name="footertext_{{ $lang->lang }}[]" class="form-control" rows="8" cols="80"></textarea>
                                          </div>
                                      @endif
                                  @endif
                              @endforeach
                          @endif
                          <div class="text-center">
                              <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                          </div>
                      </div>

                      <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                          <div class="form-group hide to-clone" data-id="0">
                              <label> Footer text description [{{ $lang->lang }}]</label>
                              <input type="text" name="footertextdesc_{{ $lang->lang }}[]"  class="form-control">
                          </div>
                          @if (count($contacts) > 0)
                              @foreach ($contacts as $key => $contact)
                                  @if ($contact->title == 'footertextdesc')
                                      @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                          @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                            <div class="form-group" data-id="1">
                                                <label> Footer text description [{{ $lang->lang }}]</label>
                                                <textarea name="footertextdesc_{{ $lang->lang }}[{{ $contact_translation->id }}]" class="form-control" rows="8" cols="80">{{$contact_translation->value}}</textarea>
                                            </div>
                                          @endforeach
                                      @else
                                          <div class="form-group" data-id="1">
                                              <label> Footer text description [{{ $lang->lang }}]</label>
                                              <textarea name="footertextdesc_{{ $lang->lang }}[]" class="form-control" rows="8" cols="80"></textarea>
                                          </div>
                                      @endif
                                  @endif
                              @endforeach
                          @endif
                          <div class="text-center">
                              <a class="text-warning add-contact" href="#"><i class="fa fa-plus"></i></a>
                          </div>
                      </div>

                      <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                          @if (count($contacts) > 0)
                              @foreach ($contacts as $key => $contact)
                                  @if ($contact->title == 'weekend')
                                      @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                          @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                            <div class="form-group" data-id="1">
                                                <label> Weekend [{{ $lang->lang }}]</label>
                                                <input type="text" name="weekend_{{ $lang->lang }}[{{ $contact_translation->id }}]" value="{{$contact_translation->value}}"  class="form-control">
                                            </div>
                                          @endforeach
                                      @else
                                          <div class="form-group" data-id="1">
                                              <label> Weekend [{{ $lang->lang }}]</label>
                                              <input type="text" name="weekend_{{ $lang->lang }}[]"  class="form-control">
                                          </div>
                                      @endif
                                  @endif
                              @endforeach
                          @endif
                      </div>

                  <div class="form-group text-center">
                      <hr>
                  </div>

                  <div class="title-block">
                      <h3 class="title"> Informații juridice multilang </h3>
                  </div>

                  <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'company')
                                  @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                      @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                        <div class="form-group" data-id="1">
                                            <label> Company [{{ $lang->lang }}]</label>
                                            <input type="text" name="company_{{ $lang->lang }}[{{ $contact_translation->id }}]" value="{{$contact_translation->value}}"  class="form-control">
                                        </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label> Company [{{ $lang->lang }}]</label>
                                          <input type="text" name="company_{{ $lang->lang }}[]"  class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                  </div>

                  <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'companyAddress')
                                  @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                      @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                        <div class="form-group" data-id="1">
                                            <label> Company address [{{ $lang->lang }}]</label>
                                            <input type="text" name="companyAddress_{{ $lang->lang }}[{{ $contact_translation->id }}]" value="{{$contact_translation->value}}"  class="form-control">
                                        </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label> Company address [{{ $lang->lang }}]</label>
                                          <input type="text" name="companyAddress_{{ $lang->lang }}[]"  class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                  </div>

                  <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                      @if (count($contacts) > 0)
                          @foreach ($contacts as $key => $contact)
                              @if ($contact->title == 'bankname')
                                  @if(count($contact->translationByLanguage($lang->id)->get()) > 0)
                                      @foreach ($contact->translationByLanguage($lang->id)->get() as $contact_translation)
                                        <div class="form-group" data-id="1">
                                            <label> Bank name [{{ $lang->lang }}]</label>
                                            <input type="text" name="bankname_{{ $lang->lang }}[{{ $contact_translation->id }}]" value="{{$contact_translation->value}}"  class="form-control">
                                        </div>
                                      @endforeach
                                  @else
                                      <div class="form-group" data-id="1">
                                          <label> Bank name [{{ $lang->lang }}]</label>
                                          <input type="text" name="bankname_{{ $lang->lang }}[]"  class="form-control">
                                      </div>
                                  @endif
                              @endif
                          @endforeach
                      @endif
                  </div>

                  </div>
                  @endforeach
                  @endif
                  <div class="form-group text-center">
                      <hr>
                      <input type="submit" value="Save" class="btn btn-primary form-control">
                  </div>
              </form>
          </div>
      </div>
    </div>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
