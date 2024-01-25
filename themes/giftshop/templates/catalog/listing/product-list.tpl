{*
* 2007-2022 ETS-Soft
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
* 
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs, please contact us for extra customization service at an affordable price
*
*  @author ETS-Soft <etssoft.jsc@gmail.com>
*  @copyright  2007-2022 ETS-Soft
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of ETS-Soft
*}
{extends file=$layout}

{block name='content'}
  <div id="main">

    {block name='product_list_header'}
      <h2 class="h2">{$listing.label|escape:'html':'UTF-8'}</h2>
    {/block}

    <div id="products" class="sang">
      {if $listing.products|count}

        <div>
          {block name='product_list_top'}
            {include file='catalog/_partials/products-top.tpl' listing=$listing}
          {/block}
        </div>

        {block name='product_list_active_filters'}
          <div class="hidden-sm-down">
            {$listing.rendered_active_filters nofilter}
          </div>
        {/block}

        <div>
          {block name='product_list'}
            {include file='catalog/_partials/products.tpl' listing=$listing}
          {/block}
        </div>

        <div>
          {block name='product_list_bottom'}
            {include file='catalog/_partials/products-bottom.tpl' listing=$listing}
          {/block}
        </div>

      {else}

        {include file='errors/not-found.tpl'}

      {/if}
    </div>

  </div>



  <script>

/*
  const {
    host, hostname, href, origin, pathname, port, protocol, search
  } = window.location
  */
  console.log(pathname)
  
  if(pathname == "/6-salud"){
  
      //cargar el script una vez que se haya cargado por completo la pagina
                  console.log("cargando script");
                  //que cargue despues de carque todos los estilos css 
                   document.querySelector(".menu_header").style.backgroundColor = "#035C5A";
                   
                   
                         var logos = document.getElementsByClassName("logo");
for (var i = 0; i < logos.length; i++) {
    logos[i].src = "https://inversionesrpz.com/img/logos.png";
}
                   
                   
                 
         
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 500);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 1000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 2000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 1000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 1000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 3000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 4000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 5000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 6000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 7000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 8000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 9000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 5500);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 51000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 52000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 1000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 51000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 53000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 54000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 55000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 56000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 57000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 58000);
                  setTimeout(function(){
                      console.log("cargando script");
                      //cambiar el color del menu
                      $(".menu_header").css("background-color", "#035C5A !important");
                      $(".ets_mm_megamenu.scroll_heading").css("background-color", "#035C5A !important");
                      $(".footer_top_extra").css("background-color", "#035C5A !important");
                  }, 59000);
                  
   
  }else{
    console.log("no cambio nada")
  }
      
  
  
  
  
      
    </script>






{/block}
