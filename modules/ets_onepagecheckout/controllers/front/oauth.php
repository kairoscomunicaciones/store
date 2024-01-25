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

if (!defined('_PS_VERSION_'))
    exit;

class Ets_onepagecheckoutOauthModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        try
        {
            $hybridauth = new Hybridauth\Hybridauth($this->module->getLoginConfigs());
            if (!($providers = $hybridauth->getProviders()))
            {
                echo $this->module->closePopup();
                exit;
            }
            $storage = new Hybridauth\Storage\Session();
            if (($sProvider = $storage->get('provider')))
            {
                $hybridauth->disconnectAllAdapters($sProvider);
                $storage->clear();
            }
            if (($provider = Tools::getValue('provider', false)) && in_array($provider, $providers) && Ets_onepagecheckout::validateArray($providers))
            {
                $storage->set('provider', $provider);
            }
            if (isset($this->context->cookie->soloProvider) && $this->context->cookie->soloProvider)
            {
                $hybridauth->disconnectAllAdapters($this->context->cookie->soloProvider);
            }
            elseif ($hybridauth->getConnectedProviders())
            {
                $hybridauth->disconnectAllAdapters();
            }
            if (($provider = $storage->get('provider')))
            {
                $hybridauth->authenticate($provider);
            }
        }
        catch (Hybridauth\Exception\Exception $exception)
        {
            die(json_encode($exception->getMessage()));
        }
        
        Tools::redirectLink($this->context->link->getModuleLink($this->module->name, 'oauth', array('provider' => $provider), Tools::usingSecureMode()? true : false));
    }
}