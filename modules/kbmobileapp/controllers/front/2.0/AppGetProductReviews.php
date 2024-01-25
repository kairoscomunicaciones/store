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
 * API to get reviews of product
 */

require_once 'AppCore.php';
include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');

class AppGetProductReviews extends AppCore
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
        if (!(int) Tools::getValue('product_id', 0)) {
            $this->content = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Product id is missing'),
                    'AppGetProductDetails'
                )
            );
        } else {
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
                $this->content['status'] = "success";
                $this->content['reviews'] = $this->getProductComments();
            }
        }
        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }
    
    public function getProductComments()
    {
        $reviews = array();
        $comments = array();
        $getReviews = array();
        $getReviews = ProductComment::getByProduct((int) (Tools::getValue('product_id')));
        $index = 0;
        foreach ($getReviews as $rev) {
            $comments[$index] = array(
                'id_product_comment' => $rev['id_product_comment'],
                'date_add' => date('Y-m-d H:i:s', strtotime($rev['date_add'])),
                'customer_name' => $rev['title'],
                'content' => $rev['content'],
                'grade' => (String) $rev['grade']
            );
            $index++;
        }
        $reviews['comments'] = $comments;
        $reviews['number_of_reviews'] = ProductComment::getCommentNumber((int) (Tools::getValue('product_id')));
        if ($reviews['number_of_reviews'] > 0) {
            $avg_rating = ProductComment::getAverageGrade((int) Tools::getValue('product_id'));
            $reviews['averagecomments'] = (String) $avg_rating['grade'];
            $reviews['number_of_reviews'] = (String) $reviews['number_of_reviews'];
        } else {
            $reviews['averagecomments'] = "0";
            $reviews['number_of_reviews'] = (String) $reviews['number_of_reviews'];
        }
        return $reviews;
    }
}
