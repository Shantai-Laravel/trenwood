// Ajax queries
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    }
});

let lang = $('html').attr('lang');

$(document).on('click', '.changeSubProductSize', function(e) {
  const subproductId = $(this).data('subproduct_id');
  $.ajax({
      type: "POST",
      url: '/'+ lang +'/changeSubProduct',
      data: { subproductId },
      success: data => {
          const res = JSON.parse(data);
          $('.subproducts').html(res.subproducts);
          selectBoxes();
      },
      error: err => console.log(err)
  });

});

$(document).on('click', '.changeSubProductOneItemSize', function(e) {
  const subproductId = $(this).data('subproduct_id');

  $.ajax({
      type: "POST",
      url: '/'+ lang +'/changeSubProductOneItem',
      data: {
          subproductId
      },
      success: data => {
          const res = JSON.parse(data);
          $(this).closest('.changeSubProduct').find('.subproduct').html(res.changedSubproduct);
          selectBoxes();
          $(this).parent().addClass('animated fadeOut faster');
          setTimeout(() => {
            $(this).parent().removeClass('animated fadeOut faster');
            $(this).parent().hide();
          }, 500);
      },
      error: err => console.log(err)
  });
});

$(document).on('click', '.addToWish', function(e){
    const productId = $(this).data('product_id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/addToWishList',
        data: { productId },
        success: data => {
            const res = JSON.parse(data);
            $('.wishListBox').html(res.wishListBox);
            $('.wishListCount').html(res.wishListCount);
            $('.wishListCountMob').html(res.wishListCountMob);
            $(this).toggleClass('addedWishList');
        },
        error: err => console.log(err)
    });

    e.preventDefault();
});

$(document).on('click', '.addSetToWish', function(e){
    e.preventDefault();
    const setId = $(this).data('set_id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/addSetToWishList',
        data: { setId },
        success: data => {
            const res = JSON.parse(data);
            $('.wishListBox').html(res.wishListBox);
            $('.wishListCount').html(res.wishListCount);
            $('.wishListCountMob').html(res.wishListCountMob);
            $(this).toggleClass('addedWishList');
        },
        error: err => console.log(err)
    });
});

$(document).on('click', '.removeItemWishList', function(e){
    const id = $(this).data('id');
    const productId = $(this).data('product_id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/removeItemWishList',
        data: { id: id },
        success: data => {
            const res = JSON.parse(data);
            $('.wishListBlock').html(res.wishListBlock);
            $('.wishListBox').html(res.wishListBox);
            $('.wishListCount').html(res.wishListCount);
            $('.wishListCountMob').html(res.wishListCountMob);
            $('.addToWish[data-product_id='+productId+']').removeClass('addedWishList');
        },
        error: err => console.log(err)
    });

    e.preventDefault();
});

$(document).on('click', '.removeSetWishList', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    const setId = $(this).data('set_id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/removeSetWishList',
        data: { id: id },
        success: data => {
            const res = JSON.parse(data);
            $('.wishListBlock').html(res.wishListBlock);
            $('.wishListBox').html(res.wishListBox);
            $('.wishListCount').html(res.wishListCount);
            $('.wishListCountMob').html(res.wishListCountMob);
            $('.addSetToWish[data-set_id='+setId+']').removeClass('addedWishList');
        },
        error: err => console.log(err)
    });
});

$(document).on('change', 'select[name="subproductSize"]', function(e) {
    const subproductId = $(this).val();
    const wishListId = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/changeSubproductSizeWishList',
        data: {subproductId, wishListId},
        success: data => {
            const res = JSON.parse(data);
            $('.wishListBox').html(res.wishListBox);
            $(this).closest('.wishProduct').find('.txtWish').css('display', 'block');
            $(this).closest('.wishProduct').find('.stock').html(res.subproduct.stock);
            $(this).closest('.wishProduct').find('.code').html(res.subproduct.code);
        }
    });

    e.preventDefault();
});

$(document).on('click', '.moveFromWishListToCart', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/moveFromWishListToCart',
        data: { id },
        success: data => {
            const res = JSON.parse(data);
            $('.wishListBlock').html(res.wishListBlock);
            $('.wishListBox').html(res.wishListBox);
            $('.wishListCount').html(res.wishListCount);
            $('.wishListCountMob').html(res.wishListCountMob);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
        },
        error: err => {
          $(`select[name="subproductSize"][data-id=${id}]`).addClass('animated heartBeat');
          setTimeout(() => {
            $(`select[name="subproductSize"][data-id=${id}]`).removeClass('animated heartBeat');
          }, 500);
        }
    });
});

$(document).on('click', '.moveSetFromWishListToCart', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    const item = $(this).parents('.wishItemSet').find('select[name="subproductSize"]');
    item.removeClass('animated heartBeat');

    $.ajax({
        type: "POST",
        url: '/'+ lang +'/moveSetFromWishListToCart',
        data: { id },
        success: data => {
            const res = JSON.parse(data);
            $('.wishListBlock').html(res.wishListBlock);
            $('.wishListBox').html(res.wishListBox);
            $('.wishListCount').html(res.wishListCount);
            $('.wishListCountMob').html(res.wishListCountMob);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
        },
        error: err => {
          for(var i = 0; i < item.length; i++){
            if($(item[i]).find("option").prop('selected')){
              $(item[i]).addClass('animated heartBeat');
            }
          }
        }
    });
});

$(document).on('click', '.addToCart', function(e){
    const product_id = $(this).data('product_id');
    let subproduct_id = $(this).closest('.changeSubProduct').find('.sect.checked').data('subproduct_id');
    console.log(typeof subproduct_id === "undefined");

    if (typeof subproduct_id === "undefined") {
        subproduct_id = $(this).parents('.onHover').find('.checked').data('subproduct_id');
    }
    console.log( subproduct_id );

    console.log($(this).parents('.onHover').find('.checked').data('subproduct_id'));

    $.ajax({
        type: "POST",
        url: '/'+ lang +'/addToCart',
        data: { product_id, subproduct_id },
        success: data => {
            const res = JSON.parse(data);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            $('.cartPop').toggle(500);
            $('.cartPop').html(res.cartQuick);

            setTimeout(function(){
                $('.cartPop').toggle(500);
            }, 2000);
        },
        error: err => {
          const item = $(this).parent().parent().find('.parentRelative');
          item.addClass('animated heartBeat');
          setTimeout(() => {
            item.removeClass('animated heartBeat');
          }, 500);
        }
    });

    e.preventDefault();
});

$(document).on('click', '.addSetToCart', function(e){
    const subproductsId = $(this).data('subproducts_id');
    const setId = $(this).data('set_id');
    const item = $(this).parent().parent().find('.parentRelative');
    item.removeClass('animated heartBeat');

    $.ajax({
        type: "POST",
        url: '/'+ lang +'/addSetToCart',
        data: { subproductsId, setId },
        success: data => {
            const res = JSON.parse(data);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            // checkSet(res.cartQuick, res.subproducts);
            $('.cartPop').toggle(500);
            $('.cartPop').html(res.cartQuick);

            setTimeout(function(){
                $('.cartPop').toggle(500);
            }, 2000);
        },
        error: err => {
          for(var i = 0; i < item.length; i++){
            if(!$(item[i]).find('.sect').hasClass('checked')){
              $(item[i]).addClass('animated heartBeat');
            }
          }
        }
    });

    e.preventDefault();
});

$(document).on('click', '.moveFromCartToWishList', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/moveFromCartToWishList',
        data: { id },
        success: data => {
            const res = JSON.parse(data);
            $('.cartBox').html(res.cartBox);
            $('.cartBlock').html(res.cartBlock);
            $('.cartBlockMob').html(res.cartBlockMob);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            $('.cartSummary').html(res.cartSummary);
            $('.promo').html(res.promo);
            $('.wishListBox').html(res.wishListBox);
            $('.wishListCount').html(res.wishListCount);
            $('.wishListCountMob').html(res.wishListCountMob);
        },
        error: err => {
          console.log(err);
        }
    });
});

$(document).on('click', '.moveSetFromCartToWishList', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/moveSetFromCartToWishList',
        data: { id },
        success: data => {
            const res = JSON.parse(data);
            $('.cartBox').html(res.cartBox);
            $('.cartBlock').html(res.cartBlock);
            $('.cartBlockMob').html(res.cartBlockMob);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            $('.cartSummary').html(res.cartSummary);
            $('.promo').html(res.promo);
            $('.wishListBox').html(res.wishListBox);
            $('.wishListCount').html(res.wishListCount);
            $('.wishListCountMob').html(res.wishListCountMob);
        },
        error: err => {
          console.log(err);
        }
    });
});

$(document).on('click', '.removeItemCart', function(event){
    event.preventDefault();
    const id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/removeItemCart',
        data: {id},
        success: data => {
            const res = JSON.parse(data);
            $('.cartBlock').html(res.cartBlock);
            $('.cartBlockMob').html(res.cartBlockMob);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            $('.cartSummary').html(res.cartSummary);
            $('.promo').html(res.promo);
        }
    });
});

$(document).on('click', '.removeSetCart', function(event){
    event.preventDefault();
    const id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/removeSetCart',
        data: {id},
        success: data => {
            const res = JSON.parse(data);
            $('.cartBlock').html(res.cartBlock);
            $('.cartBlockMob').html(res.cartBlockMob);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            $('.cartSummary').html(res.cartSummary);
            $('.promo').html(res.promo);
        }
    });
});

$(document).on('change', '.changeQty', function(event){
    event.preventDefault();
    const id = $(this).attr('data-id');
    const value = $(this).val();

    $.ajax({
        type: "POST",
        url: '/'+ lang +'/cartQty/changeQty',
        data: {
            id,
            value,
        },
        success: function(data) {
            const res = JSON.parse(data);
            $('.cartBlock').html(res.cartBlock);
            $('.cartBlockMob').html(res.cartBlockMob);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            $('.cartSummary').html(res.cartSummary);
            $('.promo').html(res.promo);
        }
    });
});

$(document).on('change', '.changeQtySet', function(event){
    const id = $(this).attr('data-id');
    const value = $(this).val();

    $.ajax({
        type: "POST",
        url: '/'+ lang +'/cartQty/changeQtySet',
        data: {
            id,
            value,
        },
        success: data => {
            const res = JSON.parse(data);
            $('.cartBlock').html(res.cartBlock);
            $('.cartBlockMob').html(res.cartBlockMob);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            $('.cartSummary').html(res.cartSummary);
            $('.promo').html(res.promo);
        }
    });
});

$(document).on('click', '.promocodeAction', function(event){
    const promocode = $('.codPromo').val();
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/cart/set/promocode',
        data: { promocode },
        success: data => {
            const res = JSON.parse(data);
            if (data == 'false') {
                $('.invalid-feedback').text('promo code is not valid');
            }
            $('.cartBlock').html(res.cartBlock);
            $('.cartBlockMob').html(res.cartBlockMob);
            $('.cartBox').html(res.cartBox);
            $('.cartCount').html(res.cartCount);
            $('.cartCountMob').html(res.cartCountMob);
            $('.cartSummary').html(res.cartSummary);
            $('.promo').html(res.promo);
        }
    });
});

$(document).on('change', '.filter-checkbox-category', function(event){
    $name = $(this).attr('name');
    $value = $(this).attr('value');
    $category = $('.category-id').val();
    $url = window.location.pathname;

    $.ajax({
        type: "POST",
        url: '/'+ lang +'/filter',
        data:{
            name: $name, value: $value, category_id: $category, url: $url
        },
        beforeSend: function(){
            $('#loading-image').show();
        },
        complete: function(){
           $('#loading-image').hide();
        },
        success: function(data) {
            var res = JSON.parse(data);
            $('.responseProducts').html(res.products);
            $('.filter-bind').html(res.filter);

            window.history.pushState({}, "Title", "?"+res.url);
        }
    });
})

$(document).on('change', '.filter-checkbox-property', function(event){
    // alert('vd');
    $name = $(this).attr('name');
    $value = $(this).attr('value');
    $category = $('.category-id').val();
    $url = window.location.pathname;

    $.ajax({
        type: "POST",
        url: '/'+ lang +'/filter/property',
        data:{
            name: $name, value: $value, category_id: $category, url: $url
        },
        beforeSend: function(){
            $('#loading-image').show();
        },
        complete: function(){
           $('#loading-image').hide();
        },
        success: function(data) {
            var res = JSON.parse(data);
            $('.responseProducts').html(res.products);
            $('.filter-bind').html(res.filter);

            window.history.pushState({}, "Title", "?"+res.url);
        }
    });
})

$(document).on('click', '#sendPrice', function(event){
    $from = $('#curent-price-from').val();
    $to = $('#curent-price-to').val();
    $category = $('.category-id').val();
    $url = window.location.pathname;

    $.ajax({
        type: "POST",
        url: '/'+ lang +'/filter/price',
        data:{
            from: $from, to: $to, category_id: $category, url: $url
        },
        beforeSend: function(){
            $('#loading-image').show();
        },
        complete: function(){
           $('#loading-image').hide();
        },
        success: function(data) {
            var res = JSON.parse(data);
            $('.responseProducts').html(res.products);
            $('.filter-bind').html(res.filter);

            window.history.pushState({}, "Title", "?"+res.url);
        }
    });
})

$(document).on('change', '.order-products', function(event){
    // $(this) = $(this).find('option:selected');
    $order = $(this).val();
    $field = $(this).attr('data');
    $category = $('.category-id').val();
    $url = window.location.pathname;
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/filter/order',
        data:{
            order: $order, field: $field, category_id: $category, url: $url
        },
        beforeSend: function(){
            $('#loading-image').show();
        },
        complete: function(){
           $('#loading-image').hide();
        },
        success: function(data) {
            var res = JSON.parse(data);
            $('.responseProducts').html(res.products);
            $('.filter-bind').html(res.filter);

            window.history.pushState({}, "Title", "?"+res.url);
        }
    });
    return false;
});

$(document).on('click', '.load-more-btn', function(e){
  e.preventDefault();
  var url = $(this).attr('data-url');
  $.ajax({
      type: "GET",
      url: url,
      data: {},
      success: function(data) {
          var res = JSON.parse(data);
          $('.load-more-area').append(res.html);
          $('.load-more-btn').attr('data-url', res.url);

          if (res.last == 'true') {
              $('.load-more-btn').parent().remove();
          }
      }
  });
});

$(document).on('change', '.filterCountries', function(){
    let value = $(this).val();
    let address_id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/cabinet/filterCountries',
        data: { value },
        success: data => {
            let res = JSON.parse(data);
            if(address_id) {
                $('.filterRegions[data-id=' + address_id +']').html('<option selected disabled>Выберите регион</option>');
                $('.filterRegions[data-id=' + address_id +']').append(res.regions);
            } else {
                $('.filterRegions').html("<option selected disabled>Выберите регион</option>");
                $('.filterRegions').append(res.regions);
            }
        },
        error: err => console.log(err)
    });
});

$(document).on('change', '.filterRegions', function(){
    let value = $(this).val();
    let address_id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/cabinet/filterRegions',
        data: { value },
        success: data => {
            let res = JSON.parse(data);
            if(address_id) {
                $('.filterCities[data-id=' + address_id + ']').html('<option selected disabled>Выберите город</option>');
                $('.filterCities[data-id=' + address_id + ']').append(res.cities);
            } else {
                $('.filterCities').html('<option selected disabled>Выберите город</option>');
                $('.filterCities').append(res.cities);
            }
        },
        error: err => console.log(err)
    });
});

$(document).on('change', '.filterCountriesCart', function(){
    let value = $(this).val();
    let address_id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/filterCountries',
        data: { value: value },
        success: data => {
            let res = JSON.parse(data);
            if(address_id) {
                $('.filterRegionsCart[data-id=' + address_id +']').html('<option selected disabled>Выберите регион</option>');
                $('.filterRegionsCart[data-id=' + address_id +']').append(res.regions);
            } else {
                $('.filterRegionsCart').html("<option selected disabled>Выберите регион</option>");
                $('.filterRegionsCart').append(res.regions);
            }
        },
        error: err => console.log(err)
    });
});

$(document).on('change', '.filterRegionsCart', function(){
    let value = $(this).val();
    let address_id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/filterRegions',
        data: { value },
        success: data => {
            let res = JSON.parse(data);
            if(address_id) {
                $('.filterCitiesCart[data-id=' + address_id + ']').html('<option selected disabled>Выберите город</option>');
                $('.filterCitiesCart[data-id=' + address_id + ']').append(res.cities);
            } else {
                $('.filterCitiesCart').html('<option selected disabled>Выберите город</option>');
                $('.filterCitiesCart').append(res.cities);
            }
        },
        error: err => console.log(err)
    });
});

let changeLang = function(lang) {
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/changeLang',
        data: { lang: lang },
        success: data => console.log(data),
        error: err => console.log(err)
    });
}

$('select[name="addressMain"').on('change', function(){
    $('.addressInfo').hide().children().attr('disabled', true);
    $('.addressInfo[data-id=' + $(this).val() + ']').children().attr('disabled', false).parent().show();
});

$('.addressInfo').hide().children().attr('disabled', true);
$('.addressInfo[data-id=' + $('select[name="addressMain"').val()  + ']').children().attr('disabled', false).parent().show();

function addReturn(clickedCheckbox) {
    if (clickedCheckbox.checked) {
        if (confirm("Are you sure you want to do this?")) {
            clickedCheckbox.checked = true;
            clickedCheckbox.closest('form').submit();
        } else {
            clickedCheckbox.checked = false;
        }
     } else {
        clickedCheckbox.closest('form').submit();
     }
}

$(document).on('change', '.showPickup', function(){
    $('.pickupBlock').slideToggle();
    $('.deliveryBlock').slideToggle();
});

$(document).on('change', '.showDelivery', function(){
    $('.pickupBlock').slideToggle();
    $('.deliveryBlock').slideToggle();
});

$('.search-field').on('keyup', function(){
    const val = $(this).val();

    if (val.length > 2) {
        $.ajax({
            type: "POST",
              url: '/'+ lang +'/search/autocomplete',
            data:{
                value: val,
            },
            success: data => {
                const res = JSON.parse(data);
                $('.searchResult').html(res);
            }
        });
    }else{
        $('.searchResult').html('');
    }
});

$(document).on('click', '.sortByHighPrice', function(event){
    const value = $('.searchInput input[name="value"]').val();
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/search/sort/highPrice',
        data: {value},
        success: data => {
            const res = JSON.parse(data);
            $('.searchBox').html(res.searchResults);
        }
    });
});

$(document).on('click', '.sortByLowPrice', function(event){
    const value = $('.searchInput input[name="value"]').val();
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/search/sort/lowPrice',
        data: {value},
        success: data => {
            const res = JSON.parse(data);
            $('.searchBox').html(res.searchResults);
        }
    });
});

$(document).on('click', '.sortByDesc', function(event){
    const value = $('.searchInput input[name="value"]').val();
    $.ajax({
        type: "POST",
        url: '/'+ lang +'/search/sort/newest',
        data: {value},
        success: data => {
            const res = JSON.parse(data);
            $('.searchBox').html(res.searchResults);
        }
    });
});
