<?php
use Symfony\Component\Translation\TranslatorInterface;
class CustomerAddressForm extends CustomerAddressFormCore
{
  /*
    * module: rkrregistroampliado
    * date: 2022-11-07 00:40:42
    * version: 2.0
    */
    public $module_enabled_and_configured = false;
  /*
    * module: rkrregistroampliado
    * date: 2022-11-07 00:40:42
    * version: 2.0
  
    public function __construct(
      Smarty $smarty,
      Language $language,
      TranslatorInterface $translator,
      CustomerAddressPersister $persister,
      CustomerAddressFormatter $formatter
  ) {
      parent::__construct(
          $smarty,
          $language,
          $translator,
          $persister,
          $formatter
      );
      $this->language = $language;
      $this->persister = $persister;
  }

    * module: rkrregistroampliado
    * date: 2022-11-07 00:40:42
    * version: 2.0
    */
    public function getTemplateVariables()
  {
      $context = Context::getContext();
      if (!$this->formFields) {
          $this->formFields = $this->formatter->getFormat();
      }
      $this->setValue('token', $this->persister->getToken());
      $formFields = array_map(
          function (FormField $item) {
              return $item->toArray();
          },
          $this->formFields
      );
      $datoPhone = Db::getInstance()->executeS('
          SELECT cc.name, cr.reponse
          FROM '._DB_PREFIX_.'rkr_ra_campos cc
          INNER JOIN '._DB_PREFIX_.'rkr_reponses cr
          ON cr.id_champ = cc.id
          WHERE cr.id_customer = '.$context->customer->id
      );
      if (empty($formFields['firstname']['value'])) {
          $formFields['firstname']['value'] = $context->customer->firstname;
      }
      if (empty($formFields['lastname']['value'])) {
          $formFields['lastname']['value'] = $context->customer->lastname;
      }
      for($i=0; $i<count($datoPhone);$i++){
        if($datoPhone[$i]['name'] == 'Address'){
          $formFields['address1']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'Address Complement'){
          $formFields['address2']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'Zip/Postal Code'){
          $formFields['postcode']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'City'){
          $formFields['city']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'Other'){
          $formFields['other']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'Phone'){
          $formFields['phone']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'Mobile phone'){
          $formFields['phone_mobile']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'VAT number'){
          $formFields['vat_number']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'Identification number'){
          $formFields['dni']['value'] = $datoPhone[$i]["reponse"];
        }
        elseif($datoPhone[$i]['name'] == 'Company'){
          $formFields['company']['value'] = $datoPhone[$i]["reponse"];
        }
      }
      return array(
          'id_address' => (isset($this->address->id)) ? $this->address->id : 0,
          'action' => $this->action,
          'errors' => $this->getErrors(),
          'formFields' => $formFields,
      );
  }
}
