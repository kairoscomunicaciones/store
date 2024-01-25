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

class PHPExcel_Style_Border extends PHPExcel_Style_Supervisor implements PHPExcel_IComparable
{
    /* Border style */
    const BORDER_NONE             = 'none';
    const BORDER_DASHDOT          = 'dashDot';
    const BORDER_DASHDOTDOT       = 'dashDotDot';
    const BORDER_DASHED           = 'dashed';
    const BORDER_DOTTED           = 'dotted';
    const BORDER_DOUBLE           = 'double';
    const BORDER_HAIR             = 'hair';
    const BORDER_MEDIUM           = 'medium';
    const BORDER_MEDIUMDASHDOT    = 'mediumDashDot';
    const BORDER_MEDIUMDASHDOTDOT = 'mediumDashDotDot';
    const BORDER_MEDIUMDASHED     = 'mediumDashed';
    const BORDER_SLANTDASHDOT     = 'slantDashDot';
    const BORDER_THICK            = 'thick';
    const BORDER_THIN             = 'thin';

    /**
     * Border style
     *
     * @var string
     */
    protected $borderStyle = PHPExcel_Style_Border::BORDER_NONE;

    /**
     * Border color
     *
     * @var PHPExcel_Style_Color
     */
    protected $color;

    /**
     * Parent property name
     *
     * @var string
     */
    protected $parentPropertyName;

    /**
     * Create a new PHPExcel_Style_Border
     *
     * @param    boolean    $isSupervisor    Flag indicating if this is a supervisor or not
     *                                    Leave this value at default unless you understand exactly what
     *                                        its ramifications are
     * @param    boolean    $isConditional    Flag indicating if this is a conditional style or not
     *                                    Leave this value at default unless you understand exactly what
     *                                        its ramifications are
     */
    public function __construct($isSupervisor = false, $isConditional = false)
    {
        // Supervisor?
        parent::__construct($isSupervisor);

        // Initialise values
        $this->color    = new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK, $isSupervisor);

        // bind parent if we are a supervisor
        if ($isSupervisor) {
            $this->color->bindParent($this, 'color');
        }
    }

    /**
     * Bind parent. Only used for supervisor
     *
     * @param PHPExcel_Style_Borders $parent
     * @param string $parentPropertyName
     * @return PHPExcel_Style_Border
     */
    public function bindParent($parent, $parentPropertyName = null)
    {
        $this->parent = $parent;
        $this->parentPropertyName = $parentPropertyName;
        return $this;
    }

    /**
     * Get the shared style component for the currently active cell in currently active sheet.
     * Only used for style supervisor
     *
     * @return PHPExcel_Style_Border
     * @throws PHPExcel_Exception
     */
    public function getSharedComponent()
    {
        switch ($this->parentPropertyName) {
            case 'allBorders':
            case 'horizontal':
            case 'inside':
            case 'outline':
            case 'vertical':
                throw new PHPExcel_Exception('Cannot get shared component for a pseudo-border.');
            case 'bottom':
                return $this->parent->getSharedComponent()->getBottom();
            case 'diagonal':
                return $this->parent->getSharedComponent()->getDiagonal();
            case 'left':
                return $this->parent->getSharedComponent()->getLeft();
            case 'right':
                return $this->parent->getSharedComponent()->getRight();
            case 'top':
                return $this->parent->getSharedComponent()->getTop();
        }
    }

    /**
     * Build style array from subcomponents
     *
     * @param array $array
     * @return array
     */
    public function getStyleArray($array)
    {
        switch ($this->parentPropertyName) {
            case 'allBorders':
            case 'bottom':
            case 'diagonal':
            case 'horizontal':
            case 'inside':
            case 'left':
            case 'outline':
            case 'right':
            case 'top':
            case 'vertical':
                $key = strtolower('vertical');
                break;
        }
        return $this->parent->getStyleArray(array($key => $array));
    }

    /**
     * Apply styles from array
     *
     * <code>
     * $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getTop()->applyFromArray(
     *        array(
     *            'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
     *            'color' => array(
     *                'rgb' => '808080'
     *            )
     *        )
     * );
     * </code>
     *
     * @param    array    $pStyles    Array containing style information
     * @throws    PHPExcel_Exception
     * @return PHPExcel_Style_Border
     */
    public function applyFromArray($pStyles = null)
    {
        if (is_array($pStyles)) {
            if ($this->isSupervisor) {
                $this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($this->getStyleArray($pStyles));
            } else {
                if (isset($pStyles['style'])) {
                    $this->setBorderStyle($pStyles['style']);
                }
                if (isset($pStyles['color'])) {
                    $this->getColor()->applyFromArray($pStyles['color']);
                }
            }
        } else {
            throw new PHPExcel_Exception("Invalid style array passed.");
        }
        return $this;
    }

    /**
     * Get Border style
     *
     * @return string
     */
    public function getBorderStyle()
    {
        if ($this->isSupervisor) {
            return $this->getSharedComponent()->getBorderStyle();
        }
        return $this->borderStyle;
    }

    /**
     * Set Border style
     *
     * @param string|boolean    $pValue
     *                            When passing a boolean, FALSE equates PHPExcel_Style_Border::BORDER_NONE
     *                                and TRUE to PHPExcel_Style_Border::BORDER_MEDIUM
     * @return PHPExcel_Style_Border
     */
    public function setBorderStyle($pValue = PHPExcel_Style_Border::BORDER_NONE)
    {

        if (empty($pValue)) {
            $pValue = PHPExcel_Style_Border::BORDER_NONE;
        } elseif (is_bool($pValue) && $pValue) {
            $pValue = PHPExcel_Style_Border::BORDER_MEDIUM;
        }
        if ($this->isSupervisor) {
            $styleArray = $this->getStyleArray(array('style' => $pValue));
            $this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
        } else {
            $this->borderStyle = $pValue;
        }
        return $this;
    }

    /**
     * Get Border Color
     *
     * @return PHPExcel_Style_Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set Border Color
     *
     * @param    PHPExcel_Style_Color $pValue
     * @throws    PHPExcel_Exception
     * @return PHPExcel_Style_Border
     */
    public function setColor(PHPExcel_Style_Color $pValue = null)
    {
        // make sure parameter is a real color and not a supervisor
        $color = $pValue->getIsSupervisor() ? $pValue->getSharedComponent() : $pValue;

        if ($this->isSupervisor) {
            $styleArray = $this->getColor()->getStyleArray(array('argb' => $color->getARGB()));
            $this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
        } else {
            $this->color = $color;
        }
        return $this;
    }

    /**
     * Get hash code
     *
     * @return string    Hash code
     */
    public function getHashCode()
    {
        if ($this->isSupervisor) {
            return $this->getSharedComponent()->getHashCode();
        }
        return md5(
            $this->borderStyle .
            $this->color->getHashCode() .
            __CLASS__
        );
    }
}
