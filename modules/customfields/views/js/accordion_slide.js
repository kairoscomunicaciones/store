/**
* FMM Custom Fields
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
*  @author    FME Modules
*  @copyright 2022 fmemodules.com All right reserved
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @category  FMM Modules
*  @package   Customfields
*/
$(document).on("click", ".accordion-header", function() {
    $(this).toggleClass("active").next().slideToggle();
 });
