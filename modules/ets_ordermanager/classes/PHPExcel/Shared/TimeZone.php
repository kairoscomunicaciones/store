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

class PHPExcel_Shared_TimeZone
{
    /*
     * Default Timezone used for date/time conversions
     *
     * @private
     * @var    string
     */
    protected static $timezone    = 'UTC';

    /**
     * Validate a Timezone name
     *
     * @param     string        $timezone            Time zone (e.g. 'Europe/London')
     * @return     boolean                        Success or failure
     */
    public static function _validateTimeZone($timezone)
    {
        if (in_array($timezone, DateTimeZone::listIdentifiers())) {
            return true;
        }
        return false;
    }

    /**
     * Set the Default Timezone used for date/time conversions
     *
     * @param     string        $timezone            Time zone (e.g. 'Europe/London')
     * @return     boolean                        Success or failure
     */
    public static function setTimeZone($timezone)
    {
        if (self::_validateTimezone($timezone)) {
            self::$timezone = $timezone;
            return true;
        }
        return false;
    }


    /**
     * Return the Default Timezone used for date/time conversions
     *
     * @return     string        Timezone (e.g. 'Europe/London')
     */
    public static function getTimeZone()
    {
        return self::$timezone;
    }


    /**
     *    Return the Timezone transition for the specified timezone and timestamp
     *
     *    @param        DateTimeZone         $objTimezone    The timezone for finding the transitions
     *    @param        integer                 $timestamp        PHP date/time value for finding the current transition
     *    @return         array                The current transition details
     */
    private static function getTimezoneTransitions($objTimezone, $timestamp)
    {
        $allTransitions = $objTimezone->getTransitions();
        $transitions = array();
        foreach ($allTransitions as $key => $transition) {
            if ($transition['ts'] > $timestamp) {
                $transitions[] = ($key > 0) ? $allTransitions[$key - 1] : $transition;
                break;
            }
            if (empty($transitions)) {
                $transitions[] = end($allTransitions);
            }
        }

        return $transitions;
    }

    /**
     *    Return the Timezone offset used for date/time conversions to/from UST
     *    This requires both the timezone and the calculated date/time to allow for local DST
     *
     *    @param        string                 $timezone        The timezone for finding the adjustment to UST
     *    @param        integer                 $timestamp        PHP date/time value
     *    @return         integer                Number of seconds for timezone adjustment
     *    @throws        PHPExcel_Exception
     */
    public static function getTimeZoneAdjustment($timezone, $timestamp)
    {
        if ($timezone !== null) {
            if (!self::_validateTimezone($timezone)) {
                throw new PHPExcel_Exception("Invalid timezone " . $timezone);
            }
        } else {
            $timezone = self::$timezone;
        }

        if ($timezone == 'UST') {
            return 0;
        }

        $objTimezone = new DateTimeZone($timezone);
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $transitions = $objTimezone->getTransitions($timestamp, $timestamp);
        } else {
            $transitions = self::getTimezoneTransitions($objTimezone, $timestamp);
        }

        return (count($transitions) > 0) ? $transitions[0]['offset'] : 0;
    }
}
