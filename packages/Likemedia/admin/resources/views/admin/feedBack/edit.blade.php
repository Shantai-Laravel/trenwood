@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('feedback.index') }}">Feedback</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Feedback</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea FeedBack </h3>
</div>
@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('pages.update', $feedBack->id) }}" id="add-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="tab-content  active-content">
            <div class="part full-part">

                <div class="col-md-6">
                    <h6>
                        <small>forma - </small> {{ $feedBack->form }}
                        @if ($feedBack->status === 'new')
                            <span class="label label-primary">new</span>
                        @elseif ($feedBack->status === 'procesed')
                            <span class="label label-success">procesed</span>
                        @elseif ($feedBack->status === 'cloose')
                            <span class="label label-danger">cloose</span>
                        @endif
                    </h6>
                    <ul>
                        <li>
                            <label for="first_name">Prenume</label>
                            <input type="text" name="first_name" class="name" id="first_name" value="{{ $feedBack->first_name }}">
                        </li>
                        <li>
                            <label for="second_name">Nume</label>
                            <input type="text" name="second_name" class="name" id="second_name" value="{{ $feedBack->second_name }}">
                        </li>
                        <li>
                            <label for="email">Email</label>
                            <input type="text" name="email" class="name" id="email" value="{{ $feedBack->email }}">
                        </li>
                        <li>
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="name" id="phone" value="{{ $feedBack->phone }}">
                        </li>
                        <li><br>
                            <label for="phone">Schimba statutul</label>

                            <a href="{{ url('back/feedback/clooseStatus/'.$feedBack->id.'/new') }}" class="btn btn-success btn-sm rounded-s">New</a>
                            <a href="{{ url('back/feedback/clooseStatus/'.$feedBack->id.'/procesed') }}" class="btn btn-success btn-sm rounded-s">In procesare</a>
                            <a href="{{ url('back/feedback/clooseStatus/'.$feedBack->id.'/cloose') }}" class="btn btn-success btn-sm rounded-s">Inchis</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" class="name" id="subject" value="{{ $feedBack->subject }}">
                        </li>
                        <li>
                            <label for="message">Message</label>
                            <textarea type="text" name="message" class="name" id="message">{{ $feedBack->message }}</textarea>
                        </li>
                        <li>
                            <label for="additional_1">Data intilnirii</label>
                            <input type="text" name="additional_1" class="name" id="additional_1" value="{{ $feedBack->additional_1 }}">
                        </li>
                        <li>
                            <label for="additional_2">Data casatoriei</label>
                            <input type="text" name="additional_2" class="name" id="additional_2" value="{{ $feedBack->additional_2 }}">
                        </li>
                    </ul>
                </div>

            </div>
        </div>
            <ul>
                <li class="text-center">
                    <hr>
                    <input type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}" data-form-id="add-form">
                </li>
            </ul>
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
