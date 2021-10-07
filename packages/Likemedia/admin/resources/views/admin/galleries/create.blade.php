@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('galleries.index') }}">Galleries</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Gallery</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Gallery </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('galleries.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('galleries.store') }}" id="add-form"
        enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="tab-content active-content">
            <div class="part full-part">
                <ul>
                    <li>
                        <label for="alias">Alias (Short Code)</label>
                        <input type="alias" name="alias" class="name"
                            id="alias">
                    </li>
                </ul>

                <div class="row">
                    <div class="col-md-4">
                        Upload  images
                        <div class="form-group">
                              <label for="upload">choice images</label>
                              <input type="file" id="upload_file" name="images[]" onchange="preview_image();" multiple/>
                              <div id="image_preview"></div>
                              <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul><hr>
            <li>
                <input type="submit" value="{{trans('variables.save_it')}}" data-form-id="add-form">
            </li>
        </ul>


    </form>
</div>

<script>
function preview_image(){
    var total_file=document.getElementById("upload_file").files.length;
    for(var i=0; i < total_file; i++){
        $('#image_preview').append(
            "<div class='row append'><div class='col-md-12'><img src='"+URL.createObjectURL(event.target.files[i])+"'alt=''></div><div class='col-md-12'>@foreach ($langs as $key => $lang)<label for=''>Text[{{ $lang->lang }}]</label><input type='text' name='text[{{ $lang->id }}][]'> <label for=''>Alt[{{ $lang->lang }}]</label><input type='text' name='alt[{{ $lang->id }}][]'><label for=''>Title[{{ $lang->lang }}]</label><input type='text' name='title[{{ $lang->id }}][]'><hr><br><br> @endforeach </div>"
        );
    }
}
</script>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
