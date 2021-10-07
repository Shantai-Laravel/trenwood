

// Home Slide

$(function() {
  if(screen.width < 768){
    $('homeFoterMobile').toggleClass('homeFoter');
    console.log('asfda')
  }
  $(document).ready(function() {
    var items = $('main').find("section");
    var count = 0;
    var isFirefox = typeof InstallTrigger !== "undefined";
    var sectionHeight = $("section").height();
    var scrollIcon = $(".scroll-downs");
    var mouseScroll;
    var lastY;
    $(window).resize(function() {
      sectionHeight = $("section").outerHeight();
      return sectionHeight;
    });
    $(window).resize(function() {
      if (isFirefox) {
        mouseScroll = "wheel";
        detectBrowser(mouseScroll);
      } else {
        mouseScroll = "wheel";
        detectBrowser(mouseScroll);
      }
    });
    for (var i = 0; i < items.length; i++) {
      $(items[i]).attr("id", i);
    }
    function detectBrowser(x) {
      function scrollSection(event) {
        $(scrollIcon).css("animation-name", "");
        $(scrollIcon).css("opacity", "0");
        setTimeout(function() {
          $(scrollIcon).css("opacity", "1");
          $(scrollIcon).css("animation-name", "opacScroll");
        }, 1000);
        if (event.deltaY > 0) {
          if (count < items.length - 1 && count != items.length - 2) {
            window.removeEventListener(x, scrollSection);
            items[count].style.top = -sectionHeight + "px";
            count++;
            setTimeout(evlist, 1000);
          } else if (count == items.length - 2) {
            window.removeEventListener(x, scrollSection);
            items[count].style.top = 0 + "px";
            items[count + 1].style.height = "50vh";
            count++;
            setTimeout(evlist, 1000);
          }
        } else if (event.deltaY < 0) {
          if (count > 0 && count != items.length - 1) {
            window.removeEventListener(x, scrollSection);
            items[count - 1].style.top = 0 + "px";
            count--;
            setTimeout(evlist, 700);
          } else if (count == items.length - 1) {
            window.removeEventListener(x, scrollSection);
            items[count].style.height = "0";
            count--;
            setTimeout(evlist, 1000);
          }
        }
      }
      function evlist() {
        window.addEventListener(x, scrollSection, { passive: false });
      }
      evlist();
    }

    if (isFirefox) {
      mouseScroll = "wheel";
      detectBrowser(mouseScroll);
    } else {
      mouseScroll = "wheel";
      detectBrowser(mouseScroll);
    }
    window.addEventListener("keyup", function(e) {
      $(scrollIcon).css("animation-name", "");
      $(scrollIcon).css("opacity", "0");
      setTimeout(function() {
        $(scrollIcon).css("opacity", "1");
        $(scrollIcon).css("animation-name", "opacScroll");
      }, 1000);
      if (e.keyCode == 38 || e.keyCode == 37) {
        if (count > 0 && count != items.length - 1) {
          items[count - 1].style.top = 0 + "px";
          count--;
        } else if (count == items.length - 1) {
          items[count].style.height = "0";
          count--;
        }
      } else if (e.keyCode == 40 || e.keyCode == 39) {
        if (count < items.length - 1 && count != items.length - 2) {
          this.console.log("fasd");
          items[count].style.top = -sectionHeight + "px";
          count++;
        } else if (count == items.length - 2) {
          items[count].style.top = 0 + "px";
          items[count + 1].style.height = "50vh";
          count++;
        }
      }
    });
    if (screen.width > 768) {
      $("main").bind("touchstart", function(event) {
        lastY = event.originalEvent.touches[0].clientY;
      });
      $("main").bind("touchend", function(event) {
        var currentY = event.originalEvent.changedTouches[0].clientY;
        $(scrollIcon).css("animation-name", "");
        $(scrollIcon).css("opacity", "0");
        if (currentY > lastY - 30) {
          if (count > 0 && count != items.length - 1) {
            items[count - 1].style.top = 0 + "px";
            count--;
          } else if (count == items.length - 1) {
            items[count].style.height = "0";
            count--;
          }
        } else if (currentY < lastY + 30) {
          if (count < items.length - 1 && count != items.length - 2) {
            items[count].style.top = -sectionHeight + "px";
            count++;
          } else if (count == items.length - 2) {
            items[count].style.top = 0 + "px";
            items[count + 1].style.height = "50vh";
            count++;
          }
        }
        lastY = currentY;
      });
    }
  });
});



if(screen.width > 768){
  $(function() {

    function listImg(){
      var mainImg = $('img.mainImg'),
          controlImg = $('#controlZoomImg')
      for(var i = 0; i<mainImg.length; i++){
          $( controlImg ).append( '<img src="' + $(mainImg[i]).attr('src') + '" alt="" class="mainImg">' );
      }
    }
    listImg();
      var mainImg = $('img.mainImg');
      var zoomImg = $('img.zoomImg');
      var juliaZoom = $('.julia-zoom');
      var bigParent = $('#cover');
      var controlsMassive = $('#controlZoomImg img.mainImg');
      var srcControl = $('#controlZoomImg img.mainImg');
      var srcMainImg = $('img.zoomImg').attr('src');
      mainImg.click(function() {
          zoomImg.attr('src', $(this).attr('src'));
          for(var i = 0; i<controlsMassive.length; i++){

              if($(srcControl[i]).attr('src') === $('img.zoomImg').attr('src')){
                $(srcControl[i]).addClass('activeMainImg');
              }
              else{
                  $(srcControl[i]).removeClass('activeMainImg');
              }
          }
          bigParent.css('display','none');
          juliaZoom.show();
          return false;
      });
      juliaZoom.mousemove(function(e){
          var ham = $(this).find('img.zoomImg').height();
          var vpnHeight = $(document).height();
          var y = -((ham - vpnHeight)/vpnHeight) * e.pageY;

          $(this).css('top', y + "px");
      });
      juliaZoom.click(function(){
          juliaZoom.hide();
           bigParent.css('display','block');
           // location.reload();
           $('.slideItems').slick('setPosition');
           $('html, body').animate({
                  scrollTop: $('#scrollOneItem').offset().top
              }, 0);
      });
  });
}
function selectBoxes(){
  var selectBoxOne = $('.selSizeOpen').children('.sect');
  for(var i = 0; i < selectBoxOne.length; i++){
    if($(selectBoxOne[i]).hasClass('checked')){
      $(selectBoxOne[i]).parents('.parentRelative').children('.selSize').text($(selectBoxOne[i]).children('b.sizeText').text());
    }
  }
}
$().ready(function(){
  $('.plus').on('click', function(){
      man = $(this).prev().prev().val();
      man++;
      $(this).prev().prev().val(man);
  });
  $('.minus').on('click', function(){
      men = $(this).prev().val();
      if(men > 1){
      men--;
      $(this).prev().val(men);
    }
  });
  });
  $(document).ready(function(){
    $(document).on('click', ".namSetButton", function(){
      $(this).parent().parent().parent().parent().children(".detSet").animate({
        height: "toggle"
      });
      $(this).toggleClass('minusSet');
    });
    $(".buttMobile").click(function(){
      $(this).parents('.cartUserSet').children(".setDetMobileOpen").animate({
        height: "toggle"
      });
      $(this).toggleClass('minusSet');
    });
    $(".namSetRetur").click(function(){
      $(this).parents('.oneSetHistory').children(".returSetOpen").animate({
        height: "toggle"
      });
      $(this).toggleClass('minusSet');
    });
  });
  $(document).ready(function(){
    if(screen.width < 992){
      $('.sal').next("ul").hide();
      $(".sal").click(function(){
        $(this).next("ul").animate({
          height: "toggle"
        });
      });
    }
  });

$(document).ready(function(){
  var buttonBurger = $(".burger");
  var buttonSearch = $(".buttonSearch");
  var buttonProfile = $(".buttonLogin");
  var buttonWish = $(".buttonWish");
  var buttonCart = $(".buttonCartHeader");
  var buttonClose = $(".closeMenu");
  var shopNow = $(".shopNow");

  var openBurger = $(".burgerOpen");
  var openBurgerMobile = $(".menuOpenBurgerMobile");
  var openSearch = $(".searchOpen");
  var openProfile = $(".loginOpen");
  var openWish = $(".wishOpen");
  var openCart = $(".cartOpen");
  var openMenu = $(".menuOpen");

  var retNav = $(".retNav");
  $(buttonBurger).click(function(){
    openBurger.toggle(500);
    openBurgerMobile.toggle(500);
  });
  $('.btnFoter').click(function(){
    $('.homeFoter').animate({
      height: "toggle"
    });
    $(this).toggleClass('btnFoterDown');
  });
  $(buttonSearch).click(function(){
    openSearch.toggle(500);
  });
  $(buttonProfile).click(function(){
    openProfile.toggle(500);
  });
  $(document).on('click', '.buttonWish', function(){
    openWish.toggle(500);
  });
  $(document).on('click', '.buttonCartHeader', function(){
    openCart.toggle(500);
  });
  $(buttonClose).click(function(){
    $(this).parent().parent().hide(500);
  });
  $(document).on('click', '.shopNow', function(e){
    e.preventDefault();
    $(this).parents('.menuOpen').toggle(500);
  });
  $(document).mouseup(function (e)
  {
      if (!buttonBurger.is(e.target)
          && !openBurger.is(e.target)
          && openBurger.has(e.target).length === 0)
        {
          openBurger.hide(500);
        }
    });
    $(document).mouseup(function (e)
    {
        if (!buttonBurger.is(e.target)
            && !openBurgerMobile.is(e.target)
            && openBurgerMobile.has(e.target).length === 0)
          {
            openBurgerMobile.hide(500);
          }
      });
    $(document).mouseup(function (e)
    {
        if (!buttonSearch.is(e.target)
            && !openSearch.is(e.target)
            && openSearch.has(e.target).length === 0)
          {
            openSearch.hide(500);
          }
      });
      $(document).mouseup(function (e)
      {
          if (!buttonProfile.is(e.target)
              && !openProfile.is(e.target)
              && openProfile.has(e.target).length === 0)
            {
              openProfile.hide(500);
            }
        });
        $(document).mouseup(function (e)
        {
            if (!buttonCart.is(e.target)
                && !openCart.is(e.target)
                && openCart.has(e.target).length === 0)
              {
                openCart.hide(500);
              }
          });
          $(document).mouseup(function (e)
          {
              if (!buttonWish.is(e.target)
                  && !openWish.is(e.target)
                  && openWish.has(e.target).length === 0)
                {
                  openWish.hide(500);
                }
            });
 });
 $('.foterTitle').click(function(){

   if($(this).hasClass('bcgMinus') !== true){
     $('.foterTitle').removeClass('bcgMinus');
     $(this).addClass('bcgMinus');
   }
   else{
     $(this).removeClass('bcgMinus');
       $('.foterTitle').removeClass('bcgMinus');
   }
 });
window.onload = function(){
  total();
 function total(){

    let ban = document.querySelector('.foter'),
     foterH = document.getElementsByClassName('foterTitle'),
     foterUl = document.getElementsByClassName('foterUl'),
     banda = document.querySelector('.collectionsMobile')

    if(screen.width < 768){
      for(var i = 0; i < foterUl.length; i++)
      {
        foterUl[i].style.display = "none";
      }
        ban.addEventListener('click', function(){
          console.log('fuuck')
        });
        $(document).on('click', ban, foterFunction);
        banda.addEventListener('click', close)
    }
    function close(){
      for(var i = 0; i < foterUl.length; i++)
      foterUl[i].style.display = "none"

    }
    function foterFunction(event){

      var dont = event.target
      console.log(dont)
        if(dont.nodeName == "H6" && dont.nextElementSibling.style.display == "none"){
          close();
          dont.nextElementSibling.style.display = "block";
        }
        else if(dont.nodeName != "H6" ){
          close();
        }
        else{
          dont.nextElementSibling.style.display = "none"
        }
    }

}

}
$(document).ready(function(){
$('.slideItems').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 5000,
  arrows: false,
  dots: true
});
$('.slideMobileCasual').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 5000,
  arrows: true,
  dots: false
});
$('.mainSlide').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 5000,
  arrows: false,
  dots: false,
  asNavFor: '.slideNav'
});
$('.slideNav').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  arrows: true,
  dots: false,
  asNavFor: '.mainSlide',
  centerMode: true,
  focusOnSelect: true
});
});
$(document).ready(function(){
$('.slideColl').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 5000,
  arrows: false,
  dots: true,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        arrows: false,
        slidesToShow: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        slidesToShow: 1
      }
    }
  ]
});
});


$(document).ready(function(){
  var sizeText = ''
  $('.borders').on("click", function(){
    $(this).children('p').toggle();
  });

$(document).on("click", '.selSize', function(){
  var bcgOpen = $(this).next('.selSizeOpen').css("background-color");
  var textOpen = $(this).next('.selSizeOpen').children('span').css("color");
  if($(this).next('.selSizeOpen').css('display') == 'none') {
    $('.selSizeOpen').hide(300);
    $('.parentRelative').css('background-color', 'inherit');
    $(this).parents('.parentRelative').css('background-color', bcgOpen);
    $(this).css('color', textOpen);
    $(this).next('.selSizeOpen').show(300);
  } else {
    $(this).next('.selSizeOpen').hide(300);
    $(this).parents('.parentRelative').css('background-color', 'inherit');
    $(this).css('color', 'inherit');
  }
});
$(document).on("click", '.sect', function(){
  var gab = $(this).parent().children('.sect');
  for(var i=0; i<gab.length; i++){
    gab.removeClass('checked');
  }
  $(this).addClass('checked');
});
$(document).mouseup(function (e) {
    if (
      !$('.selSize').is(e.target)
        &&
      !$('.selSizeOpen').is(e.target)
        && $('.selSizeOpen').has(e.target).length === 0)
      {
        $('.selSizeOpen').hide(300);
        $('.parentRelative').css('background-color', 'inherit');
      }
});
});
$(document).ready(function(){
  $(document).on('click', ".btnFiltr", function(){
    $(".filterOpen").show(300);
  });
  $(document).on('click', ".closeFiltr2, .closeFiltr", function(){
    $(".filterOpen").hide(500);
  });
  $(document).on('click', ".filtrCollection, .filtrCollection2", function(){
    $(".filterOpen").toggle(300);
  });
  $(document).on('click', ".opt", function(){
  $(this).next(".optionFiltrOpen").toggle();
  $(this).toggleClass('submenuBcgMinus');
});
$(".denWishSet").click(function(){
$(this).parent().parent().parent().children(".wishSet").animate({
  height: "toggle"
});
$(this).children('span').toggleClass('submenuBcgMinus');
});
});

var gambit = 10;
var gambit2 = 10;
$(document).on('click', '#btnTopCart', function(){
    if(gambit < 0){
        gambit += 100;
      $(this).parent().next().find('.wishScrollBlock').css('top', gambit + 'px');
    }
});
$(document).on('click', '#btnBottomCart', function(){

  var heightParent = $(this).parent().parent().find('.wishScrollBlock').height();
    var heightChild = $(this).parent().parent().find('.wishScrollBlock').find('.itemCart').outerHeight();
    if(gambit > -(heightParent - heightChild * 3)){
          gambit -= 100;
      $(this).parent().parent().find('.wishScrollBlock').css('top', gambit + 'px');

    }
    console.log(heightChild)
});
$(document).on('click', '#btnTopWish', function(){
    if(gambit2 < 0){
      gambit2 += 100;
      $(this).parent().next().find('.wishScrollBlock').css('top', gambit2 + 'px');
    }
});
$(document).on('click', '#btnBottomWish', function(){
  var heightParent = $(this).parent().parent().find('.wishScrollBlock').height();
    var heightChild = $(this).parent().parent().find('.wishScrollBlock').find('.itemCart').outerHeight();
    if(gambit2 > -(heightParent - heightChild * 3)){
      gambit2 -= 100;
      $(this).parent().parent().find('.wishScrollBlock').css('top', gambit2 + 'px');

    }
});

function checkSet(set, subproducts) {
  let checkStock = false;
  for (let i = 0; i < subproducts.length; i++) {
    const temp = $(`.changeSubProductSize[data-product_id=${subproducts[i].product_id}][data-subproduct_id=${subproducts[i].id}]`);
    if(temp.hasClass('checked')) {
      subproducts[i].stock -= 1;
    }
    if(subproducts[i].stock == 0) {
      checkStock = false;
      temp.removeClass('checked').css('pointer-events', 'none');
      break;
    } else {
      checkStock = true;
    }
  }

}
