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

class URLFragmentSerializer
{
    private function serializeListOfStrings($separator, $escape, array $list)
    {
        return implode($separator, array_map(function ($item) use ($separator, $escape) {
            return str_replace($separator, $escape.$separator, $item);
        }, $list));
    }

    private function unserializeListOfStrings($separator, $escape, $str)
    {
        $list = [];
        $currentString = '';
        $escaping = false;

        // get UTF-8 chars, inspired from http://stackoverflow.com/questions/9438158/split-utf8-string-into-array-of-chars
        $arr = [];
        preg_match_all('/./u', $str, $arr);
        $chars = $arr[0];

        foreach ($chars as $char) {
            if ($escaping) {
                if ($char === $separator || $char === $escape) {
                    $currentString .= $char;
                } else {
                    $list[] = $currentString;
                    $currentString = $char;
                }
                $escaping = false;
            } else {
                if ($char === $escape) {
                    $escaping = true;
                } elseif ($char === $separator) {
                    $list[] = $currentString;
                    $currentString = '';
                } else {
                    $currentString .= $char;
                }
            }
        }

        if ($escaping) {
            $currentString .= $escape;
        }

        if ('' !== $currentString) {
            $list[] = $currentString;
        }

        return $list;
    }

    public function serialize(array $fragment)
    {
        $parts = [];
        foreach ($fragment as $key => $values) {
            array_unshift($values, $key);
            $parts[] = $this->serializeListOfStrings('-', '-', $values);
        }

        return $this->serializeListOfStrings('/', '/', $parts);
    }

    public function unserialize($str)
    {
        $fragment = [];
        $parts = $this->unserializeListOfStrings('/', '/', $str);
        foreach ($parts as $part) {
            $values = $this->unserializeListOfStrings('-', '-', $part);
            $key = array_shift($values);
            $fragment[$key] = $values;
        }

        return $fragment;
    }
}
