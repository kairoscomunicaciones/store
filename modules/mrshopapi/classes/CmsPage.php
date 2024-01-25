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
class CmsPage extends ObjectModel
{
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'mrshop_cms_page',
        'primary' => 'id_cms_page',
        'multilang' => false,
        'fields' => [
            'id_cms' => ['type' => self::TYPE_INT, 'lang' => false, 'required' => true, 'validate' => 'isUnsignedId'],
            'simple_cms' => ['type' => self::TYPE_BOOL, 'lang' => false, 'required' => true],
        ],
    ];

    /** @var int default Cms id */
    public $id_cms;

    /** @var bool Simple cms config */
    public $simple_cms;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->id_cms = 0;
        parent::__construct($id, $id_lang, $id_shop);
    }
}
