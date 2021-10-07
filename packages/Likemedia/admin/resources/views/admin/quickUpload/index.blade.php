@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@section('content')

<nav aria-label="breadcrumb"></nav>

<div class="list-content">
<div class="row page-actions">
    <div class="col-md-7">
        <ul>
            <li>
                <form action="{{ url('/back/quick-upload') }}" method="GET">
                    <div class="col-md-1">
                        <label>Categorie</label>
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" class="cat-id" value="{{ Request::get('category') }}">
                        <select name="category" class="form-control category-select">
                            <option value="0">---</option>
                            @if (count($categories) > 0)
                            @foreach($categories as $category)
                            <option {{ $category->id == Request::get('category') ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->translation()->first()->name }}</option>
                            <option value="{{ $category->id }}">{{ $category->translation()->first()->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                <div class="col-md-1">
                    <input type="submit" class="btn btn-primary" data="redirect-cat" value=" Go">
                </div>
                <div class="col-md-3">
                    <a href="{{ url('back/quick-upload/download/'.Request::get('category')) }}" class="btn btn-primary"> Download CSV template</a>
                </div>
                </form>

                <form action="{{ url('back/quick-upload/upload') }}" method="post" enctype="multipart/form-data">
                    <div class="col-md-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="categoryId" value="{{ Request::get('category') }}">
                        <label for="">Upload CSV with products</label>
                        <input type="file" name="file">
                        {{-- <a href="{{ url('back/quick-upload/upload/'.Request::get('category')) }}" class="btn btn-primary">Upload CSV with products</a> --}}
                    </div>
                    <div class="col-md-1">
                        <input type="submit" class="btn btn-primary" data="redirect-cat" value=" Go">
                    </div>
                </form>


            </li>
        </ul>
    </div>
    <div class="col-md-3 text-right">
    </div>

    <div class="col-md-5 text-right">
        <input type="button"  class="btn btn-primary save-upload"  value="Save & Add">
        <input type="button"  class="btn btn-primary save-upload"  value="Save">
        <a href="{{ url('back/products/category/'.Request::get('category')) }}" class="btn btn-primary">Save & Back</a>
    </div>
</div>
<hr>
<div class="card">
    <div class="card-block scrollX">
        <table class="table table-bordered centred ajax-response">
            <thead>
                <tr>
                    <th class="width-auto">#</th>
                    <th class="width-auto">Lang</th>
                    <th>Titlu <small>*required</small> </th>
                    <th>Descriere</th>
                    <th class="width-auto">Pret</th>
                    <th class="width-auto">Discount</th>
                    <th>Brand</th>
                    <th>Promotie</th>
                    @if (!empty($properties))
                    @foreach ($properties as $key => $property)
                    <th>{{ $property->translation->first()->name }}</th>
                    @endforeach
                    @endif
                    <th>Imagini</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($products))
                @foreach ($products as $key => $product)
                @if (!empty($langs))
                @foreach ($langs as $keyLang => $oneLang)
                @if ($keyLang == 0)
                <tbody class="item-row" data-id={{ $product->id }}>
                <tr>
                    <td rowspan="2"  class="width-auto">{{ $key + 1 }}</td>
                    <td  class="width-auto">{{ $oneLang->lang }}</td>
                    <td>
                        <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="{{ $product->translationByLanguage($oneLang->id)->first()->name }}">
                    </td>
                    <td>
                        <input type="text" class="input-body" data-lang="{{ $oneLang->id }}" value="{{ $product->translationByLanguage($oneLang->id)->first()->body }}">
                    </td>
                    <td rowspan="2" class="width-auto">
                        <input type="number" class="input-price" value="{{ $product->price }}">
                    </td>
                    <td rowspan="2" class="width-auto">
                        <input type="number" class="input-discount" value="{{ $product->discount }}">
                    </td>
                    <td rowspan="2">
                        <select class="form-control input-brand_id">
                            <option value="0">---</option>
                            @if (count($brands) > 0)
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->translationByLanguage($lang->id)->first()->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </td>
                    <td rowspan="2">
                        <select class="form-control input-promo_id">
                            <option value="0">---</option>
                            @if (count($promotions) > 0)
                            @foreach($promotions as $promotion)
                            <option value="{{ $promotion->id }}" {{ $product->promotion_id == $promotion->id ? 'selected' : '' }}>{{ $promotion->translationByLanguage($lang->id)->first()->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </td>
                    @if (!empty($properties))
                    @foreach ($properties as $key => $property)
                    <td rowspan="2">
                        @if ($property->type == 'select')
                        <select name="prop[{{ $property->id }}]" class="form-control prop-input" data-id="{{ $property->id }}">
                            <option value="0">---</option>
                            @if (!empty($property->multidata)))
                            @foreach ($property->multidata as $key => $multidata)
                            <?php $value = getMultiDataList($multidata->id, 1); ?>
                            <option value="{{ $value->property_multidata_id }}" {{ getPropertiesData($product->id, $property->id) ==  $value->property_multidata_id ? 'selected' : ''  }}>{{ $value->name}} {{ $property->translationByLanguage($lang->id)->first()->unit }}</option>
                            @endforeach
                            @endif
                        </select>
                        @endif
                    </td>
                    @endforeach
                    @endif
                    <td rowspan="2">
                        <button type="button" name="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gallery-modal{{ $product->id }}"> <i class="fa fa-image"></i> Imagini</button>
                        <p>{{ count($product->images) }}</p>
                        @include('admin::admin.quickUpload.galleryModal', ['product' => $product])
                    </td>
                </tr>
                @else
                <tr>
                    <td  class="width-auto">{{ $oneLang->lang }}</td>
                    <td>
                        <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="{{ $product->translationByLanguage($oneLang->id)->first()->name }}">
                    </td>
                    <td>
                        <input type="text" class="input-body"  data-lang="{{ $oneLang->id }}" value="{{ $product->translationByLanguage($oneLang->id)->first()->body }}">
                    </td>

                </tr>
                </tbody>
                @endif
                @endforeach
                @endif
                @endforeach
                @endif

                <tbody class="item-row">
                @foreach ($langs as $keyLang => $oneLang)
                @if ($keyLang == 0)
                <tr>
                    <td rowspan="2"  class="width-auto"><small><i>new</i></small></td>
                    <td  class="width-auto">{{ $oneLang->lang }}</td>
                    <td>
                        <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="">
                    </td>
                    <td>
                        <input type="text" class="input-body" data-lang="{{ $oneLang->id }}" value="">
                    </td>
                    <td rowspan="2" class="width-auto">
                        <input type="number" class="input-price" value="">
                    </td>
                    <td rowspan="2" class="width-auto">
                        <input type="number" class="input-discount" value="">
                    </td>
                    <td rowspan="2">
                        <select class="form-control input-brand_id">
                            <option value="0">---</option>
                            @if (count($brands) > 0)
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->translationByLanguage($lang->id)->first()->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </td>
                    <td rowspan="2">
                        <select class="form-control input-promo_id">
                            <option value="0">---</option>
                            @if (count($promotions) > 0)
                            @foreach($promotions as $promotion)
                            <option value="{{ $promotion->id }}">{{ $promotion->translationByLanguage($lang->id)->first()->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </td>
                    @if (!empty($properties))
                    @foreach ($properties as $key => $property)
                    <td rowspan="2">
                        @if ($property->type == 'select')
                        <select name="prop[{{ $property->id }}]" class="form-control prop-input" data-id="{{ $property->id }}">
                            <option value="0">---</option>
                            @if (!empty($property->multidata)))
                            @foreach ($property->multidata as $key => $multidata)
                            <?php $value = getMultiDataList($multidata->id, 1); ?>
                            <option value="{{ $value->property_multidata_id }}" {{ getPropertiesData(Request::segment(3), $property->id) ==  $value->property_multidata_id ? 'selected' : ''  }}>{{ $value->name}} {{ $property->translationByLanguage($lang->id)->first()->unit }}</option>
                            @endforeach
                            @endif
                        </select>
                        @endif
                    </td>
                    @endforeach
                    @endif

                    <td rowspan="2">
                        {{-- <button type="button" name="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#gallery-modal{{ $product->id }}">Imagini</button> --}}
                        {{-- @include('admin::admin.quickUpload.galleryModal', ['product' => $product]) --}}
                    </td>
                </tr>
                @else
                <tr>
                    <td  class="width-auto">{{ $oneLang->lang }}</td>
                    <td>
                        <input type="text" class="input-name" data-lang="{{ $oneLang->id }}" value="">
                    </td>
                    <td>
                        <input type="text" class="input-body" data-lang="{{ $oneLang->id }}" value="">
                    </td>
                </tr>
                @endif
                @endforeach

                </tbody>
            </tbody>

        </table>
        <button type="button" name="button" class="btn btn-primary save-upload"><i class="fa fa-plus"></i> save & add</button>
    </div>
</div>

<style>
    .app, .header{
        padding-left: 0;
        left: 0;
    }
    .centred td, th{
        text-align: center;
        vertical-align: middle !important;
        padding: 0 !important;
        min-width: 200px;
    }
    .width-auto{
        min-width: 50px !important;
    }
    .centred input{
        display: block;
        width: 100%;
        min-height: 40px;
        margin: 0;
        border: none;
        padding: 2px 5px;
    }
    .centred select{
        display: block;
    }
    .scrollX{
        overflow-y: scroll;
    }
    tbody {
        overflow-x: auto;
    }
    .item-row{
        border-bottom: 2px solid #85CE36;
        overflow: hidden;
    }
    #loading-image{
         background-color: rgba(255, 255, 255, 0.3);
         position: fixed;
         width: 100%;
         height: 100%;
         top: 0;
         left: 0;
         bottom: 0;
         right: 0;
         z-index: 999;
         transition: 0.3s ease;
         display: none;
         text-align: center;
         height: 100vh;
    }
    #loading-image img{
        display: block;
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -50px;
        margin-top: -50px;
        width: 100px;
    }
    .changed{
        border-left: 5px solid #27ae60;
    }
    .fixed{
        position: fixed;
        width: 100%;
        z-index: 999;
        top: 0;
        background-color: #f0f3f6;
        box-shadow: 1px 1px 5px rgba(126, 142, 159, 0.1);
    }
    label{
        margin-top: 10px;
    }
    select.form-control:not([size]):not([multiple]){
        height: 34px;
    }
</style>

<div id="loading-image"><img src="{{ asset('fronts/img/preloader.gif') }}" alt=""></div>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
    <script src="{{ asset('admin/js/quick-upload.js') }}"></script>

    <script>
        function preview_image(){
            var total_file=document.getElementById("upload_file").files.length;
            for(var i=0; i < total_file; i++){
                $('#image_preview').append(
                    "<div class='row append'><div class='col-md-12'><img src='"+URL.createObjectURL(event.target.files[i])+"'alt=''></div><div class='col-md-12'>@foreach ($langs as $key => $lang)<label for=''>Alt[{{ $lang->lang }}]</label><input type='text' name='alt[{{ $lang->id }}][]'><label for=''>Title[{{ $lang->lang }}]</label><input type='text' name='title[{{ $lang->id }}][]'>@endforeach </div><hr><br>"
                );
            }
        }

        $().ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                }
            });


            $(document).on('click', '.delete-btn', function(){
                $id = $(this).attr('data-id');
                $productId = $(this).attr('data');



                $.ajax({
                    type: "POST",
                    url: '/back/products/gallery/delete',
                    data: {
                        id: $id,
                        productId: $productId,
                    },
                    success: function(data) {
                        // $(this).parent().hide();
                        // $(this).parent().prev().hide();
                    }
                });

                $(this).parent().hide();
                $(this).parent().prev().hide();
            });

        });
    </script>
</footer>

<script>

</script>

@stop
