var body = document.getElementById('body');


if(body.classList.contains('home')){
  //top_menu
  var top_menu = document.getElementById('top_menu');
  if(document.body.clientWidth > 0 && $(window).scroll(function() {
    $(window).scrollTop() >= 50 ? top_menu.classList.remove('transparent_menu') : top_menu.classList.add('transparent_menu');
  }));
}

if(document.body.clientWidth < 1200){
  if(document.getElementById('viber_desctop')){
    document.getElementById('viber_desctop').classList.add('d_none');
    document.getElementById('viber_mobi').classList.remove('d_none');
  }

}

if (document.getElementById('header_carousel')) {
  var header_carousel = $(".header_carousel");
  header_carousel.owlCarousel({
    loop: true,
    nav: false,
    margin: 0,
    padding: 0,
    dots: true,
    responsive: {
      0: {
        items: 1
      }
    }
  });

  $(".header_carousel_left_nav").click(function () {
    header_carousel.trigger("next.owl.carousel");
  });
  $(".header_carousel_right_nav").click(function () {
    header_carousel.trigger("prev.owl.carousel");
  });
}




//left menu

var show_catalog = document.getElementById('show_catalog');
var left_menu = document.getElementById('left_menu');
var close_menu = document.getElementById('close_menu');

show_catalog.addEventListener('click', function () {
  show_left_menu();
});
close_menu.addEventListener('click', function () {
  hide_left_menu();
});

function show_left_menu(){
  setOverlay();
  left_menu.classList.add('show_left_menu');

  $(document).mouseup(function(e) {
    let t = $('#left_menu');
    t.is(e.target) || 0 !== t.has(e.target).length || hide_left_menu();
  })

}
function hide_left_menu(){
  left_menu.classList.remove('show_left_menu');
  removeOverlay();
}

if(document.body.clientWidth < 451){

  let top_menu_icons_list = document.querySelector('.top_menu_icons_list');
  let left_menu_content = document.getElementById('left_menu_content');
  let left_menu_socline = document.getElementById('left_menu_socline');

  let ul = document.createElement('ul');
  ul.innerHTML = top_menu_icons_list.innerHTML;
  ul.classList.add('top_menu_icons_list');


  left_menu_content.insertBefore(ul, left_menu_socline );

  top_menu_icons_list.remove();


}




//popups
if(document.getElementsByClassName('claim')){
  var claim_mass = document.getElementsByClassName('claim');

  for(claim_item of claim_mass){
    claim_item.addEventListener('click', function(){
      show_claim_popup();
    })
  }

  document.getElementById('close_popup').addEventListener('click', function(){
    close_claim_popup();
  })


  var wpcf7Elm = document.getElementsByClassName( 'wpcf7' );

  for(elm of wpcf7Elm){
    elm.addEventListener( 'wpcf7submit', function( event ) {
      // alert(elm.parentNode.querySelector('.alert_done'));
      show_alert_done(elm.parentNode, elm.parentNode.parentNode);
    }, false );
  }

function show_alert_done(parent_elem, parent_parent_elem){
    if(parent_parent_elem.querySelector('.alert_done')){
      parent_elem.classList.add('d_none');
      parent_parent_elem.querySelector('.alert_done').classList.remove('d_none');
    }
  return true;
}

}




function show_claim_popup() {
  setOverlay();
  document.getElementById('claim_popup').classList.remove('d_none');
  $(document).mouseup(function(e) {
    let t = $(".claim_popup");
    t.is(e.target) || 0 !== t.has(e.target).length || close_claim_popup();
  })

  return true;
}

function close_claim_popup(){

  document.getElementById('claim_popup').classList.add('d_none');
  removeOverlay();

  return true;
}

function setOverlay() {
  document.getElementById('overlay').classList.remove('d_none');
  document.getElementById('body').classList.add('ovf_hidden');
  return true;
}



function removeOverlay() {
  document.getElementById('overlay').classList.add('d_none');
  document.getElementById('body').classList.remove('ovf_hidden');
  return true;
}

function setSearchOverlay() {
  document.getElementById('search_overlay').classList.remove('d_none');
  document.getElementById('body').classList.add('search_ovf_hidden');
  return true;
}

function removeSearchOverlay() {
  document.getElementById('search_overlay').classList.add('d_none');
  document.getElementById('body').classList.remove('search_ovf_hidden');
  return true;
}


let $dots = $('.owl-dot');
$dots.attr('aria-label', 'owl carousel');

// product_popup counter

if(document.getElementsByClassName('product_popup')) {
  let checkers_mass = document.getElementsByClassName('checkers');

  for(checker of checkers_mass){
    checker.addEventListener('click',function () {
      router_product_popup_counter(this);
    })
  }

  function router_product_popup_counter(elem){
    if(elem.classList.contains('minus')){
      product_popup_counter_minus(elem);
    }else{
      product_popup_counter_plus(elem);
    }
    return true;
  }

  function product_popup_counter_minus(elem){
    let count_display_value = parseInt(elem.parentNode.querySelector('.count_display').value);
    let closest_parent = elem.closest('.search_product_item');

    if(count_display_value > 1){
      elem.parentNode.querySelector('.count_display').value = count_display_value - 1;
      closest_parent.querySelector('.add_to_cart_button').dataset.quantity = count_display_value - 1;

    }

    return true;
  }


  function product_popup_counter_plus(elem){
    let count_display_value = parseInt(elem.parentNode.querySelector('.count_display').value);
    let closest_parent = elem.closest('.search_product_item');

    elem.parentNode.querySelector('.count_display').value = count_display_value + 1;
    closest_parent.querySelector('.add_to_cart_button').dataset.quantity = count_display_value + 1;
    return true;
  }

}







// show_search_popup
var show_search_popup = document.getElementById('show_search_popup');
var close_search_popup = document.querySelector('.close_search_popup');


show_search_popup.addEventListener('click', function () {
  click_miss_modal();
  showSearchModal();
})

close_search_popup.addEventListener('click', function () {
  hideSearchModal();
})

function showSearchModal() {
  setSearchOverlay();
  body.classList.add('search_modal');
  closeLeftMenuByMissClick();
  return true;
}

function hideSearchModal() {
  body.classList.remove('search_modal');
  removeSearchOverlay();
  return true;
}

function click_miss_modal(){
  $(document).mouseup(function (e) {
    let t = $(".modal_search_wrapper");
    t.is(e.target) || 0 !== t.has(e.target).length || hideSearchModal();
  });
}























//исходники
$(document).ready(function () {
  $("#callback_form").submit(function () {
    $.ajax({
      type: "POST",
      url: "\\wp-content\\themes\\starter_pack\\mails\\callback_mail.php",
      data: $(this).serialize()
    }).done(function () {
      popup_alert_done('callback_popup');
    }).fail(function () {
    });
    return false;
  });
});


// custom dots and navs
if (document.getElementById('dots_list')) {
  var dots_list = document.getElementById('dots_list');
  var dots_mass = dots_list.getElementsByClassName('dots_item');
  var our_service_carousel = $(".our_service_carousel");
  our_service_carousel.owlCarousel({
    loop: false,
    nav: false,
    margin: 20,
    padding: 0,
    dots: false,
    dotsContainer: '#dots_list',
    responsive: {
      0: {
        items: 1
      }
    }
  });
  $('.dots_item').click(function () {
    our_service_carousel.trigger('to.owl.carousel', [$(this).index(), 300]);
  });
  our_service_carousel.on('changed.owl.carousel', function (e) {
    changeActiveDots(e.item.index);
    return true;
  });

  function changeActiveDots(index) {

    for (let i = 0; i < dots_mass.length; i++) {
      dots_mass[i].classList.remove('active');
    }
    dots_mass[index].classList.add('active');

    return true;
  }

  $(".our_service_carousel_left_nav").click(function () {
    our_service_carousel.trigger("next.owl.carousel");
  });
  $(".our_service_carousel_right_nav").click(function () {
    our_service_carousel.trigger("prev.owl.carousel");
  });
}




function closeLeftMenuByMissClick(blockname) {

  $(document).mouseup(function(e) {
    let t = $(blockname);
    t.is(e.target) || 0 !== t.has(e.target).length || hide_left_menu();
  })
}

