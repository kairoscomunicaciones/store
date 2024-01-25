<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 * Description
 *
 * API to get the category product 
 */

require_once 'AppCore.php';

class AppGetCategory extends AppCore
{
    public function getPageData()
    {
        if (!(int) Tools::getValue('level_id')) {
            
            $this->content['category_details'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('level_id is missing'),
                    'AppGetCategory'
                )
            );
        }
      else if (!(int) Tools::getValue('category_id')) {
          
          $this->content['category_details'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('level_id is missing'),
                    'AppGetCategory'
                )
            );
      }
      else {
          $this->content['category_details'] = $this->getCategory();
      }
      
      $this->content['install_module'] = '';
      return $this->fetchJSONContent();
    }
    
    /**
     * Get Category 
     *
     * @return array category data
     */
    public function getCategory()
    {        
        $category = array();
        
        $sql = 'select pc.id_category, pcl.name from `'. _DB_PREFIX_ . '_category` pc inner join `'. _DB_PREFIX_ . 'category_lang` pcl on pc.id_category = pcl.id_category where pc.level_depth = '. (int) Tools::getValue('level_id').'and pc.id_parent = '.(int) Tools::getValue('category_id');
        if(!empty(Db::getInstance()->getRow($sql)))
        {
            $data = Db::getInstance()->execute($sql);
            
            foreach ($data as $key => $value)
            {
                $category['name'] = $value['name'];
                $category['id'] = $value['id_category'];
                $category++;
            }
            
        }
        
        return $category;
        
        
    }
    
    
}