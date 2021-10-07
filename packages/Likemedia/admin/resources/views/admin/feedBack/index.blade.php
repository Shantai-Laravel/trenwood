@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Feed Back</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Feed Back </h3>

</div>
@include('admin::admin.alerts')
@if(!$feedbacks->isEmpty())
<div class="card">

    <div class="tab-area">
        <ul class="nav nav-tabs nav-tabs-bordered">
            <li class="nav-item">
                <a href="#new" class="nav-link open active" data-target="#new">Nou</a>
            </li>
            <li class="nav-item">
                <a href="#procesed" class="nav-link" data-target="#procesed">In procesare</a>
            </li>
            <li class="nav-item">
                <a href="#cloose" class="nav-link" data-target="#cloose">Inchis</a>
            </li>
        </ul>
    </div>
    <div class="card-block">
        <div class="tab-content active-content" id="new">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Prenume/Nume</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Form</th>
                        <th class="text-center">Status</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        <th>{{trans('variables.delete_table')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacksNew as $key => $feedBack)
                    <tr id="{{ $feedBack->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $feedBack->first_name }} / {{ $feedBack->second_name }}
                        </td>
                        <td>
                            {{ $feedBack->email }}
                        </td>
                        <td>
                            {{ $feedBack->phone }}
                        </td>
                        <td>
                            {{ $feedBack->form }}
                        </td>
                        <td class="text-center">
                            @if ($feedBack->status == 'new')
                                <span class="label label-primary">new</span>
                            @elseif ($feedBack->status == 'procesed')
                                <span class="label label-success">procesed</span>
                            @elseif ($feedBack->status == 'cloose')
                                <span class="label label-danger">cloose</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('feedback.edit', $feedBack->id) }}">
                            <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="destroy-element">
                            <form action="{{ route('feedback.destroy', $feedBack->id) }}" method="post">
                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                <button type="submit" class="btn-link">
                                <a href=""><i class="fa fa-trash"></i></a>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=8></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="tab-content" id="procesed">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Prenume/Nume</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Form</th>
                        <th class="text-center">Status</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        <th>{{trans('variables.delete_table')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacksProcesed as $key => $feedBack)
                    <tr id="{{ $feedBack->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $feedBack->first_name }} / {{ $feedBack->second_name }}
                        </td>
                        <td>
                            {{ $feedBack->email }}
                        </td>
                        <td>
                            {{ $feedBack->phone }}
                        </td>
                        <td>
                            {{ $feedBack->form }}
                        </td>
                        <td class="text-center">
                            @if ($feedBack->status == 'new')
                                <span class="label label-primary">new</span>
                            @elseif ($feedBack->status == 'procesed')
                                <span class="label label-success">procesed</span>
                            @elseif ($feedBack->status == 'cloose')
                                <span class="label label-danger">cloose</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('feedback.edit', $feedBack->id) }}">
                            <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="destroy-element">
                            <form action="{{ route('feedback.destroy', $feedBack->id) }}" method="post">
                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                <button type="submit" class="btn-link">
                                <a href=""><i class="fa fa-trash"></i></a>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=8></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="tab-content" id="cloose">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Prenume/Nume</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Form</th>
                        <th class="text-center">Status</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        <th>{{trans('variables.delete_table')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacksCloose as $key => $feedBack)
                    <tr id="{{ $feedBack->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $feedBack->first_name }} / {{ $feedBack->second_name }}
                        </td>
                        <td>
                            {{ $feedBack->email }}
                        </td>
                        <td>
                            {{ $feedBack->phone }}
                        </td>
                        <td>
                            {{ $feedBack->form }}
                        </td>
                        <td class="text-center">
                            @if ($feedBack->status == 'new')
                                <span class="label label-primary">new</span>
                            @elseif ($feedBack->status == 'procesed')
                                <span class="label label-success">procesed</span>
                            @elseif ($feedBack->status == 'cloose')
                                <span class="label label-danger">cloose</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('feedback.edit', $feedBack->id) }}">
                            <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="destroy-element">
                            <form action="{{ route('feedback.destroy', $feedBack->id) }}" method="post">
                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                <button type="submit" class="btn-link">
                                <a href=""><i class="fa fa-trash"></i></a>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=8></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
