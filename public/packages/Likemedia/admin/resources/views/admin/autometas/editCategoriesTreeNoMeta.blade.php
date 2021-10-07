<ul class="cats-tree">
<?php
     $lang = $lang_id;
?>
@if (count(SelectProdsCatsTree($lang, 0)) > 0)
@foreach (SelectProdsCatsTree($lang, 0) as $key => $category)
<li>
    <ul>
        @if (count(SelectProdsCatsTree($lang, $category->id)) > 0)
        <li>
            <div class="form-group">
                <label>
                    <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkAutometasCategoryEdit($category->id, $lang, $type, $meta_id) ? 'checked' : ''}}>
                    <span>{{ $category->name }}</span>
                </label>
            </div>
            <ul>
                @foreach (SelectProdsCatsTree($lang, $category->product_category_id) as $key => $category)
                @if (count(SelectProdsCatsTree($lang, $category->id)) > 0)
                <li>
                    <div class="form-group">
                        <label>
                            <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkAutometasCategoryEdit($category->id, $lang, $type, $meta_id) ? 'checked' : ''}}>
                            <span>{{ $category->name }}</span>
                        </label>
                    </div>
                    <ul>
                        @foreach (SelectProdsCatsTree($lang, $category->product_category_id) as $key => $category)
                        <li>
                            <div class="form-group">
                                <label>
                                    <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkAutometasCategoryEdit($category->id, $lang, $type, $meta_id) ? 'checked' : ''}}>
                                    <span>{{ $category->name }}</span>
                                </label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @else
                <li>
                    <div class="form-group">
                        <label>
                            <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkAutometasCategoryEdit($category->id, $lang, $type, $meta_id) ? 'checked' : ''}}>
                            <span>{{ $category->name }}</span>
                        </label>
                    </div>
                </li>
                @endif
                @endforeach
            </ul>
        </li>
        @else
        <li>
            <div class="form-group">
                <label>
                    <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkAutometasCategoryEdit($category->id, $lang, $type, $meta_id) ? 'checked' : ''}}>
                    <span>{{ $category->name }}</span>
                </label>
            </div>
        </li>
        @endif
    </ul>
</li>
@endforeach
@endif
</ul>

<script>
$(function() {
    let arr = [];

    $('input[type=checkbox]').each(function () {
      if($(this).prop('checked'))
          arr.push($(this).val());
      $('#category_id').val(arr);
    });


    $("input[type='checkbox']").change(function () {
        $(this).parent().parent('.form-group').siblings('ul')
               .find("input[type='checkbox']")
               .prop('checked', this.checked);
         $('input[type=checkbox]').each(function () {
             if($(this).prop('checked')) {
               if(!arr.includes($(this).val())) {
                 arr.push($(this).val());
               }
             } else {
               let index = arr.indexOf($(this).val());
               if (index !== -1) arr.splice(index, 1);
             }
         });
       $('#category_id').val(arr);
    });
});
</script>
