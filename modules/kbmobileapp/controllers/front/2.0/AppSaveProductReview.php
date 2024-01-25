<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 * Description
 *
 * API to save reviews
 */

require_once 'AppCore.php';
include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');

class AppSaveProductReview extends AppCore
{
    private $product = null;

    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData()
    {
        $error = false;
        if (!(int) Tools::getValue('product_id', 0)) {
            $error = true;
            $this->content = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Product id is missing'),
                    'AppGetProductDetails'
                )
            );
//        } else if (!Tools::getValue('title') || !Validate::isGenericName(Tools::getValue('title'))) {
//            $error = true;
//            $this->content = array(
//                'status' => 'failure',
//                'message' => parent::getTranslatedTextByFileAndISO(
//                    Tools::getValue('iso_code', false),
//                    $this->l('Title is incorrect'),
//                    'AppSaveProductReview'
//                ),
//            );
        } else if (!Tools::getValue('content') || !Validate::isMessage(Tools::getValue('content'))) {
            $error = true;
            $this->content = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Content is incorrect'),
                    'AppSaveProductReview'
                )
            );
        } else if (!Tools::isSubmit('customer_name') || !Tools::getValue('customer_name') || !Validate::isGenericName(Tools::getValue('customer_name'))) {
            $error = true;
            $this->content = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Customer Name is incorrect'),
                    'AppSaveProductReview'
                )
            );
        } else if (!Tools::getValue('rating') || Tools::getValue('rating')<1) {
            $error = true;
            $this->content = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('You must give a rating greater than '),
                    'AppSaveProductReview'
                )
            );
        }
        if (!$error) {
            $this->product = new Product(
                Tools::getValue('product_id', 0),
                true,
                $this->context->language->id,
                $this->context->shop->id,
                $this->context
            );
            if (!Validate::isLoadedObject($this->product)) {
                $this->content = array(
                    'status' => 'failure',
                    'message' => parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false),
                        $this->l('Product not found'),
                        'AppGetProductDetails'
                    )
                );
            } else {
                $this->content = $this->saveProductComments();
            }
        }
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
    
    public function saveProductComments()
    {
        $result = 'success';
        $id_guest = 0;
        $id_customer = $this->context->customer->id;
        
        if (!$id_customer) {
            $id_guest = $this->context->cookie->id_guest;
        }
        $errors = array();
//        if (!Tools::getValue('title') || !Validate::isGenericName(Tools::getValue('title'))) {
//            $errors[] = parent::getTranslatedTextByFileAndISO(
//                Tools::getValue('iso_code', false),
//                $this->l('Title is incorrect'),
//                'AppSaveProductReview'
//            );
//        }
        if (!Tools::getValue('content') || !Validate::isMessage(Tools::getValue('content'))) {
            $errors[] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Content is incorrect'),
                'AppSaveProductReview'
            );
        }
        if (!$id_customer && (!Tools::isSubmit('customer_name') || !Tools::getValue('customer_name') || !Validate::isGenericName(Tools::getValue('customer_name')))) {
            $errors[] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Customer Name is incorrect'),
                'AppSaveProductReview'
            );
        }
        if (!$this->context->customer->id && !Configuration::get('PRODUCT_COMMENTS_ALLOW_GUESTS')) {
            $errors[] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('You must be connected in order to send a comment'),
                'AppSaveProductReview'
            );
        }
        if (!Tools::getValue('rating')) {
            $errors[] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('You must give a rating'),
                'AppSaveProductReview'
            );
        }
        if (!count($errors)) {
            $comment = new ProductComment();
            $comment->content = strip_tags(Tools::getValue('content'));
            $comment->id_product = (int)Tools::getValue('product_id');
            $comment->id_customer = (int)$id_customer;
            $comment->id_guest = $id_guest;
            $comment->customer_name = Tools::getValue('customer_name');
            if (!$comment->customer_name) {
                $comment->customer_name = pSQL($this->context->customer->firstname.' '.$this->context->customer->lastname);
            }
            $comment->title = Tools::getValue('customer_name');
            $comment->grade = 0;
            $comment->validate = 0;
            $comment->save();
            $grade_sum = 0;
            $grade_sum = Tools::getValue('rating');
            $product_comment_criterion = new ProductCommentCriterion(1);
            if ($product_comment_criterion->id) {
                $product_comment_criterion->addGrade($comment->id, $grade_sum);
            }
            /**
             * Rating is not an array, so removed the count. Otherwise, from PHP 7.2, the count() function throws a warning when used with non-countable types like null, boolean, integer, float, and string. 
             * TGmay2023 Save-Review-Count
             * @date 06-05-2023
             * @modifier Tanisha Gupta
             */
            if (Tools::getValue('rating') >= 1) {
                    $comment->grade = $grade_sum / (Tools::getValue('rating'));
                    // Update Grade average of comment
                    $comment->save();
            }
            $result = 'success';
            $message =  parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l("Your comment has been registered and will become available as soon as it is approved by a moderator."),
                'AppSaveProductReview'
            );
        } else {
            $result = 'failure';
            $message = '';
        }
        return array('status' => $result, 'message' => $message);
    }
}
