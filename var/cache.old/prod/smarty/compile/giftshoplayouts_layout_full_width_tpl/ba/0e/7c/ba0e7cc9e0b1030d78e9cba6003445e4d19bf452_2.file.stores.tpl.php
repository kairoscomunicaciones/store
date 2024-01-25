<?php
/* Smarty version 3.1.43, created on 2024-01-14 17:23:43
  from '/home2/inveriti/public_html/themes/giftshop/templates/cms/stores.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_65a450df776fd7_76677993',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ba0e7cc9e0b1030d78e9cba6003445e4d19bf452' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/cms/stores.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a450df776fd7_76677993 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_164136390965a450df766ee9_90342528', 'page_title');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_179749982865a450df769950_00029395', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_title'} */
class Block_164136390965a450df766ee9_90342528 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_title' => 
  array (
    0 => 'Block_164136390965a450df766ee9_90342528',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Our stores','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>

<?php
}
}
/* {/block 'page_title'} */
/* {block 'page_content_container'} */
class Block_179749982865a450df769950_00029395 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_179749982865a450df769950_00029395',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <div id="content" class="page-content page-stores">

    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['stores']->value, 'store');
$_smarty_tpl->tpl_vars['store']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['store']->value) {
$_smarty_tpl->tpl_vars['store']->do_else = false;
?>
      <article id="store-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="store-item card">
        <div class="store-item-container clearfix">
            <?php if ($_smarty_tpl->tpl_vars['store']->value['image']) {?>
              <div class="col-md-3 store-picture hidden-sm-down">
            <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['image']['bySize']['stores_default']['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['image']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['image']['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
          </div>
              <?php }?>
          <div class="col-md-5 col-sm-7 col-xs-12 store-description">
            <h3 class="h3 card-title"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</h3>
            <address><?php echo $_smarty_tpl->tpl_vars['store']->value['address']['formatted'];?>
</address>
            <?php if ($_smarty_tpl->tpl_vars['store']->value['note'] || $_smarty_tpl->tpl_vars['store']->value['phone'] || $_smarty_tpl->tpl_vars['store']->value['fax'] || $_smarty_tpl->tpl_vars['store']->value['email']) {?>
              <a data-toggle="collapse" href="#about-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" aria-expanded="false" aria-controls="about-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'About and Contact','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</strong><i class="material-icons">&#xE409;</i></a>
            <?php }?>
          </div>
          <div class="col-md-4 col-sm-5 col-xs-12 divide-left">
            <table>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['store']->value['business_hours'], 'day');
$_smarty_tpl->tpl_vars['day']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['day']->value) {
$_smarty_tpl->tpl_vars['day']->do_else = false;
?>
              <tr>
                <th><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['day']->value['day'],4,'.' )),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</th>
                <td>
                  <ul>
                  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['day']->value['hours'], 'h');
$_smarty_tpl->tpl_vars['h']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['h']->value) {
$_smarty_tpl->tpl_vars['h']->do_else = false;
?>
                    <li><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['h']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</li>
                  <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                  </ul>
                </td>
              </tr>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </table>
          </div>
        </div>
        <footer id="about-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['id'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="collapse">
          <div class="store-item-footer divide-top">
            <div class="card-block">
              <?php if ($_smarty_tpl->tpl_vars['store']->value['note']) {?>
                <p class="text-justify"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['note'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
<p>
              <?php }?>
            </div>
            <ul class="card-block">
              <?php if ($_smarty_tpl->tpl_vars['store']->value['phone']) {?>
                <li><i class="material-icons">&#xE0B0;</i><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['phone'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</li>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['store']->value['fax']) {?>
                <li><i class="material-icons">&#xE8AD;</i><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['fax'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</li>
              <?php }?>
              <?php if ($_smarty_tpl->tpl_vars['store']->value['email']) {?>
                <li><i class="material-icons">&#xE0BE;</i><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['store']->value['email'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</li>
              <?php }?>
            </ul>
          </div>
        </footer>
      </article>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

  </div>
<?php
}
}
/* {/block 'page_content_container'} */
}
