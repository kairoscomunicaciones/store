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

if (!defined('PHPEXCEL_ROOT')) {
    /**
     * @ignore
     */
    define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../');
    require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}
class PHPExcel_Settings
{
    /**    constants */
    /**    Available Zip library classes */
    const PCLZIP     = 'PHPExcel_Shared_ZipArchive';
    const ZIPARCHIVE = 'ZipArchive';

    /**    Optional Chart Rendering libraries */
    const CHART_RENDERER_JPGRAPH = 'jpgraph';

    /**    Optional PDF Rendering libraries */
    const PDF_RENDERER_TCPDF  = 'tcPDF';
    const PDF_RENDERER_DOMPDF = 'DomPDF';
    const PDF_RENDERER_MPDF   = 'mPDF';


    private static $chartRenderers = array(
        self::CHART_RENDERER_JPGRAPH,
    );

    private static $pdfRenderers = array(
        self::PDF_RENDERER_TCPDF,
        self::PDF_RENDERER_DOMPDF,
        self::PDF_RENDERER_MPDF,
    );


    /**
     * Name of the class used for Zip file management
     *    e.g.
     *        ZipArchive
     *
     * @var string
     */
    private static $zipClass = self::ZIPARCHIVE;


    /**
     * Name of the external Library used for rendering charts
     *    e.g.
     *        jpgraph
     *
     * @var string
     */
    private static $chartRendererName;

    /**
     * Directory Path to the external Library used for rendering charts
     *
     * @var string
     */
    private static $chartRendererPath;


    /**
     * Name of the external Library used for rendering PDF files
     *    e.g.
     *         mPDF
     *
     * @var string
     */
    private static $pdfRendererName;

    /**
     * Directory Path to the external Library used for rendering PDF files
     *
     * @var string
     */
    private static $pdfRendererPath;

    /**
     * Default options for libxml loader
     *
     * @var int
     */
    private static $libXmlLoaderOptions = null;

    /**
     * Set the Zip handler Class that PHPExcel should use for Zip file management (PCLZip or ZipArchive)
     *
     * @param string $zipClass    The Zip handler class that PHPExcel should use for Zip file management
     *      e.g. PHPExcel_Settings::PCLZip or PHPExcel_Settings::ZipArchive
     * @return    boolean    Success or failure
     */
    public static function setZipClass($zipClass)
    {
        if (($zipClass === self::PCLZIP) ||
            ($zipClass === self::ZIPARCHIVE)) {
            self::$zipClass = $zipClass;
            return true;
        }
        return false;
    }


    /**
     * Return the name of the Zip handler Class that PHPExcel is configured to use (PCLZip or ZipArchive)
     *    or Zip file management
     *
     * @return string Name of the Zip handler Class that PHPExcel is configured to use
     *    for Zip file management
     *    e.g. PHPExcel_Settings::PCLZip or PHPExcel_Settings::ZipArchive
     */
    public static function getZipClass()
    {
        return self::$zipClass;
    }


    /**
     * Return the name of the method that is currently configured for cell cacheing
     *
     * @return string Name of the cacheing method
     */
    public static function getCacheStorageMethod()
    {
        return PHPExcel_CachedObjectStorageFactory::getCacheStorageMethod();
    }


    /**
     * Return the name of the class that is currently being used for cell cacheing
     *
     * @return string Name of the class currently being used for cacheing
     */
    public static function getCacheStorageClass()
    {
        return PHPExcel_CachedObjectStorageFactory::getCacheStorageClass();
    }


    /**
     * Set the method that should be used for cell cacheing
     *
     * @param string $method Name of the cacheing method
     * @param array $arguments Optional configuration arguments for the cacheing method
     * @return boolean Success or failure
     */
    public static function setCacheStorageMethod($method = PHPExcel_CachedObjectStorageFactory::cache_in_memory, $arguments = array())
    {
        return PHPExcel_CachedObjectStorageFactory::initialize($method, $arguments);
    }


    /**
     * Set the locale code to use for formula translations and any special formatting
     *
     * @param string $locale The locale code to use (e.g. "fr" or "pt_br" or "en_uk")
     * @return boolean Success or failure
     */
    public static function setLocale($locale = 'en_us')
    {
        return PHPExcel_Calculation::getInstance()->setLocale($locale);
    }


    /**
     * Set details of the external library that PHPExcel should use for rendering charts
     *
     * @param string $libraryName    Internal reference name of the library
     *    e.g. PHPExcel_Settings::CHART_RENDERER_JPGRAPH
     * @param string $libraryBaseDir Directory path to the library's base folder
     *
     * @return    boolean    Success or failure
     */
    public static function setChartRenderer($libraryName, $libraryBaseDir)
    {
        if (!self::setChartRendererName($libraryName)) {
            return false;
        }
        return self::setChartRendererPath($libraryBaseDir);
    }


    /**
     * Identify to PHPExcel the external library to use for rendering charts
     *
     * @param string $libraryName    Internal reference name of the library
     *    e.g. PHPExcel_Settings::CHART_RENDERER_JPGRAPH
     *
     * @return    boolean    Success or failure
     */
    public static function setChartRendererName($libraryName)
    {
        if (!in_array($libraryName, self::$chartRenderers)) {
            return false;
        }
        self::$chartRendererName = $libraryName;

        return true;
    }


    /**
     * Tell PHPExcel where to find the external library to use for rendering charts
     *
     * @param string $libraryBaseDir    Directory path to the library's base folder
     * @return    boolean    Success or failure
     */
    public static function setChartRendererPath($libraryBaseDir)
    {
        if ((file_exists($libraryBaseDir) === false) || (is_readable($libraryBaseDir) === false)) {
            return false;
        }
        self::$chartRendererPath = $libraryBaseDir;

        return true;
    }


    /**
     * Return the Chart Rendering Library that PHPExcel is currently configured to use (e.g. jpgraph)
     *
     * @return string|NULL Internal reference name of the Chart Rendering Library that PHPExcel is
     *    currently configured to use
     *    e.g. PHPExcel_Settings::CHART_RENDERER_JPGRAPH
     */
    public static function getChartRendererName()
    {
        return self::$chartRendererName;
    }


    /**
     * Return the directory path to the Chart Rendering Library that PHPExcel is currently configured to use
     *
     * @return string|NULL Directory Path to the Chart Rendering Library that PHPExcel is
     *     currently configured to use
     */
    public static function getChartRendererPath()
    {
        return self::$chartRendererPath;
    }


    /**
     * Set details of the external library that PHPExcel should use for rendering PDF files
     *
     * @param string $libraryName Internal reference name of the library
     *     e.g. PHPExcel_Settings::PDF_RENDERER_TCPDF,
     *     PHPExcel_Settings::PDF_RENDERER_DOMPDF
     *  or PHPExcel_Settings::PDF_RENDERER_MPDF
     * @param string $libraryBaseDir Directory path to the library's base folder
     *
     * @return boolean Success or failure
     */
    public static function setPdfRenderer($libraryName, $libraryBaseDir)
    {
        if (!self::setPdfRendererName($libraryName)) {
            return false;
        }
        return self::setPdfRendererPath($libraryBaseDir);
    }


    /**
     * Identify to PHPExcel the external library to use for rendering PDF files
     *
     * @param string $libraryName Internal reference name of the library
     *     e.g. PHPExcel_Settings::PDF_RENDERER_TCPDF,
     *    PHPExcel_Settings::PDF_RENDERER_DOMPDF
     *     or PHPExcel_Settings::PDF_RENDERER_MPDF
     *
     * @return boolean Success or failure
     */
    public static function setPdfRendererName($libraryName)
    {
        if (!in_array($libraryName, self::$pdfRenderers)) {
            return false;
        }
        self::$pdfRendererName = $libraryName;

        return true;
    }


    /**
     * Tell PHPExcel where to find the external library to use for rendering PDF files
     *
     * @param string $libraryBaseDir Directory path to the library's base folder
     * @return boolean Success or failure
     */
    public static function setPdfRendererPath($libraryBaseDir)
    {
        if ((file_exists($libraryBaseDir) === false) || (is_readable($libraryBaseDir) === false)) {
            return false;
        }
        self::$pdfRendererPath = $libraryBaseDir;

        return true;
    }


    /**
     * Return the PDF Rendering Library that PHPExcel is currently configured to use (e.g. dompdf)
     *
     * @return string|NULL Internal reference name of the PDF Rendering Library that PHPExcel is
     *     currently configured to use
     *  e.g. PHPExcel_Settings::PDF_RENDERER_TCPDF,
     *  PHPExcel_Settings::PDF_RENDERER_DOMPDF
     *  or PHPExcel_Settings::PDF_RENDERER_MPDF
     */
    public static function getPdfRendererName()
    {
        return self::$pdfRendererName;
    }

    /**
     * Return the directory path to the PDF Rendering Library that PHPExcel is currently configured to use
     *
     * @return string|NULL Directory Path to the PDF Rendering Library that PHPExcel is
     *        currently configured to use
     */
    public static function getPdfRendererPath()
    {
        return self::$pdfRendererPath;
    }

    /**
     * Set options for libxml loader
     *
     * @param   int   $options   Options for libxml loader
     */
    public static function setLibXmlLoaderOptions($options = null)
    {
        if (is_null($options) && defined('LIBXML_DTDLOAD')) {
            $options = LIBXML_DTDLOAD | LIBXML_DTDATTR;
        }
        self::$libXmlLoaderOptions = $options;
    }

    /**
     * Get defined options for libxml loader.
     * Defaults to LIBXML_DTDLOAD | LIBXML_DTDATTR when not set explicitly.
     *
     * @return int Default options for libxml loader
     */
    public static function getLibXmlLoaderOptions()
    {
        if (is_null(self::$libXmlLoaderOptions) && defined('LIBXML_DTDLOAD')) {
            self::setLibXmlLoaderOptions(LIBXML_DTDLOAD | LIBXML_DTDATTR);
        } elseif (is_null(self::$libXmlLoaderOptions)) {
            self::$libXmlLoaderOptions = true;
        }
        return self::$libXmlLoaderOptions;
    }
}
