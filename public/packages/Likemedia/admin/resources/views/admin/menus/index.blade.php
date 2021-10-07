@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Grupuri de menu</a></li>
        <li class="breadcrumb-item active" aria-current="page">Grupa {{ $menuGroup->name }}</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Menu - <b>{{ $menuGroup->name }}</b> </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('menus.create').'?group='.$menuGroup->id,
    ]
    ])
</div>

@include('admin::admin.alerts')
<div class="list-content">
    <div class="part full-part min-height">
        <div id="container">
            <a class="btn btn-primary modal-id" data-toggle="modal" data-target="#addCategory" data-id="0"><i class="fa fa-plus"></i></a>
            <a href="{{ route('menus.clean') }}" class="btn btn-primary">Sterge toate puncte de meniu care nu mai exista</a>
        </div>
        <div class="dd" id="nestable-output">
            {!! SelectMenusTree(1, 0, $curr_id=null, 0, Request::segment(4)) !!}
            <div class="nestable-stop"></div>
        </div>
        <script>
            var data = {{ $general }};
            if (data !== null) {
                jQuery(function($){
                    $('.nestable-stop').hide();
                })

                $('#nestable-output').nestable();
            }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    }
                });

                $(document).ready(function () {
                    var updateOutput = function (e) {
                        var list = e.length ? e : $(e.target), output = list.data('output');

                        var data = localStorage.getItem("nestable");
                        if (data !== null) {

                        $.ajax({
                            method: "POST",
                            url: "{{ route('menus.change') }}",
                            data: {
                                list: list.nestable('serialize')
                            },
                            success:  function(data){
                                console.log(JSON.parse(data).message);
                                if (JSON.parse(data).message == false) {
                                    var response = JSON.parse(data);
                                    $('#moveModal').modal('show');
                                    $('.parent_id').val(response.parentId);
                                    $('.child_id').val(response.childId);
                                }
                                $('#nestable-output').html(JSON.parse(data).text);
                            },
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            alert("Unable to save new list order: " + errorThrown);
                        });
                    };

                    }

                    $('#nestable-output').nestable({
                        group: 1,
                        maxDepth: 3,
                    }).on('change', updateOutput);
                });

                $('#container').on("changed.jstree", function (e, data) {
                    console.log("The selected nodes are:");
                    console.log(data.selected);
                });

        </script>
    </div>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@include('admin::admin.menus.modals')
<script>
    $('.modal-id').click(function () {
        $('#parent_id').val($(this).data('id'));
        $('.category-id').val($(this).data('id'));
        $('.parent_id').val($(this).data('id'));
        $('.category-name').text($(this).attr('data-name'));
    })
</script>
@stop
