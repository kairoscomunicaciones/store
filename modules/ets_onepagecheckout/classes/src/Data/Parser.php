<?php
/**
 * Copyright ETS Software Technology Co., Ltd
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
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
 */

namespace Hybridauth\Data;

/**
 * Parser
 *
 * This class is used to parse plain text into objects. It's used by hybriauth adapters to converts
 * providers api responses to a more 'manageable' format.
 */
final class Parser
{
    /**
    * Decodes a string into an object.
    *
    * This method will first attempt to parse data as a JSON string (since most providers use this format)
    * then parse_str.
    *
    * @param string $raw
    *
    * @return mixed
    */
    public function parse($raw = null)
    {
        $data = $this->parseJson($raw);

        if (! $data) {
            $data = $this->parseQueryString($raw);
        }

        return $data;
    }

    /**
    * Decodes a JSON string
    *
    * @param $result
    *
    * @return mixed
    */
    public function parseJson($result)
    {
        return json_decode($result,true);
    }

    /**
    * Parses a string into variables
    *
    * @param $result
    *
    * @return \StdClass
    */
    public function parseQueryString($result)
    {
        parse_str($result, $output);

        if (! is_array($output)) {
            return $result;
        }

        $result = new \StdClass();

        foreach ($output as $k => $v) {
            $result->$k = $v;
        }

        return $result;
    }

    /**
    * needs to be improved
    */
    public function parseBirthday($birthday, $seperator)
    {
        $birthday = date_parse($birthday);
        unset($seperator);
        return array( $birthday['year'], $birthday['month'], $birthday['day'] );
    }
}
