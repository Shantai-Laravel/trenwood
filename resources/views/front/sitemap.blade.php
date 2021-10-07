<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <h3>{{trans('front.common')}}</h3>
  @if (count($staticPages) > 0)
      <ul>
          @foreach( $staticPages as $staticPage )
              <li><a href="{{$url.$lang->lang.$staticPage}}">{{$url.$lang->lang.$staticPage}}</a></li>
          @endforeach
      </ul>
  @endif

  <h3>{{trans('front.advice.name')}}</h3>
  @if (count($posts) > 0)
      <ul>
          <li><a href="{{$url.$lang->lang.'/advices'}}">{{$url.$lang->lang.'/advices'}}</a></li>
          @foreach( $posts as $post )
              <li><a href="{{$url.$lang->lang.'/advices/'.$post->translationByLanguage($lang->id)->first()->url}}">{{$url.$lang->lang.'/advices/'.$post->translationByLanguage($lang->id)->first()->url}}</a></li>
          @endforeach
      </ul>
  @endif

  <h3>{{trans('front.brand.brand')}}</h3>
  @if (count($brands) > 0)
      <ul>
          @foreach( $brands as $brand )
              <li><a href="{{$url.$lang->lang.'/brands/'.$brand->alias}}">{{$url.$lang->lang.'/brands/'.$brand->alias}}</a></li>
          @endforeach
      </ul>
  @endif

  <h3>{{trans('front.promo.promo')}}</h3>
  @if (count($promotions) > 0)
      <ul>
          @foreach( $promotions as $promotion )
              <li><a href="{{$url.$lang->lang.'/promotions/'.$promotion->alias}}">{{$url.$lang->lang.'/promotions/'.$promotion->alias}}</a></li>
          @endforeach
      </ul>
  @endif

  <h3>{{trans('front.category.catalog')}}</h3>
  @if (count($categories) > 0)
      <ul>
          @foreach( $categories as $category )
              <li><a href="{{$url.$lang->lang.'/catalog/'.$category->alias}}">{{$url.$lang->lang.'/catalog/'.$category->alias}}</a></li>

              <?php $firstSubCategories = getCategories($category->id, $lang->id); ?>

              @if (count($firstSubCategories) > 0)
                  <ul>
                      @foreach( $firstSubCategories as $firstSubCategory )
                          <li><a href="{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias}}">{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias}}</a></li>

                          <?php $secondSubCategories = getCategories($firstSubCategory->product_category_id, $lang->id); ?>

                          @if (count($secondSubCategories) > 0)
                              <ul>
                                  @foreach ($secondSubCategories as $secondSubCategory)
                                      <li><a href="{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$secondSubCategory->alias}}">{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$secondSubCategory->alias}}</a></li>

                                      <?php $products = getProductsByCategory($secondSubCategory->product_category_id, $lang->id); ?>

                                      @if (count($products) > 0)
                                          <ul>
                                              @foreach( $products as $product )
                                                  <li><a href="{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$secondSubCategory->alias.'/'.$product->alias}}">{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$secondSubCategory->alias.'/'.$product->alias}}</a></li>
                                              @endforeach
                                          </ul>
                                      @endif

                                  @endforeach
                              </ul>
                          @else
                              <?php $products = getProductsByCategory($firstSubCategory->product_category_id, $lang->id); ?>

                              @if (count($products) > 0)
                                  <ul>
                                      @foreach( $products as $product )
                                          <li><a href="{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$product->alias}}">{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$product->alias}}</a></li>
                                      @endforeach
                                  </ul>
                              @endif
                          @endif
                      @endforeach
                  </ul>
              @else
                  <?php $products = getProductsByCategory($category->id, $lang->id); ?>

                  @if (count($products) > 0)
                      <ul>
                          @foreach( $products as $product )
                              <li><a href="{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$product->alias}}">{{$url.$lang->lang.'/catalog/'.$category->alias.'/'.$product->alias}}</a></li>
                          @endforeach
                      </ul>
                  @endif
              @endif
          @endforeach
      </ul>
  @endif
</body>
</html>
