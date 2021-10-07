<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="warning" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Elementul <b class="category-name"></b> este de ultimul nivel</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    Elementul <b class="category-name"></b> este de ultimul nivel
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="moveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Category <b class="category-name"></b> is not empty</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    Atentie! Toate articolele din <b class="category-name"></b> vor fi mutate in categoria creata
                </div>
                <form action="{{ route('menus.move.posts_') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="parent_id" class="parent_id" value="0"/>
                    <input type="hidden" name="child_id" class="child_id" value="0"/>
                    <div class="list-content">
                        @include('admin::admin.alerts')
                        <div class="part full-part">
                            <ul>
                                <li>
                                    <label>Toate articolele din categoria <b class="category-name"></b> vor fi mutate in subcategoria nou creata. Daca doriti sa le mutati in alta categorie, alegeti mai jos:</label>
                                    @if (!empty($menus))
                                    <select class="form-control" name="add">
                                        @foreach($menus as $category)
                                        <option value="{{ $category->id }}">{{ !is_null($category->translation()->first()) ?  $category->translation()->first()->name : '' }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </li>
                                <li>
                                    <input style="margin-top: 10px;" type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}">
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addCategory" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Adauga un element menu</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('menus.partial.save') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="parent_id" id="parent_id" value="0"/>
                    <div class="list-content">
                        <div class="tab-area">
                            @include('admin::admin.alerts')
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                @if (!empty($langs))
                                @foreach ($langs as $key => $lang)
                                <li class="nav-item">
                                    <a href="#{{ $lang->lang }}"
                                        class="nav-link  {{ $key == 0 ? ' open active' : '' }}"
                                        data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                        @foreach ($langs as $lang)
                        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}"
                            id={{ $lang->
                            lang }}>
                            <div class="part full-part">
                                <ul>
                                    <li>
                                        <label>{{trans('variables.title_table')}}</label>
                                        <input type="text" name="name_{{ $lang->lang }}"
                                            class="name form-control"
                                            data-lang="{{ $lang->lang }}">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                        <div class="">
                            <ul>
                                <li>
                                    <label>Link</label>
                                    <select class="form-control categorySelect" name="link" >
                                        @if (!empty($pages))
                                        <optgroup label="Pagini Statice">
                                            @foreach ($pages as $key => $page)
                                            <option value="/page/{{ $page->translation()->first()->slug }}">{{ !is_null($page->translation()->first()) ? $page->translation()->first()->title : '' }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endif
                                        @if (!empty($categories))
                                        <optgroup label="Categorii de articole">
                                            @foreach ($categories as $key => $category)
                                            <option data="category" data-id="{{ $category->id }}" value="{{ !is_null($category->translation()->first()) ? $category->translation()->first()->name : '' }}">{{ $category->translation()->first()->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endif
                                        @if (!empty($productCategories))
                                        <optgroup label="Categorii de produse">
                                            @foreach ($productCategories as $key => $category)
                                            <option data="category" data-id="{{ $category->id }}" value="{{ !is_null($category->translation()->first()) ? $category->translation()->first()->name : '' }}">{{ $category->translation()->first()->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endif
                                        <option data="link" value="link">Link extern</option>
                                        {{-- @endif --}}
                                    </select>
                                </li>
                                <li class="subcategories hide">
                                    <br>
                                    <input type="checkbox" name="subcategories" id="subcats{{ $lang->lang }}">
                                    <label for="subcats{{ $lang->lang }}">Bifati pentru a adauga si toate subcategoriile?</label>
                                </li>
                                <li class="ex-link hide">
                                    <label>Link extern</label>
                                    <input type="text" name="link" class="name form-control">
                                </li>
                                <li>
                                    <input style="margin-top: 10px;" type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}">
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="type" value="page" id="atributionType">
                        <input type="hidden" name="categoryId" value="" id="categoryId">
                        <input type="hidden" name="groupId" value="{{ Request::segment(4) }}">
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- move articles on delete Modal -->
<div class="modal fade bd-example-modal-lg" id="addCategory_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Delete menu</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    Doriti sa stergeti elementul menu?
                </div>
                <form action="{{ route('menus.destroy', 0) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field("DELETE") }}
                    <input type="hidden" name="parent_id" class="parent_id" value="0"/>
                    <input type="checkbox" name="with-children" id="with_children">
                    <label for="with-children">Doriti sa stergeti elementul cu elementele child?</label>
                    <div class="list-content">
                        @include('admin::admin.alerts')
                        <div class="part full-part">
                            <ul>
                                <li>
                                    <input style="margin-top: 10px;" type="submit" class="btn btn-primary" value="sterge">
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('.categorySelect').on('change', function(){
        var value = $( ".categorySelect option:selected").attr('data');
        var id = $( ".categorySelect option:selected").attr('data-id');
        if(value == 'category'){
            $('.subcategories').removeClass('hide');
            $('#atributionType').val('category');
            $('#categoryId').val(id);
        }else if(value == 'link'){
            $('.ex-link').removeClass('hide');
        }else{
            $('.subcategories').addClass('hide');
            $('.subcategories').children('input').prop("checked", false);
            $('#atributionType').val('page');
        }
    });
</script>
