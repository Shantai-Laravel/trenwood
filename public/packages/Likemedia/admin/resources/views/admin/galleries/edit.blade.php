@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('galleries.index') }}">Galleries</a></li>
        <li class="breadcrumb-item active" aria-current="gallery">Edit gallery</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea gallery </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('galleries.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('galleries.update', $gallery->id) }}" id="add-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="tab-content active-content">
            <div class="part full-part">
                <ul>
                    <li>
                        <label for="alias">Alias (Short Code)</label>
                        <input type="text" name="alias" class="name" disabled  id="alias" value="{{ $gallery->alias }}">
                        <input type="hidden" name="alias" class="name" id="alias" value="{{ $gallery->alias }}">
                    </li>
                </ul>

                <div class="row">
                    <div class="col-md-8">
                        Gallery
                          {{ csrf_field() }}

                            @if (!empty($images))
                                @foreach ($images as $key => $image)
                                    <div class="row image-list">
                                        <div class="col-md-5">
                                            <img src="/images/galleries/bg/{{ $image->src }}" alt="" class="{{ $image->main == 1 ? 'main-image' : '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            @foreach ($langs as $key => $lang)
                                                <div class="form-group row">
                                                   <div class="col-md-4 text-right">
                                                        <label for="">Text [{{ $lang->lang }}]</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                       <input type="text" name="is_text[{{ $image->id }}][{{ $lang->id }}]" class="form-control" value="{{ $image->translationByLanguage($lang->id)->first()->text }}">
                                                   </div>
                                                </div>
                                                <div class="form-group row">
                                                   <div class="col-md-4 text-right">
                                                        <label for="">Alt [{{ $lang->lang }}]</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                       <input type="text" name="is_alt[{{ $image->id }}][{{ $lang->id }}]" class="form-control" value="{{ $image->translationByLanguage($lang->id)->first()->alt }}">
                                                   </div>
                                                </div>
                                                <div class="form-group row">
                                                   <div class="col-md-4 text-right">
                                                        <label for="">Title [{{ $lang->lang }}]</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                       <input type="text" name="is_title[{{ $image->id }}][{{ $lang->id }}]" class="form-control" value="{{ $image->translationByLanguage($lang->id)->first()->title }}">
                                                   </div>
                                               </div>
                                               <br>
                                            @endforeach
                                        </div>
                                        <div class="col-md-1">
                                            <a href="#" class="delete-btn" data-id="{{ $image->id }}"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                    </div>
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

    $().ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            }
        });

        $('.delete-btn').on('click', function(){
            $id = $(this).attr('data-id');
            $galleryId = '{{ $gallery->id }}';

            $.ajax({
                type: "POST",
                url: '/back/gallery/images/delete',
                data: {
                    id: $id,
                    galleryId: $galleryId,
                },
                success: function(data) {
                    if (data === 'true') {
                        location.reload();
                    }
                }
            });
        });
    });
</script>


@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
