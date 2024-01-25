<?php
/* Smarty version 3.1.43, created on 2024-01-16 18:50:05
  from '/home2/inveriti/public_html/themes/giftshop/templates/catalog/listing/product-list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a7081d7e9374_79406082',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9a81f922a68cfeab6ad1ee46785de1b0f7291e6f' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/catalog/listing/product-list.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:catalog/_partials/products-top.tpl' => 1,
    'file:catalog/_partials/products.tpl' => 1,
    'file:catalog/_partials/products-bottom.tpl' => 1,
    'file:errors/not-found.tpl' => 1,
  ),
),false)) {
function content_65a7081d7e9374_79406082 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_38364362165a7081d7ddec9_00978439', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'product_list_header'} */
class Block_186113303165a7081d7de211_60258883 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <h2 class="h2"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['listing']->value['label'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</h2>
    <?php
}
}
/* {/block 'product_list_header'} */
/* {block 'product_list_top'} */
class Block_115935585565a7081d7df787_17424805 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/products-top.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('listing'=>$_smarty_tpl->tpl_vars['listing']->value), 0, false);
?>
          <?php
}
}
/* {/block 'product_list_top'} */
/* {block 'product_list_active_filters'} */
class Block_130481303365a7081d7e5410_22066464 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <div class="hidden-sm-down">
            <?php echo $_smarty_tpl->tpl_vars['listing']->value['rendered_active_filters'];?>

          </div>
        <?php
}
}
/* {/block 'product_list_active_filters'} */
/* {block 'product_list'} */
class Block_66259293865a7081d7e5f63_34751393 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/products.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('listing'=>$_smarty_tpl->tpl_vars['listing']->value), 0, false);
?>
          <?php
}
}
/* {/block 'product_list'} */
/* {block 'product_list_bottom'} */
class Block_169813854865a7081d7e6be9_01947506 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/products-bottom.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('listing'=>$_smarty_tpl->tpl_vars['listing']->value), 0, false);
?>
          <?php
}
}
/* {/block 'product_list_bottom'} */
/* {block 'content'} */
class Block_38364362165a7081d7ddec9_00978439 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_38364362165a7081d7ddec9_00978439',
  ),
  'product_list_header' => 
  array (
    0 => 'Block_186113303165a7081d7de211_60258883',
  ),
  'product_list_top' => 
  array (
    0 => 'Block_115935585565a7081d7df787_17424805',
  ),
  'product_list_active_filters' => 
  array (
    0 => 'Block_130481303365a7081d7e5410_22066464',
  ),
  'product_list' => 
  array (
    0 => 'Block_66259293865a7081d7e5f63_34751393',
  ),
  'product_list_bottom' => 
  array (
    0 => 'Block_169813854865a7081d7e6be9_01947506',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <div id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_186113303165a7081d7de211_60258883', 'product_list_header', $this->tplIndex);
?>


    <div id="products" class="sang">
      <?php if (count($_smarty_tpl->tpl_vars['listing']->value['products'])) {?>

        <div>
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_115935585565a7081d7df787_17424805', 'product_list_top', $this->tplIndex);
?>

        </div>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_130481303365a7081d7e5410_22066464', 'product_list_active_filters', $this->tplIndex);
?>


        <div>
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_66259293865a7081d7e5f63_34751393', 'product_list', $this->tplIndex);
?>

        </div>

        <div>
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_169813854865a7081d7e6be9_01947506', 'product_list_bottom', $this->tplIndex);
?>

        </div>

      <?php } else { ?>

        <?php $_smarty_tpl->_subTemplateRender('file:errors/not-found.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      <?php }?>
    </div>

  </div>



  <?php echo '<script'; ?>
>

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
      
  
  
  
  
      
    <?php echo '</script'; ?>
>






<?php
}
}
/* {/block 'content'} */
}
