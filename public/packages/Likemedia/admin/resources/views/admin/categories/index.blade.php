@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Categoriile de articole</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Categorile de articole </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('categories.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <div class="part full-part min-height">
        <div id="container">
            <a class="btn-link btn btn-primary modal-id" data-toggle="modal" data-target="#addCategory" data-id="0">
            <small><i class="fa fa-plus"></i> Adauga o categorie</small>
            </a>
            <a class="btn-link btn btn-primary modal-id" href="{{ Request::url() }}">
            <small><i class="fa fa-refresh"></i> Salveaza</small>
            </a>
        </div>
        <div class="dd" id="nestable-output">
            {!! SelectGoodsCatsTree($lang->id, 0, $curr_id=null) !!}
        </div>
        <script>
            $('#nestable-output').nestable();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                }
            });

            $(document).ready(function () {
                var updateOutput = function (e) {
                    var list = e.length ? e : $(e.target), output = list.data('output');

                    $.ajax({
                        method: "POST",
                        url: "{{ route('categories.change') }}",
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
@include('admin::admin.categories.modals')
@stop
