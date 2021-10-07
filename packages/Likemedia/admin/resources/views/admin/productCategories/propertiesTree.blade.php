<ul class="cats-tree">
    @if (count($groups))
        @foreach ($groups as $key => $group)
            @if(count((array)$group->properties) > 0)
                <li>
                    <ul>
                    <div class="form-group">
                        <label>
                            <input class="checkbox group-checkbox" type="checkbox" name="goups[]" data-id="{{ $group->translations()->first()->name }}" value="{{ $group->translations()->first()->id }}" {{ checkPropertyCatGroup($menuItem->id, $group->id) ? 'checked' : ''}}>
                            <span class="text-uppercase"> <i class="fa fa-ellipsis-v"></i> {{ $group->translations()->first()->name }}</span>
                        </label>
                    </div>
                    @foreach($group->properties as $key => $property)
                    <ul id="{{ $group->translations()->first()->name }}" class="props-list">
                        <li>
                            <div class="form-group">
                                <label>
                                    <input class="checkbox" type="checkbox" name="properties[]" value="{{ $property->id }}" {{ checkPropertyCat($menuItem->id, $property->id) ? 'checked' : ''}}>
                                    <span>{{ $property->key }}</span>
                                </label>
                            </div>
                        </li>
                    </ul>
                    @endforeach
                </ul>
            </li>
            @endif
        @endforeach
    @else
    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
    @endif
</ul>

<script>
$(function() {
    $("input[type='checkbox']").change(function () {
        console.log('cds');
    $(this).parent().parent('.form-group').siblings('ul')
           .find("input[type='checkbox']")
           .prop('checked', this.checked);
    });
});
</script>
