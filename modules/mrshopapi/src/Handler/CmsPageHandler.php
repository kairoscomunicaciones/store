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

namespace MrAPPs\MrShopApi\Handler;

use Context;
use Db;

class CmsPageHandler
{
    private $context;

    public function __construct()
    {
        $this->context = Context::getContext();
    }

    public function getCmsPageTypes($id_lang = null)
    {
        if (!isset($id_lang)) {
            $id_lang = $this->context->language->id;
        }

        $query = "SELECT
            "._DB_PREFIX_."cms_lang.id_cms,
            "._DB_PREFIX_."cms_lang.meta_title,
            "._DB_PREFIX_."cms_lang.meta_description,
            "._DB_PREFIX_."cms_lang.content
        FROM "._DB_PREFIX_."cms_shop INNER JOIN "._DB_PREFIX_."cms_lang
        ON "._DB_PREFIX_."cms_shop.id_cms = "._DB_PREFIX_."cms_lang.id_cms
        WHERE "._DB_PREFIX_."cms_shop.id_shop = ".(int) $this->context->shop->id." AND "._DB_PREFIX_."cms_lang.id_lang = ".(int) $id_lang." AND "._DB_PREFIX_."cms_lang.id_cms NOT IN (
            SELECT "._DB_PREFIX_."mrshop_cms_page.id_cms
            FROM "._DB_PREFIX_."cms_shop INNER JOIN "._DB_PREFIX_."mrshop_cms_page
            ON "._DB_PREFIX_."cms_shop.id_cms = "._DB_PREFIX_."mrshop_cms_page.id_cms
            WHERE "._DB_PREFIX_."cms_shop.id_shop = ".(int) $this->context->shop->id."
        )";

        return Db::getInstance()->executeS($query);
    }

    public function getCmsPages($id_lang = null, $filter = null)
    {
        if (!isset($id_lang)) {
            $id_lang = $this->context->language->id;
        }

        $query = "SELECT
                "._DB_PREFIX_."mrshop_cms_page.id_cms_page,
                "._DB_PREFIX_."mrshop_cms_page.simple_cms,
                "._DB_PREFIX_."cms_lang.id_cms,
                "._DB_PREFIX_."cms_lang.meta_title,
                "._DB_PREFIX_."cms_lang.meta_description,
                "._DB_PREFIX_."cms_lang.content
            FROM "._DB_PREFIX_."cms_shop INNER JOIN "._DB_PREFIX_."mrshop_cms_page
            ON "._DB_PREFIX_."cms_shop.id_cms = "._DB_PREFIX_."mrshop_cms_page.id_cms INNER JOIN "._DB_PREFIX_."cms_lang
            ON "._DB_PREFIX_."mrshop_cms_page.id_cms = "._DB_PREFIX_."cms_lang.id_cms AND "._DB_PREFIX_."cms_lang.id_shop = ".(int) $this->context->shop->id." INNER JOIN "._DB_PREFIX_."cms
            ON "._DB_PREFIX_."cms_lang.id_cms = "._DB_PREFIX_."cms.id_cms
            WHERE "._DB_PREFIX_."cms_lang.id_lang = ".$id_lang." AND "._DB_PREFIX_."cms_shop.id_shop = ".(int) $this->context->shop->id."
            ORDER BY "._DB_PREFIX_."cms.position ASC "
            .(isset($filter) && array_key_exists('limit', $filter) && array_key_exists('offset', $filter)
                ? "LIMIT ".(int) $filter['limit']." OFFSET ".(int) $filter['offset']
                : "");

        $res = Db::getInstance()->executeS($query);

        return $this->popCmsPage($res);
    }

    public function getCmsPageById($id, $id_lang = null)
    {
        if (!isset($id_lang)) {
            $id_lang = $this->context->language->id;
        }

        $query = "SELECT
            "._DB_PREFIX_."mrshop_cms_page.id_cms_page,
            "._DB_PREFIX_."mrshop_cms_page.simple_cms,
            "._DB_PREFIX_."cms_lang.id_cms,
            "._DB_PREFIX_."cms_lang.meta_title,
            "._DB_PREFIX_."cms_lang.meta_description,
            "._DB_PREFIX_."cms_lang.content
        FROM "._DB_PREFIX_."cms_shop INNER JOIN "._DB_PREFIX_."mrshop_cms_page
        ON "._DB_PREFIX_."cms_shop.id_cms = "._DB_PREFIX_."mrshop_cms_page.id_cms INNER JOIN "._DB_PREFIX_."cms_lang
        ON "._DB_PREFIX_."mrshop_cms_page.id_cms = "._DB_PREFIX_."cms_lang.id_cms
        WHERE "._DB_PREFIX_."mrshop_cms_page.id_cms = ".(int) $id." AND "._DB_PREFIX_."cms_lang.id_lang = ".(int) $id_lang." AND "._DB_PREFIX_."cms_shop.id_shop = ".(int) $this->context->shop->id;
        $res = Db::getInstance()->executeS($query);
        $out = $this->popCmsPage($res);

        return $out[0];
    }

    public function countCmsPages()
    {
        $query = "SELECT COUNT(*) as 'total'
        FROM "._DB_PREFIX_."mrshop_cms_page";
        $res = Db::getInstance()->executeS($query);

        return (int) $res[0]['total'];
    }

    private function popCmsPage($cms_pages)
    {
        $out = [];

        foreach ($cms_pages as $cms) {
            $out[] = [
                'id' => (int) $cms['id_cms_page'],
                'id_cms' => (int) $cms['id_cms'],
                'simple_cms' => (bool) $cms['simple_cms'],
                'title' => $cms['meta_title'],
                'description' => $cms['meta_description'],
                'content' => $cms['content']
            ];
        }

        return $out;
    }
}
