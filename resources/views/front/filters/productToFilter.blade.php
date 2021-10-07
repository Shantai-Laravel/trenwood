@php
$i = 0;
$s = 0;
$productsArr = [];
if (!$products->isEmpty()) {
    foreach ($products as $key => $value) {
        if (($key + 1) % 3 == 1) {
            $i = 0;
            $s++;
        }
        $i++;
        $productsArr[$s][$i] = $value;
    }
}
@endphp

@php $i = 0; @endphp
@if (count($productsArr) > 0)
    @foreach ($productsArr as $key => $productArr)
        @if ($key % 2 == 1)
            @php
                $i = 0;
            @endphp
        @endif

        @php $i++; @endphp

        @include('front.productTemplates.template'.$i, ['products' => $productArr])
    @endforeach
@endif

<div class="load-more-area"></div>

@if ($products->nextPageUrl())
    <div class="row justify-content-center">
        <div class="col-auto">
            @php
                $link = str_replace ("?&", '?', $url.'&page='. ($products->currentPage() + 1));
            @endphp
            <a href="#" class="load-more-btn buttSilver" data-url="{{ url($link) }}">{{trans('front.products.loadMore')}}</a>
        </div>
    </div>
@endif
