{*
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Mr. APPs
 * @copyright Mr. APPs 2023
 * @license Mr. APPs
*}

{extends file='layouts/layout-content-only.tpl'}

{block name='content'}
{block name='page_content_container'}
  <section id="content" class="page-content page-cms page-cms-{$cms.id|escape:'htmlall':'UTF-8'}">

    {block name='cms_content'}
      {$cms.content nofilter} {* HTML comment, no escape necessary like cms/page.tpl in ps classic theme to render cms page content for App*}
    {/block}

    {block name='hook_cms_dispute_information'}
      {hook h='displayCMSDisputeInformation'}
    {/block}

    {block name='hook_cms_print_button'}
      {hook h='displayCMSPrintButton'}
    {/block}

  </section>
{/block}
{/block}
