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

class PHPExcel_Shared_Escher
{
    /**
     * Drawing Group Container
     *
     * @var PHPExcel_Shared_Escher_DggContainer
     */
    private $dggContainer;

    /**
     * Drawing Container
     *
     * @var PHPExcel_Shared_Escher_DgContainer
     */
    private $dgContainer;

    /**
     * Get Drawing Group Container
     *
     * @return PHPExcel_Shared_Escher_DgContainer
     */
    public function getDggContainer()
    {
        return $this->dggContainer;
    }

    /**
     * Set Drawing Group Container
     *
     * @param PHPExcel_Shared_Escher_DggContainer $dggContainer
     */
    public function setDggContainer($dggContainer)
    {
        return $this->dggContainer = $dggContainer;
    }

    /**
     * Get Drawing Container
     *
     * @return PHPExcel_Shared_Escher_DgContainer
     */
    public function getDgContainer()
    {
        return $this->dgContainer;
    }

    /**
     * Set Drawing Container
     *
     * @param PHPExcel_Shared_Escher_DgContainer $dgContainer
     */
    public function setDgContainer($dgContainer)
    {
        return $this->dgContainer = $dgContainer;
    }
}
