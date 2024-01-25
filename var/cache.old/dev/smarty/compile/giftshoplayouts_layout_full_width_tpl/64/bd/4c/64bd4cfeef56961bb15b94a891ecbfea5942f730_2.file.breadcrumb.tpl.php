<?php
/* Smarty version 3.1.43, created on 2023-12-13 10:34:03
  from '/home2/inveriti/public_html/themes/giftshop/templates/_partials/breadcrumb.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6579c0db3c20e1_95740006',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64bd4cfeef56961bb15b94a891ecbfea5942f730' => 
    array (
      0 => '/home2/inveriti/public_html/themes/giftshop/templates/_partials/breadcrumb.tpl',
      1 => 1697325107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6579c0db3c20e1_95740006 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['page']->value['page_name'] != 'index') {?>
    <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'prices-drop') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sales','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'my-account') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'My account','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'search') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'sitemap') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sitemap','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'new-products') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'New Products','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'best-sales') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Best Sellers','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'stores') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Stores','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'contact') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Contact','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'order-confirmation') {?>
        <div class="breadcrumb_wrapper">
            <div class="container">
                <ul class="breadcrumb_page_other">
                    <li>
                        <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['urls']->value['base_url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>
                        <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Order Confirmation','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </li>
                </ul>
            </div>
        </div>
    <?php } else { ?>
        <div class="breadcrumb_wrapper">
            <div class="container">
            <nav data-depth="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['breadcrumb']->value['count'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="breadcrumb">
              <ol itemscope itemtype="http://schema.org/BreadcrumbList">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['breadcrumb']->value['links'], 'path', false, NULL, 'breadcrumb', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['path']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['path']->value) {
$_smarty_tpl->tpl_vars['path']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_breadcrumb']->value['iteration']++;
?>
                  <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemprop="item" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['path']->value['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                      <span itemprop="name"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['path']->value['title'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
                    </a>
                    <meta itemprop="position" content="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( (isset($_smarty_tpl->tpl_vars['__smarty_foreach_breadcrumb']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_breadcrumb']->value['iteration'] : null),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
                  </li>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'cart') {?>
                    <li itemtype="http://schema.org/ListItem" itemscope="" itemprop="itemListElement">
                        <a>
                          <span itemprop="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shopping Cart','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                        </a>
                      </li>
                <?php }?>
              </ol>
            </nav>
            </div>
        </div>
    <?php }?>
    
<?php }
}
}
