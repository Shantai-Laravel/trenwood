@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
          <li class="breadcrumb-item"><a href="{{ route('autometa.index') }}">AutoMeta</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create AutoMeta</li>
      </ol>
  </nav>

  <div class="title-block">
      <h3 class="title"> Create AutoMeta </h3>
      @include('admin::admin.list-elements', [
      'actions' => [
      trans('variables.add_element') => route('autometa.create'),
      ]
      ])
  </div>

    <div class="list-content">
        <div class="tab-area">
            @include('admin::admin.alerts')
        </div>

        <form class="form-reg" method="POST" id="newAutometaForm" action="{{ route('autometa.store') }}">
          {{ csrf_field() }}


                    <div class="tab-content active-content" >
                        <div class="part left-part">

                            <ul>

                                <h6>Auto Meta</h6>

                                <input type="hidden" id="category_id" name="categories_id" value="">

                                <li>
                                    <select name="autometa_type">
                                      <option value="1">Category</option>
                                      <option value="2">Product</option>
                                    </select>
                                </li>

                                <li>
                                    <select name="lang_id">
                                        @foreach($langs as $lang)
                                            <option value="{{ $lang->id }}">{{ $lang->lang }}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li>
                                    <label>Name</label>
                                    <input type="text" class="draggableForAll" name="name" value="{{ old('name') }}">
                                </li>

                                <li>
                                    <label>Seo text</label>
                                    <input type="text" class="draggableForAll" name="seotext" value="{{ old('seotext') }}">
                                </li>

                                <li>
                                    <label>Product Description</label>
                                    <input type="text" class="draggableForAll" name="product_description" value="{{ old('product_description') }}">
                                </li>

                                <li>
                                    <label>Title</label>
                                    <input type="text" class="draggableForAll" name="title" value="{{ old('title') }}">
                                </li>

                                <li>
                                    <label>Description</label>
                                    <input type="text" class="draggableForAll" name="description" value="{{ old('description') }}">
                                </li>

                                <hr>

                                <li>
                                    <label>Keywords</label>
                                    <textarea name="keywords" class="draggableForAll">{{ old('keywords') }}</textarea>
                                </li>

                                <li>
                                    <label>Var 1</label>
                                    <input name="var1" class="draggableForAll" value="{{ old('var1') }}"/>
                                </li>

                                <li>
                                    <label>Var 2</label>
                                    <input name="var2" class="draggableForAll" value="{{ old('var2') }}"/>
                                </li>

                                <li>
                                    <label>Var 3</label>
                                    <input name="var3" class="draggableForAll" value="{{ old('var3') }}"/>
                                </li>

                                <li>
                                    <label>Var 4</label>
                                    <input name="var4" class="draggableForAll" value="{{ old('var4') }}"/>
                                </li>

                                <li>
                                    <label>Var 5</label>
                                    <input name="var5" class="draggableForAll" value="{{ old('var5') }}"/>
                                </li>

                                <li>
                                    <label>Var 6</label>
                                    <input name="var6" class="draggableForAll" value="{{ old('var6') }}"/>
                                </li>

                                <li>
                                    <label>Var 7</label>
                                    <input name="var7" class="draggableForAll" value="{{ old('var7') }}"/>
                                </li>

                                <li>
                                    <label>Var 8</label>
                                    <input name="var8" class="draggableForAll" value="{{ old('var8') }}"/>
                                </li>

                                <li>
                                    <label>Var 9</label>
                                    <input name="var9" class="draggableForAll" value="{{ old('var9') }}"/>
                                </li>

                                <li>
                                    <label>Var 10</label>
                                    <input name="var10" class="draggableForAll" value="{{ old('var10') }}"/>
                                </li>

                                <li>
                                    <label>Var 11</label>
                                    <input name="var11" class="draggableForAll" value="{{ old('var11') }}"/>
                                </li>

                                <li>
                                    <label>Var 12</label>
                                    <input name="var12" class="draggableForAll" value="{{ old('var12') }}"/>
                                </li>

                                <li>
                                    <label>Var 13</label>
                                    <input name="var13" class="draggableForAll" value="{{ old('var13') }}"/>
                                </li>

                                <li>
                                    <label>Var 14</label>
                                    <input name="var14" class="draggableForAll" value="{{ old('var14') }}"/>
                                </li>

                                <li>
                                    <label>Var 15</label>
                                    <input name="var15" class="draggableForAll" value="{{ old('var15') }}"/>
                                </li>


                            </ul>

                            <input type="button" name="checkAutometasCategory" value="{{trans('variables.save_it')}}">
                        </div>

                        <div class="part right-part">
                            <h5>Статика</h5>
                            <hr>
                            <div class="container dragDropElements">
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;prodName&#125;&#125;">
                                имя продукта - &#123;&#123;prodName&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;catName&#125;&#125;">
                                имя категории - &#123;&#123;catName&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;1&#125;&#125;">
                                var1 - &#123;&#123;var1&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;2&#125;&#125;">
                                var2 - &#123;&#123;var2&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;3&#125;&#125;">
                                var3 - &#123;&#123;var3&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;4&#125;&#125;">
                                var4 - &#123;&#123;var4&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;5&#125;&#125;">
                                var5 - &#123;&#123;var5&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;6&#125;&#125;">
                                var6 - &#123;&#123;var6&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;7&#125;&#125;">
                                var7 - &#123;&#123;var7&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;8&#125;&#125;">
                                var8 - &#123;&#123;var8&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;9&#125;&#125;">
                                var9 - &#123;&#123;var9&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;10&#125;&#125;">
                                var10 - &#123;&#123;var10&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;11&#125;&#125;">
                                var11 - &#123;&#123;var11&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;12&#125;&#125;">
                                var12 - &#123;&#123;var12&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;13&#125;&#125;">
                                var13 - &#123;&#123;var13&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;14&#125;&#125;">
                                var14 - &#123;&#123;var14&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;15&#125;&#125;">
                                var15 - &#123;&#123;var15&#125;&#125;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="#">
                                separator - #;
                              </div>
                              <div style="background-color: #ccc; padding: 10px 0" data-value="&#123;&#123;&#125;&#125;">
                                {{}}
                              </div>
                            </div>
                        </div>

                        <hr>

                        <div class="part right-part">
                            <h5>Categorii</h5>
                            <hr>
                            <?php $property = 0; ?>
                            <div class="wrapperCategory">
                              @include('admin::admin.autometas.categoriesTreeNoMeta')
                            </div>
                        </div>
                    </div>

        </form>
    </div>

<script type="text/javascript">
  $(function() {
    $('.dragDropElements div').draggable({
        revert: true,
        helper: 'clone',
        drag: function() {
            $(".left-part .draggableForAll").css('border-color', 'red');
        },
        stop: function() {
            $(".left-part .draggableForAll").css('border-color', 'rgba(0, 0, 0, 0.15');
        }
    });

    $(".left-part .draggableForAll").droppable({
        drop: function (event, ui) {
            this.value += $(ui.draggable).attr('data-value');
        }
    });

    if($('select[name="autometa_type"]').val() == 1) {
      $('#newAutometaForm input[name="seotext"]').parent().css('display', 'block');
      $('#newAutometaForm input[name="product_description"]').parent().css('display', 'none');
    } else {
      $('#newAutometaForm input[name="seotext"]').parent().css('display', 'none');
      $('#newAutometaForm input[name="product_description"]').parent().css('display', 'block');
    }

    $('select[name="autometa_type"]').change(function(){
        if($(this).val() == 1) {
          $('#newAutometaForm input[name="seotext"]').parent().css('display', 'block');
          $('#newAutometaForm input[name="product_description"]').parent().css('display', 'none');
        } else {
          $('#newAutometaForm input[name="seotext"]').parent().css('display', 'none');
          $('#newAutometaForm input[name="product_description"]').parent().css('display', 'block');
        }
    });

    $('select[name="lang_id"]').change(function(){
        $.ajax({
            url: "/back/autometa/changeCategory",
            type: 'POST',
            data: {_token: $('input[name="_token"]').val(), lang_id: $(this).val(), type: $('select[name="autometa_type"]').val()},
            success: function (data) {
              $('.wrapperCategory').html(data.html);
            }
        });
    });

    $('select[name="autometa_type"]').change(function(){
        $.ajax({
            url: "/back/autometa/changeCategory",
            type: 'POST',
            data: {_token: $('input[name="_token"]').val(), lang_id: $('select[name="lang_id"]').val(), type: $(this).val()},
            success: function (data) {
              $('.wrapperCategory').html(data.html);
            }
        });
    });

    $('#newAutometaForm input[name="checkAutometasCategory"]').click(function(e){
      e.preventDefault();
      let data = {
        _token: $('input[name="_token"]').val(),
        categories_id: $('#newAutometaForm input[name="categories_id"]').val(),
        lang_id: $('select[name="lang_id"]').val(),
        type: $('select[name="autometa_type"]').val()
      }
      $.ajax({
          url: "/back/autometa/checkAutometasCategory",
          type: 'POST',
          data: data,
          success: function (data) {
            if(data != 'false') {
              let catNameArr = [];
              $.each(data, function(index, value){
                  catNameArr.push($('input[value='+value+']').parent().find('span').html());
              });
              if(confirm('Scenario for '+catNameArr+' already exists, do you want to rewrite it?')) {
                $('#newAutometaForm').submit();
              }
            } else {
              $('#newAutometaForm').submit();
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
