<?php
/**
* FMM Custom Fields.
*
* NOTICE OF LICENSE
*
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by FMM Modules.
*
* @author    FMM Modules
* @copyright 2022 FMM Modules All right reserved
* @license   FMM Modules
*/

class FieldsModel extends ObjectModel
{
    public $id_custom_field;

    public $field_type;

    public $field_validation;

    public $field_placement;

    public $position;

    public $default_value;

    public $value_required;

    public $editable = 1;

    public $alert_type = 'info';

    public $extensions = 'jpg';

    public $products;

    public $attachment_size = 2;

    public $show_customer;

    public $show_email;

    public $show_admin;

    public $active;

    public $created_time;

    public $update_time;

    public $field_name;

    public $assoc_shops;

    public $dependant;

    public $dependant_field;

    public $dependant_value;

    public $id_heading;

    const KB = 1024;

    const MB = 1048576;

    public static $definition = [
        'table' => 'custom_field',
        'primary' => 'id_custom_field',
        'multilang' => true,
        'fields' => [
            'active' => ['type' => self::TYPE_BOOL],
            'created_time' => ['type' => self::TYPE_DATE],
            'update_time' => ['type' => self::TYPE_DATE],
            'value_required' => ['type' => self::TYPE_BOOL],
            'position' => ['type' => self::TYPE_INT],
            'editable' => ['type' => self::TYPE_INT],
            'field_placement' => ['type' => self::TYPE_STRING],
            'field_validation' => ['type' => self::TYPE_STRING],
            'field_type' => ['type' => self::TYPE_NOTHING],
            'extensions' => ['type' => self::TYPE_STRING],
            'products' => ['type' => self::TYPE_STRING],
            'attachment_size' => ['type' => self::TYPE_FLOAT],
            'show_customer' => ['type' => self::TYPE_BOOL],
            'show_email' => ['type' => self::TYPE_BOOL],
            'show_admin' => ['type' => self::TYPE_BOOL],
            'field_name' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'],
            'default_value' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'],
            'assoc_shops' => ['type' => self::TYPE_STRING],
            'dependant' => ['type' => self::TYPE_INT],
            'dependant_field' => ['type' => self::TYPE_INT],
            'dependant_value' => ['type' => self::TYPE_INT],
            'id_heading' => ['type' => self::TYPE_INT],
        ],
    ];

    public function add($auto_date = true, $null_values = false)
    {
        if (!$this->dependant) {
            $this->dependant_field = 0;
            $this->dependant_value = 0;
        }

        return parent::add($auto_date, $null_values);
    }

    public function delete()
    {
        $res = Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'custom_field
            WHERE id_custom_field = '.(int) $this->id_custom_field);

        $res &= parent::delete();

        return $res;
    }

    public function update($null_values = false)
    {
        if (!$this->dependant) {
            $this->dependant_field = 0;
            $this->dependant_value = 0;
        }

        if (parent::update($null_values)) {
            return true;
        }

        return false;
    }

    public function deleteSelection($selection)
    {
        if (!is_array($selection)) {
            die(Tools::displayError());
        }

        $result = true;
        foreach ($selection as $id) {
            $this->id_custom_field = (int) $id;
            $result = $result && $this->delete();
        }

        return $result;
    }

    public function getCustomFieldValues($id)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'custom_field_values WHERE id_custom_field = '.pSQL((int) $id);

        return DB::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
    }

    public function getOrderFields($where)
    {
        $cookie = Context::getContext()->cookie;
        $id_lang = (int) $cookie->id_lang;
        $sql = 'SELECT a.*, b.`field_name`, val.`field_value`
            FROM '._DB_PREFIX_.'custom_field a
            INNER JOIN '._DB_PREFIX_.'custom_field_lang b ON (a.id_custom_field = b.id_custom_field AND b.id_lang = '.(int) $id_lang.')
            INNER JOIN '._DB_PREFIX_.'custom_field_userdata val ON (a.id_custom_field = val.id_custom_field)
            WHERE '.((isset($where) && $where) ? pSQL($where) : '1').' AND a.active = 1
            ORDER BY a.position ASC';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
        if (isset($result) && $result) {
            foreach ($result as &$res) {
                if (in_array($res['field_type'], ['multiselect', 'radio', 'checkbox', 'select'])) {
                    $values = (self::isSerialized($res['field_value'])) ? unserialize($res['field_value']) : $res['field_value'];
                    $opts = [];
                    if (isset($values) && is_array($values)) {
                        foreach ($values as $id_value) {
                            $opts[] = self::getFieldsValueById($id_value);
                        }
                        $res['field_value'] = (isset($opts) && $opts && is_array($opts) ? implode(',', $opts) : '');
                    } else {
                        $res['field_value'] = self::getFieldsValueById($res['field_value']);
                    }
                }
            }
        }

        return $result;
    }

    public static function fieldValidate($type, $fields, $id_cart = null)
    {
        $errors = [];
        $allfields = self::getCustomFields($type, Context::getContext()->cookie->id_lang);

        foreach ($allfields as $f) {
            if (!isset($fields[$f['id_custom_field']])) {
                if ($f['field_type'] == 'attachment') {
                    if ($id_cart) {
                        $fields[$f['id_custom_field']] = self::getUserValueByField($f['id_custom_field'], 'id_cart = '.(int) $id_cart);
                    } else {
                        $fields[$f['id_custom_field']] = null;
                    }
                } else {
                    continue;
                }
            }

            $user_field_value = $fields[$f['id_custom_field']];
            if ($f['field_type'] == 'attachment') {
                if ($f['value_required'] == 1 && empty($user_field_value) && (!isset($_FILES['fields']) || !isset($_FILES['fields']['name'][$f['id_custom_field']]) || empty($_FILES['fields']['name'][$f['id_custom_field']]))) {
                    $errors[] = $f['field_name'].' '.Module::getInstanceByName('customfields')->trans['required'];
                } elseif (isset($_FILES) && isset($_FILES['fields']['name'][$f['id_custom_field']])) {
                    $ext = pathinfo($_FILES['fields']['name'][$f['id_custom_field']], PATHINFO_EXTENSION);
                    $size = Tools::ps_round($_FILES['fields']['size'][$f['id_custom_field']] / FieldsModel::MB, 2, PS_ROUND_UP);
                    if ($ext && isset($f['extensions']) && $f['extensions'] && !in_array($ext, explode(',', $f['extensions']))) {
                        $errors[] = $f['field_name'].' '.Module::getInstanceByName('customfields')->trans['type'];
                    }
                    if ($size && isset($f['attachment_size']) && $f['attachment_size'] && $size > $f['attachment_size']) {
                        $errors[] = $f['field_name'].' '.Module::getInstanceByName('customfields')->trans['size'];
                    }
                }
            } else {
                if ($f['value_required'] == 1 && empty($user_field_value)) {
                    if ($f['dependant'] <= 0) {
                        $errors[] = $f['field_name'].' '.Module::getInstanceByName('customfields')->trans['required'];
                    } elseif ($f['dependant'] > 0) {
                        $depend_val = (int) self::getDependantCheckedValue($f['id_custom_field']);
                        $depend_field = (int) self::getDependantCheckedVal($f['id_custom_field']);
                        $existance = $fields[$depend_field];
                        if (is_array($existance)) {
                            if (in_array($depend_val, $existance)) {
                                $errors[] = $f['field_name'].' '.Module::getInstanceByName('customfields')->trans['required'];
                            }
                        } else {
                            if ($depend_val == $existance) {
                                $errors[] = $f['field_name'].' '.Module::getInstanceByName('customfields')->trans['required'];
                            }
                        }
                        //$errors[] = print_r($existance);
                    }
                }
            }

            if (array_key_exists($f['id_custom_field'], $fields) && $f['field_validation'] != '') {
                if (!call_user_func('Validate::'.$f['field_validation'], $user_field_value)) {
                    $errors[] = $f['field_name'].' '.Module::getInstanceByName('customfields')->trans['invalid'];
                }
            }
        }

        return $errors;
    }

    public static function getAllFieldsInvoice($where)
    {
        $sql = 'SELECT DISTINCT a.*, b.field_name, val.field_value
                FROM '._DB_PREFIX_.'custom_field a
                INNER JOIN '._DB_PREFIX_.'custom_field_lang b ON (a.id_custom_field = b.id_custom_field)
                INNER JOIN '._DB_PREFIX_.'custom_field_userdata val ON (a.id_custom_field = val.id_custom_field)
                WHERE '.pSQL($where).' AND a.active = 1
                GROUP BY val.field_value
                ORDER BY a.position ASC';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
    }

    public function saveFieldValues($fields, $type, $cart_id)
    {
        $errors = self::fieldValidate($type, $fields, $cart_id);
        if (count($errors)) {
            return $errors;
        }

        if (isset($_FILES) && isset($_FILES['fields']) && isset($_FILES['fields']['name']) && count($_FILES['fields']['name']) > 0) {
            $files = $_FILES['fields'];
            foreach ($files['name'] as $key => $file) {
                if ($files['error'][$key] && isset(Module::getInstanceByName('customfields')->file_errors[$files['error'][$key]])) {
                    ++$errors;
                } else {
                    $ext = Tools::substr($files['name'][$key], strrpos($files['name'][$key], '.') + 1);
                    $file_name = 'field_'.$key.'.'.$ext;

                    $file_path = _PS_UPLOAD_DIR_.Module::getInstanceByName('customfields')->name.DIRECTORY_SEPARATOR.$cart_id;
                    if (!file_exists($file_path)) {
                        @mkdir($file_path, 0777, true);
                        @copy(_PS_MODULE_DIR_.Module::getInstanceByName('customfields')->name.DIRECTORY_SEPARATOR.'index.php', $file_path.DIRECTORY_SEPARATOR.'index.php');
                    }

                    $file_path .= DIRECTORY_SEPARATOR.$key;
                    if (!file_exists($file_path)) {
                        @mkdir($file_path, 0777, true);
                        @copy(_PS_MODULE_DIR_.Module::getInstanceByName('customfields')->name.DIRECTORY_SEPARATOR.'index.php', $file_path.DIRECTORY_SEPARATOR.'index.php');
                    }

                    //remove old file
                    if (file_exists($file_path.DIRECTORY_SEPARATOR.$file_name)) {
                        @unlink($file_path.DIRECTORY_SEPARATOR.$file_name);
                    }

                    if (!move_uploaded_file($files['tmp_name'][$key], $file_path.DIRECTORY_SEPARATOR.$file_name)) {
                        ++$errors;
                    } else {
                        $field = $file_path.DIRECTORY_SEPARATOR.$file_name;
                        $sql = 'INSERT INTO '._DB_PREFIX_.'custom_field_userdata(id_custom_field, id_cart, field_value)
                        VALUES('.(int) $key.','.(int) $cart_id.',"'.pSQL($field).'")
                        ON DUPLICATE KEY UPDATE id_custom_field='.pSQL((int) $key).', id_cart='.pSQL((int) $cart_id).', field_value="'.pSQL($field, true).'"';
                        Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($sql);
                    }
                }
            }
        }

        if (!$fields) {
            return true;
        }

        foreach ($fields as $id => $field) {
            if (empty($field)) {
                continue;
            }

            if (is_array($field)) {
                $field = serialize($field);
            }

            $sql = 'INSERT INTO  '._DB_PREFIX_.'custom_field_userdata(id_custom_field, id_cart, field_value)
                VALUES ('.(int) $id.','.(int) $cart_id.',"'.pSQL($field).'")
                ON DUPLICATE KEY UPDATE id_custom_field = '.pSQL((int) $id).', id_cart='.pSQL((int) $cart_id).', field_value="'.pSQL($field, true).'"';
            Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($sql);
        }

        return true;
    }

    public static function getFieldType($id_custom_field)
    {
        if (!$id_custom_field) {
            return false;
        } else {
            return (string) Db::getInstance()->getValue('SELECT `field_type` FROM `'._DB_PREFIX_.'custom_field`
                WHERE id_custom_field = '.(int) $id_custom_field);
        }
    }

    /*
     * get custom fields
    */
    public static function getCustomFields($field_type, $id_lang = null, $id_shop = null, $id_cart = null, $and = null)
    {
        $id_customer = (int) Context::getContext()->customer->id;
        $groups = Customer::getGroupsStatic($id_customer);
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        if (!$id_cart) {
            $id_cart = (int) Context::getContext()->cookie->id_cart;
        }
        if (!$id_lang) {
            $id_lang = (int) Context::getContext()->language->id;
        }
        $sql = 'SELECT t.*,tl.*';
        if ($id_cart) {
            $sql .= ',cu.field_value';
        }

        $sql .= ' FROM '._DB_PREFIX_.'custom_field t
            LEFT JOIN '._DB_PREFIX_.'custom_field_lang tl ON (t.id_custom_field = tl.id_custom_field AND id_lang = '.(int) $id_lang.')';

        $sql .= 'LEFT JOIN '._DB_PREFIX_.'custom_field_group cg ON (t.id_custom_field = cg.id_custom_field)';

        if ($id_cart) {
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'custom_field_userdata cu ON (t.id_custom_field = cu.id_custom_field AND cu.id_cart = '.(int) $id_cart.')';
        }

        // $sql .= ' WHERE t.field_placement = "'.pSQL($field_type).'" and t.active = 1 '.(($and)? 'AND '.$and : '').'
        // AND cg.`id_group` IN ('.implode(',', array_map('intval', $groups)).') order by t.position';

        $sql .= ' WHERE t.field_placement = "'.pSQL($field_type).'" and t.active = 1 '.(($and) ? 'AND '.$and : '').'
        AND cg.`id_group` IN ('.implode(',', array_map('intval', $groups)).')
        GROUP BY t.id_custom_field
        ORDER BY t.position';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
        $final = [];
        if (isset($result)) {
            foreach ($result as &$res) {
                if ($res['assoc_shops'] && $id_shop) {
                    if (in_array($id_shop, explode(',', $res['assoc_shops']))) {
                        $final[] = $res;
                    }
                }
            }
        }

        return $final;
    }

    public function getCustomFieldsValues($id)
    {
        if (!$id) {
            return false;
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT *
            FROM '._DB_PREFIX_.'custom_field_values WHERE id_custom_field = '.(int) $id);
    }

    public static function getUserValueByField($id_custom_field, $where = null)
    {
        if (!$id_custom_field) {
            return false;
        }

        return Db::getInstance()->getValue('SELECT field_value FROM '._DB_PREFIX_.'custom_field_userdata
            WHERE id_custom_field = '.(int) $id_custom_field.(($where) ? ' AND '.$where : ''));
    }

    public static function getFieldsValueById($id_value)
    {
        if (!$id_value) {
            return false;
        }

        return Db::getInstance()->getValue('SELECT `field_value`
            FROM '._DB_PREFIX_.'custom_field_values WHERE field_value_id = '.(int) $id_value);
    }

    public function getFormatedValue($field)
    {
        if (isset($field) && isset($field['field_type']) && isset($field['field_value'])) {
            $field_type = $field['field_type'];
            $field_value = $field['field_value'];
            if ($field_value && in_array($field_type, ['multiselect', 'radio', 'checkbox'])) {
                $value = unserialize($field_value);

                return join(', ', $value);
            }

            return $field_value;
        }
    }

    public function getLastId()
    {
        return (int) Db::getInstance()->getValue('SELECT MAX(id_custom_field) FROM `'._DB_PREFIX_.'custom_field`');
    }

    public static function updateStatus($field, $id_custom_field)
    {
        return (bool) Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'custom_field`
            SET `'.pSQL($field).'` = !'.pSQL($field).' WHERE id_custom_field = '.(int) $id_custom_field);
    }

    public static function countEditableFields()
    {
        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(id_custom_field) FROM `'._DB_PREFIX_.'custom_field`
            WHERE editable = 1 AND active = 1 AND show_customer = 1');
    }

    public static function countEditableFieldValues($id_cart)
    {
        if (!$id_cart) {
            false;
        }

        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(ud.id_custom_field) FROM `'._DB_PREFIX_.'custom_field_userdata` ud
            LEFT JOIN `'._DB_PREFIX_.'custom_field` cf
                ON (cf.id_custom_field = ud.id_custom_field AND ud.id_cart = '.(int) $id_cart.')
            WHERE cf.editable = 0
            AND cf.active = 1
            AND cf.show_customer = 1
            AND ud.field_value = \'\'');
    }

    public static function countNonEditableMissigFields($id_cart)
    {
        if (!$id_cart) {
            return false;
        }

        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(id_custom_field) FROM `'._DB_PREFIX_.'custom_field`
            WHERE id_custom_field NOT IN (SELECT `id_custom_field` FROM `'._DB_PREFIX_.'custom_field_userdata` WHERE id_cart = '.(int) $id_cart.')
            AND editable = 0
            AND active = 1
            AND show_customer = 1');
    }

    public function setOrderId($id_cart, $id_order)
    {
        if (!$id_cart || !$id_order) {
            return false;
        } else {
            return (bool) Db::getInstance()->update(
                'custom_field_userdata',
                ['id_order' => (int) $id_order],
                'id_cart = '.(int) $id_cart
            );
        }
    }

    public static function getFieldLabels($id_lang = null)
    {
        if (!$id_lang) {
            $id_lang = (int) Context::getContext()->language->id;
        } else {
            $sql = new DbQuery();
            $sql->select('cf.*,cfl.field_name');
            $sql->from(self::$definition['table'], 'cf');
            $sql->leftJoin(
                self::$definition['table'].'_lang',
                'cfl',
                'cf.id_custom_field = cfl.id_custom_field AND cfl.id_lang = '.(int) $id_lang
            );
            $sql->where('cf.show_customer = 1');
            $sql->where('cf.field_type <> "message"');
            $sql->orderBy('cf.position');

            return Db::getInstance()->executeS($sql);
        }
    }

    public static function getFieldsData($where)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('custom_field_userdata');
        $sql->where($where);
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
        $final = [];
        if (isset($result) && $result) {
            foreach ($result as $res) {
                if (in_array(self::getFieldType($res['id_custom_field']), ['multiselect', 'radio', 'checkbox', 'select'])) {
                    $values = unserialize($res['field_value']);
                    $opts = [];
                    if (isset($values) && is_array($values)) {
                        foreach ($values as $id_value) {
                            $opts[] = self::getFieldsValueById($id_value);
                        }
                        $res['field_value'] = (isset($opts) && $opts && is_array($opts) ? implode(',', $opts) : '');
                    }
                }
                $final[$res['id_custom_field']] = $res;
            }
        }

        return $final;
    }

    public static function deleteByCart($id_cart)
    {
        if (!$id_cart) {
            return false;
        } else {
            $sql = 'DELETE FROM `'._DB_PREFIX_.'custom_field_userdata` WHERE id_cart = '.(int) $id_cart;
            if (Db::getInstance()->execute($sql)) {
                $dirname = _PS_UPLOAD_DIR_.Module::getInstanceByName('customfields')->name.DIRECTORY_SEPARATOR.$id_cart;

                return self::deleteDir($dirname);
            } else {
                return false;
            }
        }
    }

    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS(
            'SELECT `id_custom_field`, `position`
            FROM `'._DB_PREFIX_.'custom_field`
            WHERE id_custom_field = '.(int) Tools::getValue('id').'
            ORDER BY `position` ASC'
        )) {
            return false;
        }

        foreach ($res as $field) {
            if ((int) $field['id_custom_field'] == (int) $this->id) {
                $moved_field = $field;
            }
        }

        if (!isset($moved_field) || !isset($position)) {
            return false;
        }

        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        return Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'custom_field`
            SET `position`= `position` '.($way ? '- 1' : '+ 1').'
            WHERE `position`
            '.($way
                ? '> '.(int) $moved_field['position'].' AND `position` <= '.(int) $position
                : '< '.(int) $moved_field['position'].' AND `position` >= '.(int) $position))
        && Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'custom_field`
            SET `position` = '.(int) $position.'
            WHERE `id_custom_field` = '.(int) $moved_field['id_custom_field']);
    }

    public static function getHigherPosition()
    {
        $sql = 'SELECT MAX(`position`) FROM `'._DB_PREFIX_.'custom_field`';
        $position = DB::getInstance()->getValue($sql);

        return (is_numeric($position)) ? $position : -1;
    }

    public static function positionOccupied($position)
    {
        if (!$position) {
            return false;
        }

        $sql = 'SELECT * FROM `'._DB_PREFIX_.'custom_field` WHERE position = '.(int) $position;

        return (bool) DB::getInstance()->getRow($sql);
    }

    public static function actionDownload($full_path)
    {
        if (headers_sent()) {
            die(Tools::displayError('Headers Sent'));
        }
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        if (file_exists($full_path)) {
            $fsize = filesize($full_path);
            $path_parts = pathinfo($full_path);
            $ext = Tools::strtolower($path_parts['extension']);
            switch ($ext) {
                case 'pdf':
                    $content_type = 'application/pdf';
                    break;
                case 'exe':
                    $content_type = 'application/octet-stream';
                    break;
                case 'zip':
                    $content_type = 'application/zip';
                    break;
                case 'doc':
                    $content_type = 'application/msword';
                    break;
                case 'xls':
                    $content_type = 'application/vnd.ms-excel';
                    break;
                case 'ppt':
                    $content_type = 'application/vnd.ms-powerpoint';
                    break;
                case 'gif':
                    $content_type = 'image/gif';
                    break;
                case 'png':
                    $content_type = 'image/png';
                    break;
                case 'jpeg':
                case 'jpg':
                    $content_type = 'image/jpg';
                    break;
                default:
                    $content_type = mime_content_type($full_path);
            }
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Type: '.$content_type);
            header('Content-Disposition: attachment; filename="'.basename($full_path).'";');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: '.$fsize);
            ob_clean();
            flush();
            readfile($full_path);
        } else {
            die(Tools::displayError('File Not Found'));
        }
    }

    public static function deleteDir($dirname)
    {
        if (is_dir($dirname)) {
            $dir_handle = opendir($dirname);
        }
        if (!$dir_handle) {
            return false;
        }

        while ($file = readdir($dir_handle)) {
            if ($file != '.' && $file != '..') {
                if (!is_dir($dirname.DIRECTORY_SEPARATOR.$file)) {
                    @unlink($dirname.DIRECTORY_SEPARATOR.$file);
                } else {
                    self::deleteDir($dirname.DIRECTORY_SEPARATOR.$file);
                }
            }
        }
        closedir($dir_handle);
        @rmdir($dirname);

        return true;
    }

    public static function isSerialized($value, &$result = null)
    {
        // Bit of a give away this one
        if (!is_string($value)) {
            return false;
        }
        // Serialized false, return true. unserialize() returns false on an
        // invalid string or it could return false if the string is serialized
        // false, eliminate that possibility.
        if ($value === 'b:0;') {
            $result = false;

            return true;
        }
        $length = Tools::strlen($value);
        $end = '';
        switch ($value[0]) {
            case 's':
                if ($value[$length - 2] !== '"') {
                    return false;
                }
                // no break
            case 'b':
            case 'i':
            case 'd':
                $end .= ';';
                // no break
            case 'a':
            case 'O':
                $end .= '}';
                if ($value[1] !== ':') {
                    return false;
                }
                switch ($value[2]) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        break;
                    default:
                        return false;
                }
                // no break
            case 'N':
                $end .= ';';
                if ($value[$length - 1] !== $end[0]) {
                    return false;
                }
                break;
            default:
                return false;
        }
        if (($result = @unserialize($value)) === false) {
            $result = null;

            return false;
        }

        return true;
    }

    public static function getCustomFieldsCollection($id_lang = null, $id_shop = null, $id_cart = null, $and = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        if (!$id_cart) {
            $id_cart = (int) Context::getContext()->cookie->id_cart;
        }
        if (!$id_lang) {
            $id_lang = (int) Context::getContext()->language->id;
        }
        $sql = 'SELECT t.*, tl.`field_name`';
        if ($id_cart) {
            $sql .= ',cu.field_value';
        }

        $sql .= ' FROM '._DB_PREFIX_.'custom_field t
            LEFT JOIN '._DB_PREFIX_.'custom_field_lang tl ON (t.id_custom_field = tl.id_custom_field AND id_lang = '.(int) $id_lang.')';

        if ($id_cart) {
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'custom_field_userdata cu ON (t.id_custom_field = cu.id_custom_field AND cu.id_cart = '.(int) $id_cart.')';
        }

        $sql .= ' WHERE t.active = 1 '.(($and) ? 'AND '.$and : '').' order by t.position';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
        $final = [];
        if (isset($result)) {
            foreach ($result as &$res) {
                if ($res['assoc_shops'] && $id_shop) {
                    if (in_array($id_shop, explode(',', $res['assoc_shops']))) {
                        $final[] = $res;
                    }
                }
            }
        }

        return $final;
    }

    public static function getDependantCheckedVal($id)
    {
        return (int) Db::getInstance()->getValue('SELECT `dependant_field`
        FROM `'._DB_PREFIX_.'custom_field` WHERE `id_custom_field` = '.(int) $id);
    }

    public static function getDependantCheckedValue($id)
    {
        return (int) Db::getInstance()->getValue('SELECT `dependant_value`
        FROM `'._DB_PREFIX_.'custom_field` WHERE `id_custom_field` = '.(int) $id);
    }

    public static function dumpCurrentData($id, $table)
    {
        return Db::getInstance()->delete($table, 'id_custom_field = '.(int) $id);
    }

    public static function populateTable($table, $key, $id, $raw)
    {
        if (is_array($raw)) {
            foreach ($raw as $row) {
                Db::getInstance()->insert(
                    $table,
                    [
                        'id_custom_field' => (int) $id,
                        $key => $row
                    ]
                );
            }
        }
        $last_id = (int) Db::getInstance()->Insert_ID();

        return $last_id;
    }

    public static function needleCheck($table, $key, $id_key, $id_obj)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `id_custom_field`
            FROM `'._DB_PREFIX_.$table.'`
            WHERE `'.pSQL($key).'` = '.(int) $id_key.'
            AND `id_custom_field` = '.(int) $id_obj);
    }

    public static function needleCheckCategory($id_obj)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `id_category`
            FROM `'._DB_PREFIX_.'custom_field_categories`
            WHERE `id_custom_field` = '.(int) $id_obj);
    }

    public static function getHeadingsCollection($id_lang)
    {
        $sql = new DbQuery();
        $sql->select('a.`id_custom_field_headings`,b.`title`');
        $sql->from('custom_field_headings', 'a');
        $sql->leftJoin('custom_field_headings_lang', 'b', 'a.`id_custom_field_headings` = b.`id_custom_field_headings`');
        $sql->where('b.`id_lang` = '.(int) $id_lang);

        return Db::getInstance()->executeS($sql);
    }

    public static function getSubHeading($id, $id_lang)
    {
        $sql = new DbQuery();
        $sql->select('`title`');
        $sql->from('custom_field_headings_lang');
        $sql->where('`id_custom_field_headings` = '.(int) $id.' AND `id_lang` = '.(int) $id_lang);
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }
}
