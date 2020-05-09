var ajax_loc = location.protocol + '//' + location.host + '/wp-admin/admin-ajax.php';

if (document.getElementById('product_carousel_wrapper')) {
  init_carousel();

  var filter_item_mass = document.getElementsByClassName('filter_item');

  for(let filter_item of filter_item_mass){
    filter_item.addEventListener('click', function () {
      if(!this.classList.contains('currient')){
        set_carousel_overlay();
        do_carousel_filter(filter_item.id, this);
      }
    });
  }
}

function render_carousel(msg){
  destroy_carousel();
  set_carousel_HTML(msg);
  init_carousel();
}

function destroy_carousel(){
  $(".product_carousel").owlCarousel('destroy');
  $(".inner_galary").owlCarousel('destroy');

  return true;
}

function init_carousel(){

  let product_carousel = $(".product_carousel");
  product_carousel.owlCarousel({
    loop: false,
    rewind: true,
    nav: false,
    margin: 0,
    padding: 0,
    dots: false,
    responsive: {
      0: {
        items: 1,
      },
      576: {
        items: 2,
        margin: 20,
      },
      992: {
        items: 3,
        margin: 20,
      }
    }
  });

  $(".product_carousel_left_nav").click(function () {
    product_carousel.trigger("next.owl.carousel");
  });
  $(".product_carousel_right_nav").click(function () {
    product_carousel.trigger("prev.owl.carousel");
  });

  let inner_galary = $(".inner_galary");
  inner_galary.owlCarousel({
    loop: false,
    rewind: true,
    nav: false,
    margin: 0,
    padding: 0,
    dots: true,
    mouseDrag: false,
    responsive: {
      0: {
        items: 1,
        touchDrag: true,
      },
      450: {
        items: 1,
        touchDrag: false,
      }
    }
  });

  return true;
}

async function do_carousel_filter(elem_id, filter_button_elem) {
  $.ajax({
    type: "get",
    url: ajax_loc,
    data: {
      action: 'mp_do_filter',
      filter_type: elem_id
    },
    success: function (msg) {
      render_carousel(msg);
    }
  }).done(function () {
    set_filter_button_currient(filter_button_elem);
    remove_carousel_overlay()
  }).fail(function () {
    remove_carousel_overlay();
    alert('Извините, данные товары временно недоступны.')
  });

  return false;
}

function set_filter_button_currient(filter_button_elem){

  for(let filter_item of filter_item_mass){
    filter_item.classList.remove('currient');
  }

  filter_button_elem.classList.add('currient');
  return true;
}

function set_carousel_HTML(msg){
  document.getElementById('product_carousel').innerHTML = msg;
  return true;
}

function set_carousel_overlay(){
    document.getElementById('carousel_overlay').classList.remove('d_none');
    document.getElementById('filter_overlay').classList.remove('d_none');
    return true;
}

function remove_carousel_overlay(){
  document.getElementById('carousel_overlay').classList.add('d_none');
  document.getElementById('filter_overlay').classList.add('d_none');
  return true;
}