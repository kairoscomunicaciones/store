<?php
/**
 * 2007-2022 ETS-Soft
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 * @author ETS-Soft <etssoft.jsc@gmail.com>
 * @copyright  2007-2022 ETS-Soft
 * @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

class PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip
{
    /**
     * The parent BSE
     *
     * @var PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE
     */
    private $parent;

    /**
     * Raw image data
     *
     * @var string
     */
    private $data;

    /**
     * Get the raw image data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the raw image data
     *
     * @param string
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Set parent BSE
     *
     * @param PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent BSE
     *
     * @return PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
}
