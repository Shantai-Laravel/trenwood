// Ajax queries
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    }
});

$('.main-wrapper').scroll(function() {
    if ($(this).scrollTop() > 135) {
        $('.page-actions').addClass('fixed');
    } else {
        $('.page-actions').removeClass('fixed');
    }
});


$(document).on('keydown', 'input', function(event){
    $(this).parents('.item-row').addClass('changed');
});

$(document).on('change', 'select', function(event){
    $(this).parents('.item-row').addClass('changed');
});

$('.save-upload').on('click', function(event){
    $btnOption = $(this).data();

    $('.item-row').each(function(key, value){
        if ($('.item-row').eq(key).hasClass('changed')) {

            $obj = $('.item-row').eq(key);
            var id = $obj.attr('data-id');
            var catID = $('.cat-id').val();

            $name = $('.item-row').eq(key).find('.input-name');
            var dataName = {};
            $name.each(function(k, v){
                lang = $name.eq(k).attr('data-lang');
                var val = $name.eq(k).val();
                dataName[lang] = val;
            });

            $body = $('.item-row').eq(key).find('.input-body');
            var dataBody = {};
            $body.each(function(k, v){
                lang = $body.eq(k).attr('data-lang');
                var val = $body.eq(k).val();
                dataBody[lang] = val;
            });

            $props = $('.item-row').eq(key).find('.prop-input');
            var dataProp = {};
            $props.each(function(k, v){
                var val = $props.eq(k).val();
                var data = $props.eq(k).attr('data-id');
                dataProp[data] = val;
            });

            $brand = $('.item-row').eq(key).find('.input-brand_id').val();
            $promo = $('.item-row').eq(key).find('.input-promo_id').val();
            $price = $('.item-row').eq(key).find('.input-price').val();
            $discount = $('.item-row').eq(key).find('.input-discount').val();
            $brand = $('.item-row').eq(key).find('.input-brand_id').val();
            $promo = $('.item-row').eq(key).find('.input-promo_id').val();

            $.ajax({
                type: "POST",
                url: '/back/save-products',
                data: {
                    id: id,
                    catID : catID,
                    name: JSON.stringify(dataName),
                    body: JSON.stringify(dataBody),
                    brand: $brand,
                    promo: $promo,
                    price: $price,
                    discount: $discount,
                    brand: $brand,
                    promo: $promo,
                    props: JSON.stringify(dataProp),
                },
                beforeSend: function(){
                    $('#loading-image').show();
                },
                complete: function(){
                   $('#loading-image').hide();
                },
                success: function(data) {
                    if (data != 'false') {
                        if (typeof id == 'undefined') {
                            $('.ajax-response').html(data);
                            if ($btnOption == "redirect-cat") {
                                $('.category-select').val();
                            }
                        }
                    }
                }
            });
        }

    });

    $('.item-row').removeClass('changed');
    $id = $(this).attr('data-id');

})


$(document).on('click', '.save-images-btn', function(e){
        e.preventDefault();
        var formData = new FormData($(this).parents('form')[0]);
        var id = $(this).attr('data');

        $.ajax({
            url: '/back/upload-files',
            type: 'POST',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            beforeSend: function(){
                $('#loading-image').show();
            },
            complete: function(){
               $('#loading-image').hide();
            },
            success: function (data) {
                $('.images-live-update' + id).html(data);
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
});


// $(document).on('click', '.save-images-btn', function(e){
//
//     e.preventdefault;
//
//     // var id = $('#id').val();
//     var image = $('#upload_file')[0].files[0];
//     new form = new FormData();
//     form.append('id', id);
//     form.append('image', image);
//     $.ajax({
//         url: 'upload',
//         data: form,
//         cache: false,
//         contentType: false,
//         processData: false,
//         type: 'POST',
//         success:function(response) {
//             alert(response);
//         }
//     });


    // var form_data = new FormData();
    // var ins = document.getElementById('upload_file').files.length;
    //
    // for (var x = 0; x < ins; x++) {
    //     form_data.append("files[]", document.getElementById('upload_file').files[x]);
    // }
    //
    // $.ajax({
    //     type: "POST",
    //     url: '/back/upload-files',
    //     xhr: function() {
    //             var myXhr = $.ajaxSettings.xhr();
    //             return myXhr;
    //         },
    //     data: {
    //         // id: id,
    //         form_data : form_data,
    //     },
    //     success: function(data) {
    //
    //     }
    // });
    //
    // return false;

// });

window.onbeforeunload = function() {
   return "Do you really want to leave our brilliant application?";
   //if we return nothing here (just calling return;) then there will be no pop-up question at all
   //return;
};
