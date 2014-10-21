<?php
/**
* @copyright Amasty.
*/ 
class Amasty_Email_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getAvailableTemplates($asHash=true)
    {
        $result = array();
        
        $collection = Mage::getResourceModel('core/email_template_collection')
                ->load();
 
        $options    = $collection->toOptionArray();
        $defOptions = Mage_Core_Model_Email_Template::getDefaultTemplatesAsOptionsArray(); 
         
        // the same as + but to be sure   
        foreach ($defOptions as $v){
            $options[] = $v;
        }
        
        // convert to hash
        foreach ($options as $v){
            $result[$v['value']] = $v['label'];
        } 
         
        // sort by names alphabetically
        asort($result);     
        
        if (!$asHash){
            $options = array();
            
            foreach ($result as $k => $v){
                $options[] = array('value'=>$k, 'label'=>$v);
            }
            
            $result = $options;            
        }
        
        return $result;
    }

    public function loadTemplateText($templateId)
    {
        $emailTemplate = Mage::getModel('core/email_template');

        if (is_numeric($templateId)) {
            $emailTemplate->load($templateId);
        } else {
            $localeCode = Mage::getStoreConfig('general/locale/code');
            $emailTemplate->loadDefault($templateId, $localeCode);
        }

        $templateText = str_replace('{{var email_text}}', Mage::getStoreConfig('amemail/general/txt'), $emailTemplate->getTemplateText());

        return $templateText;
    }
}