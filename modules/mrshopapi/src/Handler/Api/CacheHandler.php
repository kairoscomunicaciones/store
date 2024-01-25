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

namespace MrAPPs\MrShopApi\Handler\Api;

use Category;
use Context;
use Customer;
use Db;
use MrAPPs\MrShopApi\Api\Front\AppHomeWS;
use MrAPPs\MrShopApi\Api\Front\CategoryWS;
use MrAPPs\MrShopApi\Api\Front\NewProductWS;
use MrAPPs\MrShopApi\Api\Front\PricesDropWS;
use MrAPPs\MrShopApi\Api\Front\ProductWS;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use Product;
use Tools;
use Validate;

class CacheHandler
{
    // Module
    private $module;

    // DataHandler
    private $dataHandler;

    // Path to cache folder (with trailing /)
    private $path;

    // Resource object
    private $resourceObject = null;

    // Cache file extension
    private $extension = '.cache';

    // Cache maxAge
    private $maxAge = null;

    public function __construct($dataHandler)
    {
        $this->path = _PS_MODULE_DIR_.'/mrshopapi/cache/';
        $this->dataHandler = $dataHandler;
        $this->module = ApiUtils::getModule();
    }

    private function safeFilename($filename)
    {
        return preg_replace('/[^0-9a-z\.\_\-]/i', '', Tools::strtolower($filename));
    }

    public function setResourceObject($object)
    {
        $this->resourceObject = $object;
    }

    public function getIdentifier($object, $getParams, $isListRequest, $customerId = null, $id = null)
    {
        $identifier = ($isListRequest ? 'list' : 'detail').'_'.$getParams['entity'];

        if (!array_key_exists('version', $getParams)) {
            $getParams['version'] = $this->dataHandler->getApiVersion();
        }
        ksort($getParams);

        $suffix = '';
        $psParams = ['fc', 'module', 'controller', 'entity', 'auth_token', 'id_guest'];
        foreach ($getParams as $key => $value) {
            if (!in_array($key, $psParams) && $object->isCacheParameterAllowed($key)) {
                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        $suffix .= '-'.$key.$subkey.'_'.$subvalue;
                    }
                } else {
                    $suffix .= '-'.$key.'_'.$value;
                }
            }
        }

        if ($object->isCacheUserGroupsHandlerEnabled()) {
            $groups = Customer::getGroupsStatic($customerId);
            if (count($groups) > 0) {
                sort($groups);
                $suffix .= '-groups_'.implode('', $groups);
            }
        }

        if ($id) {
            $identifier .= '_'.$id;
        }

        if (Validate::isLoadedObject(Context::getContext()->language)) {
            $identifier .= '_'.Context::getContext()->language->iso_code;
        }

        if (Validate::isLoadedObject(Context::getContext()->currency)) {
            $identifier .= '_'.Tools::strtolower(Context::getContext()->currency->iso_code);
        }

        if ($suffix && Tools::strlen($suffix) > 0) {
            $identifier .= '_'.sha1($suffix);
        }

        return $identifier;
    }

    public function createDirIfNeeded($dir)
    {
        $fullDir = $this->path.$dir;

        if (!is_dir($fullDir)) {
            return mkdir($fullDir, 0755, true);
        }

        return true;
    }

    public function setCache($dir, $identifier, $data)
    {
        $cacheEnabled = $this->resourceObject ? $this->resourceObject->isCacheEnabled() : true;
        if ($cacheEnabled && $this->createDirIfNeeded($dir)) {
            return file_put_contents($this->path.$dir.'/'.$this->safeFilename($identifier).$this->extension, $data);
        }

        return false;
    }

    public function isCached($dir, $identifier, $cacheTtl = null)
    {
        if (!$cacheTtl && $this->resourceObject) {
            $cacheTtl = $this->resourceObject->getCacheTime();
        }
        if (!$cacheTtl) {
            return false;
        }

        $filename = $this->path.$dir.'/'.$this->safeFilename($identifier).$this->extension;

        if (!file_exists($filename)) {
            $this->maxAge = $cacheTtl;

            return false;
        }

        $lastModifiedTime = filemtime($filename);
        $realExpiresAt = $lastModifiedTime + $cacheTtl; // Expire time for cache checking
        $this->maxAge = $realExpiresAt - time();

        if ($this->maxAge <= 0) {
            $this->maxAge = $cacheTtl;

            return false;
        }

        return true;
    }

    public function getCache($dir, $identifier, $cacheTtl = null)
    {
        if ($this->isCached($dir, $identifier, $cacheTtl)) {
            $filename = $this->path.$dir.'/'.$this->safeFilename($identifier).$this->extension;
            $cachedFile = Tools::file_get_contents($filename);

            return empty($cachedFile) ? false : $cachedFile;
        }

        return false;
    }

    public function getMaxAge()
    {
        return $this->maxAge;
    }

    public function cleanup($dir)
    {
        $cacheTtl = $this->resourceObject ? $this->resourceObject->getCacheTime() : null;

        $results = glob($this->path.$dir.'/*'.$this->extension);

        foreach ($results as $filePath) {
            $identifier = basename($filePath, $this->extension);
            if (!$this->isCached($dir, $identifier, $cacheTtl)) {
                try {
                    unlink($filePath);
                } catch (\Exception $e) {
                }
            }
        }
    }

    public function deleteCacheForResource($dir, $id, $deleteList = true)
    {
        $basePath = $this->path.$dir;
        $listQuery = sprintf('%s/list_%s*', $basePath, $dir);
        $detailQuery = sprintf('%s/detail_%s_%d*', $basePath, $dir, $id);

        $results = glob($detailQuery);
        if ((bool) $deleteList) {
            $results = array_merge($results, glob($listQuery));
        }

        foreach ($results as $filePath) {
            if (file_exists($filePath)) {
                try {
                    unlink($filePath);
                } catch (\Exception $e) {
                }
            }
        }
    }

    public function regenerateCacheForCategory()
    {
        $entity = 'categories';

        $_GET['ajax'] = true;
        $categoryWS = new CategoryWS($this->dataHandler);
        $identifier = $this->getIdentifier($categoryWS, ['entity' => $entity], true);
        $categoryWS->setCacheParams($this, $entity, $identifier);
        $categoryWS->getList([], null, false);
    }

    public function regenerateCacheForProduct($params)
    {
        $entity = 'products';

        $productWS = new ProductWS($this->dataHandler);
        $identifier = $this->getIdentifier($productWS, array_merge($params, ['entity' => $entity]), true);
        $productWS->setCacheParams($this, $entity, $identifier);
        $productWS->getList($params, null, false);
    }

    public function regenerateCacheForNewProducts()
    {
        $entity = 'new_products';

        $newProductWS = new NewProductWS($this->dataHandler);
        $identifier = $this->getIdentifier($newProductWS, ['entity' => $entity], true);
        $newProductWS->setCacheParams($this, $entity, $identifier);
        $newProductWS->getList([], null, false);
    }

    public function regenerateCacheForSpecialOffers()
    {
        $entity = 'special_offers';

        $specialOffersWS = new PricesDropWS($this->dataHandler);
        $identifier = $this->getIdentifier($specialOffersWS, ['entity' => $entity], true);
        $specialOffersWS->setCacheParams($this, $entity, $identifier);
        $specialOffersWS->getList([], null, false);
    }

    public function regenerateCacheForAppHome()
    {
        $entity = 'app_home';

        $appHomeWS = new AppHomeWS($this->dataHandler);
        $identifier = $this->getIdentifier($appHomeWS, ['entity' => $entity], true);
        $appHomeWS->setCacheParams($this, $entity, $identifier);
        $appHomeWS->getList([], null, false);
    }

    public function deleteCacheForCategory($params, $regenerateCache = true)
    {
        $dir = 'categories';

        $category = isset($params['category']) ? $params['category'] : null;
        $deletedChildren = isset($params['deleted_children']) ? $params['deleted_children']->getResults() : [];

        if (Validate::isLoadedObject($category) && !$category->is_root_category) {
            $this->deleteCacheForResource($dir, (int) $category->id);

            // Parent
            if ($category->id_parent) {
                $parentCategory = new Category((int) $category->id_parent);

                if (Validate::isLoadedObject($parentCategory) && !$parentCategory->is_root_category) {
                    $this->deleteCacheForResource($dir, (int) $parentCategory->id);

                    // Parent of the Parent
                    $parentParentCategory = new Category((int) $parentCategory->id_parent);
                    if (Validate::isLoadedObject($parentParentCategory) && !$parentParentCategory->is_root_category) {
                        $this->deleteCacheForResource($dir, (int) $parentParentCategory->id);
                    }
                }
            }
        } else {
            $regenerateCache = false;
        }

        foreach ($deletedChildren as $category) {
            if ($category && Validate::isLoadedObject($category)) {
                $this->deleteCacheForResource($dir, (int) $category->id);
            }
        }

        // Regenerate cache
        if ($regenerateCache) {
            $this->regenerateCacheForCategory();
        }
    }

    public function deleteCacheForProduct($params, $deleteList = true)
    {
        // Product object directly in params
        $product = isset($params['product']) ? $params['product'] : null;
        if (!Validate::isLoadedObject($product)) {
            // Product ID
            $idProduct = isset($params['id_product']) ? (int) $params['id_product'] : 0;
            $product = new Product($idProduct);
            if (!Validate::isLoadedObject($product)) {
                // Product Attribute ID
                $idProductAttribute = isset($params['id_product_attribute']) ? $params['id_product_attribute'] : 0;

                $idProduct = (int) Db::getInstance()->getValue('SELECT id_product FROM `'._DB_PREFIX_.'product_attribute` WHERE id_product_attribute = '.(int) $idProductAttribute);
                $product = new Product($idProduct);
            }
        }

        if (!Validate::isLoadedObject($product)) {
            return;
        }

        $dir = 'products';
        $this->deleteCacheForResource($dir, (int) $product->id, $deleteList);
        /*
        //Delete cache for home lists
        $this->deleteCacheForResource('new_products', 0, true);
        $this->deleteCacheForResource('special_offers', 0, true);
        $this->deleteCacheForResource('related_products', 0, true);

        //Delete cache for associated categories
        $categoryIDs = $product->getCategories();
        $regenerateCacheForCategory = false;
        foreach ($categoryIDs as $categoryID) {
            $category = new Category((int)$categoryID);

            if (Validate::isLoadedObject($category)) {
                $this->deleteCacheForCategory(array('category' => $category), false);
                $regenerateCacheForCategory = true;
            }
        }

        //Regenerate cache
        if ($regenerateCacheForCategory) {
            $this->regenerateCacheForCategory();
        }

         $this->regenerateCacheForProduct(array('home' => true));
         $this->regenerateCacheForNewProducts();
         $this->regenerateCacheForSpecialOffers();
        */
    }

    public function deleteCacheForAppHome($regenerateCache = true)
    {
        $dir = 'app_home';
        $this->deleteCacheForResource($dir, 0, true);

        // Regenerate cache
        if ($regenerateCache) {
            $this->regenerateCacheForAppHome();
        }
    }

    public function deleteAll()
    {
        $cacheFolders = glob($this->path.'*');
        foreach ($cacheFolders as $folder) {
            if (is_dir($folder)) {
                $cacheFiles = glob($folder.'/*');
                foreach ($cacheFiles as $file) {
                    if (file_exists($file)) {
                        try {
                            unlink($file);
                        } catch (\Exception $e) {
                        }
                    }
                }

                try {
                    rmdir($folder);
                } catch (\Exception $e) {
                }
            }
        }
    }
}
