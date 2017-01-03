<?php
/**
 * MIT License.
 *
 * Copyright (c) 2016 DropFan <DropFan@Gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author    DropFan <DropFan@Gmail.com>
 * @copyright 2016 DropFan.
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version 0.1
 *
 * @link https://github.com/DropFan/onesignal-server-api
 */

namespace OneSignalApi\Models;

abstract class BaseModel
{
    public $fields = [];

    public $bodyParams = [];

    public function __construct(array $arr)
    {
        $this->setFields($arr)->setBodyParams($arr);
    }

    public function setFields(array $arr)
    {
        foreach ($arr as $k => $v) {
            if (array_key_exists($k, $this->fields)) {
                $this->fields[$k] = $v;
            }
        }

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setBodyParams(array $arr)
    {
        foreach ($arr as $k => $v) {
            if (array_key_exists($k, $this->bodyParams)) {
                $this->bodyParams[$k] = $v;
            }
        }

        return $this;
    }

    public function getBodyParams()
    {
        return $this->bodyParams;
    }

    public function getAllFields($include_empty = false)
    {
        $all = array_merge($this->fields, $this->bodyParams);
        if (!$include_empty) {
            foreach ($all as $k => $v) {
                if (empty($v)) {
                    unset($all[$k]);
                }
            }
        }

        return $all;
    }

    public function __toString()
    {
        $all = $this->getAllFields();

        return json_encode($all, JSON_NUMERIC_CHECK);
    }

    public function __get(string $name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        if (isset($this->bodyParams[$name])) {
            return $this->bodyParams[$name];
        }
    }

    public function __set(string $name, $value)
    {
        if (isset($this->fields[$name])) {
            $this->fields[$name] = $value;
        }
        if (isset($this->bodyParams[$name])) {
            $this->bodyParams[$name] = $value;
        }

        return $value;
    }
}
