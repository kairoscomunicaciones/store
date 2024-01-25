<?php
/**
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
 */

namespace MrAPPs\MrShopApi\Service;

use Configuration;
use Context;
use Module;
use MrAPPs\MrShopApi\Handler\Api\DataHandler;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Product;

class ExternalModuleService
{
    private $dataHandler;

    private $module;

    private $priceFormatter;

    public function __construct()
    {
        $this->dataHandler = new DataHandler();
        $this->module = ApiUtils::getModule();
        $this->priceFormatter = new PriceFormatter();
    }

    public function egbuycentimeterGetQuantities(
        $productId,
        $minimum_quantity = 0,
        $quantity = 0,
        $quantity_available = 0,
        $unit_increment = null,
        $stock_quantity = null
    ) {
        $productInstance = new Product((int) $productId);

        $params = [
            'quantity' => $quantity,
            'minimum_quantity' => $minimum_quantity,
            'quantity_available' => $quantity_available,
            'unit_increment' => $unit_increment,
            'stock_quantity' => $stock_quantity,
            'unit_price' => null
        ];

        if (isset($productInstance->unity) && !empty($productInstance->unity)) {
            if ($this->dataHandler->getApiVersion() <= 11) {
                $params['unit_increment'] = [
                    'unit_type' => 'cm',
                    'unit_value' => 50
                ];
                $params['quantity'] = ((int) $params['quantity']) / 100;
                $params['quantity_available'] = $params['quantity_available'] / 100;
                $params['stock_quantity'] = sprintf(
                    $this->module->l('%s m in stock', 'productws'),
                    $params['quantity_available'] / 100
                );
            } else {
                $params['unit_increment'] = [
                    'unit_type' => 'm',
                    'unit_value' => 0.5
                ];
                $params['minimum_quantity'] = ((int) $params['minimum_quantity']) / 100.0;
                $params['quantity'] = ((int) $params['quantity']) / 100.0;
                $params['quantity_available'] = ((int) $params['quantity_available']) / 100.0;
                $output['stock_quantity'] = sprintf(
                    $this->module->l('%s m in stock', 'productws'),
                    $params['quantity_available'] / 100.0
                );
            }
        }

        return $params;
    }

    public function egbuycentimeterCalculatePrices($discountedPrice)
    {
        $out = [
            'discounted_price' => '',
            'discounted_price_val' => [],
            'full_price'
        ];

        $price = $discountedPrice * 100;
        $out['discounted_price'] = $this->priceFormatter->format($price, $this->dataHandler->getCurrencyId()).' /m';
        $out['discounted_price_val'] = $this->priceFormatter->getPriceData($price);
        $out['full_price'] = '';

        return $out;
    }

    public function minpurchaseGetQuantities(
        $productId
    ) {
        $out = [
            'minimum_quantity' => null,
            'quantity_available' => null,
            'unit_increment' => null
        ];

        $productInstance = new Product((int) $productId);

        require_once _PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php';
        $minpurchaseProduct = \MinpurchaseConfiguration::setProduct($productInstance);

        if (property_exists($minpurchaseProduct, 'minimal_quantity')) {
            $out['minimum_quantity'] = (int) $minpurchaseProduct->minimal_quantity;
        }

        if (property_exists($minpurchaseProduct, 'maximum_quantity')) {
            $out['quantity_available'] = (int) $minpurchaseProduct->maximum_quantity;
        }

        if (property_exists($minpurchaseProduct, 'increment_qty')) {
            $out['unit_increment'] = [
                'unit_type' => '',
                'unit_value' => (int) $minpurchaseProduct->increment_qty
            ];
        }

        return $out;
    }

    public function minpurchaseCheckQuantity($id_product, $quantityToChange, $cartQuantity = 0)
    {
        $productInstance = new Product($id_product);
        require_once _PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php';
        $minProduct = \MinpurchaseConfiguration::setProduct($productInstance);

        $quantityToCheck = $cartQuantity + $quantityToChange;
        $error = null;

        foreach (get_object_vars($minProduct) as $key => $value) {
            switch ($key) {
                case 'minimal_quantity':
                    if ($quantityToCheck < (int) $minProduct->minimal_quantity) {
                        $error = $this->module->l('Minimal quantity of product is not enough', 'cartws');
                    }

                    break;
                case 'maximum_quantity':
                    if ($quantityToCheck > (int) $minProduct->maximum_quantity) {
                        $error = $this->module->l('Maximum quantity of product is not enough', 'cartws');
                    }

                    break;
                case 'increment_qty':
                    if (($quantityToCheck % (int) $minProduct->increment_qty) != 0) {
                        $error = $this->module->l('Product quantity increment is not available', 'cartws');
                    }

                    break;
            }

            if ($error != false) {
                return $error;
            }
        }

        return null;
    }

    public function minpurchaseCheckAvailability($products)
    {
        Module::getInstanceByName('minpurchase');
        $payment = [
            'can_proceed' => count($products) > 0,
            'message' => null
        ];

        $refactorProducts = [];
        foreach ($products as $p) {
            $refactorProducts[] = [
                'id_product' => $p['id'],
                'id_product_attribute' => $p['id_product_attribute'],
                'cart_quantity' => $p['quantity'],
                'name' => $p['name']
            ];
        }

        $errorMessages = \MinpurchaseConfiguration::checkProductsAvailability($refactorProducts);
        if (count($errorMessages) > 0) {
            $payment = [
                'can_proceed' => false,
                'message' => implode('\n', $errorMessages)
            ];
        }

        return $payment;
    }

    public function productcommentsGetCriteria($product)
    {
        return $this->productcommentsGetCriterionRepository()->getByProduct($product->id, Context::getContext()->language->id);
    }

    public function productcommentsGetAverageGrade($product)
    {
        $productCommentRepository = $this->productcommentsGetProductCommentRepository();

        $averageRating = $productCommentRepository->getAverageGrade($product->id, (bool) Configuration::get('PRODUCT_COMMENTS_MODERATE'));
        $nbComments = $productCommentRepository->getCommentsNumber($product->id, (bool) Configuration::get('PRODUCT_COMMENTS_MODERATE'));

        return [
            'averageRating' => $averageRating,
            'nbComments' => $nbComments
        ];
    }

    public function productcommentsGetComments($product, $page)
    {
        $comments = $this->productcommentsGetProductCommentRepository()->paginate(
            $product->id,
            $page,
            (int) Configuration::get('PRODUCT_COMMENTS_COMMENTS_PER_PAGE'),
            (bool) Configuration::get('PRODUCT_COMMENTS_MODERATE')
        );
        $out = [];

        $isLastNameAnonymous = Configuration::get('PRODUCT_COMMENTS_ANONYMISATION');

        foreach ($comments as $c) {
            if ($isLastNameAnonymous && isset($c['lastname'])) {
                $c['lastname'] = substr($c['lastname'], 0, 1).'.';
            }

            // if registered customer : return customer first and last name instead of using customer_name
            if (!empty($c['lastname'])) {
                $customerName = htmlentities($c['firstname'].' '.$c['lastname']);
            } else {
                $customerName = htmlentities($c['customer_name']);
            }

            $out[] = [
                'id' => $c['id_product_comment'],
                'id_product' => $c['id_product'],
                'title' => $c['title'],
                'content' => $c['content'],
                'average' => (int) $c['grade'],
                'customer_name' => $customerName,
                'date' => (new \DateTime($c['date_add']))->getTimestamp()
            ];
        }

        return $out;
    }

    public function productcommentsCountComments($product)
    {
        return $this->productcommentsGetProductCommentRepository()->getCommentsNumber(
            $product->id,
            (bool) Configuration::get('PRODUCT_COMMENTS_MODERATE')
        );
    }

    public function productcommentsAddComment($data, $criterions)
    {
        $entityManager = ApiUtils::getEntityManager();

        $productComment = new \PrestaShop\Module\ProductComment\Entity\ProductComment();
        $productComment
            ->setProductId($data['id_product'])
            ->setTitle($data['title'])
            ->setContent($data['content'])
            ->setGuestId((int) $data['id_guest'])
            ->setCustomerName($data['customer_name'])
            ->setCustomerId($data['id_customer'])
            ->setDateAdd(new \DateTime());

        $entityManager->persist($productComment);

        $this->productcommentsAddGrades($productComment, $criterions);
        $entityManager->flush();

        return $productComment;
    }

    public function productcommentsAddGrades($productComment, $criterions)
    {
        $entityManager = ApiUtils::getEntityManager();
        $criterionRepository = $entityManager->getRepository(\PrestaShop\Module\ProductComment\Entity\ProductCommentCriterion::class);
        $averageGrade = 0;

        if (count($criterions) > 0) {
            foreach ($criterions as $criterionId => $grade) {
                $criterion = $criterionRepository->findOneBy([
                    'id' => $criterionId
                ]);
                $criterionGrade = new \PrestaShop\Module\ProductComment\Entity\ProductCommentGrade(
                    $productComment,
                    $criterion,
                    $grade
                );

                $entityManager->persist($criterionGrade);
                $averageGrade += $grade;
            }

            $averageGrade /= count($criterions);
        }

        $productComment->setGrade($averageGrade);
    }

    protected function productcommentsGetCriterionRepository()
    {
        /** @var ProductCommentCriterionRepository $criterionRepository */
        return Context::getContext()->controller->get('product_comment_criterion_repository');
    }

    protected function productcommentsGetProductCommentRepository()
    {
        /** @var ProductCommentRepository $productCommentRepository */
        return Context::getContext()->controller->get('product_comment_repository');
    }
}
