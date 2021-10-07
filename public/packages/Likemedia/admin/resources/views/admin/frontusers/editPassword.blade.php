@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('frontusers.index') }}">Front Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Front User</li>
    </ol>
</nav>

<div class="title-block">
    <h3 class="title"> Edit Front User </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('frontusers.create'),
    ]
    ])
</div>


    <div class="list-content">
        <div class="tab-area">
            @include('admin::admin.alerts')
        </div>

        <form class="form-reg" role="form" method="POST" action="{{ route('frontusers.updatePassword', $user->id) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="part left-part">
                <ul>
                    <li>
                        <label for="oldpassword">Old Password</label>
                        <input type="password" name="oldpassword" class="name" id="oldpassword" value="">
                    </li>
                    <li>
                        <label for="newpassword">New Password</label>
                        <input type="password" name="newpassword" class="name" id="newpassword" value="">
                    </li>
                    <li>
                        <label for="repeatpassword">Repeat Password</label>
                        <input type="password" name="repeatpassword" class="name" id="repeatpassword" value="">
                    </li>
                </ul>
            </div>
            <ul>
                <div class="row">
                    <div class="col-md-6">
                        <li>
                            <input type="submit" value="{{trans('variables.save_it')}}">
                        </li>
                    </div>
                </div>
            </ul>
        </form>
    </div>
@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
