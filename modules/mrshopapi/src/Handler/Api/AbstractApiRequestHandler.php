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

namespace MrAPPs\MrShopApi\Handler\Api;

use Context;
use Customer;

use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Api\Front\SetupWS;

use MrAPPs\MrShopApi\Utils\ApiUtils;

abstract class AbstractApiRequestHandler
{
    public $resourceList = [];

    protected $ALLOWED_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'OPTIONS'];

    protected $resource;

    /** @var DataHandler */
    protected $dataHandler;

    protected $id;

    protected $method;

    protected $GET_params;

    protected $module;

    public function __construct($dataHandler)
    {
        $this->dataHandler = $dataHandler;
        $this->module = ApiUtils::getModule();
    }

    public function setupContext($resource = '')
    {
        $context = Context::getContext();
        $context->controller->ajax = true;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        if (!$this->checkResource()) {
            ResponseHandler::badRequest($this->module->l('Resource not found', 'abstractrequesthandler').': '.$resource);
        }
    }

    /**
     * Check resource validity
     *
     * @return bool
     */
    private function checkResource()
    {
        return in_array($this->resource, array_keys($this->resourceList));
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
        if (!$this->checkMethod()) {
            ResponseHandler::methodNotAllowed($this->module->l('Method not allowed', 'abstractrequesthandler'));
        }
    }

    /**
     * Check HTTP method
     *
     * @return bool
     */
    protected function checkMethod()
    {
        if (!(in_array($this->method, $this->ALLOWED_METHODS))) {
            return false;
        } elseif (isset($this->resourceList[$this->resource]['forbidden_method']) && in_array(
            $this->method,
            $this->resourceList[$this->resource]['forbidden_method']
        )) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param mixed $params
     */
    public function setGETParams($params)
    {
        $this->GET_params = $params;
    }

    abstract protected function isUserAuthorized();

    abstract protected function getUserId($throwErrorIfInvalidJwt);

    /**
     * Load resource data
     */
    public function loadData()
    {
        if ($this->needAuth() && false == $this->isUserAuthorized()) {
            ResponseHandler::unauthorized($this->module->l('Authorization Required', 'abstractrequesthandler'));
        } else {
            if ($this->needSecretCode() && false == $this->hasValidSecretCode()) {
                ResponseHandler::unauthorized($this->module->l('Authorization Required', 'abstractrequesthandler'));
            }

            $throwErrorIfInvalidJwt = !strpos($this->resource, 'refresh_token');
            $this->handleMethods($this->getUserId($throwErrorIfInvalidJwt));
        }
    }

    protected function needAuth()
    {
        return array_key_exists('auth', $this->resourceList[$this->resource])
            && $this->resourceList[$this->resource]['auth'] === true;
    }

    protected function needSecretCode()
    {
        return array_key_exists('secret_code_required', $this->resourceList[$this->resource])
            && $this->resourceList[$this->resource]['secret_code_required'] === true;
    }

    protected function hasValidSecretCode()
    {
        $secretCode = isset($this->GET_params['access_token']) ? trim($this->GET_params['access_token']) : '';

        if (empty($secretCode)) {
            $bodyParams = $this->dataHandler->getBodyParams();
            if (isset($bodyParams['access_token'])) {
                $secretCode = trim($bodyParams['access_token']);
            }
        }

        return $secretCode == trim(\Configuration::get('MRSHOP_SECRET', null, null, null, ''));
    }

    public function cacheCleanup()
    {
        if (mt_rand(1, 1000) === 1) {
            $cacheHandler = new CacheHandler($this->dataHandler);

            foreach ($this->resourceList as $dir => $info) {
                $classToLoad = $info['class'];
                /** @var BaseWS $object */
                $object = new $classToLoad($this->dataHandler);

                $cacheHandler->setResourceObject($object);
                $cacheHandler->cleanup($dir);
            }
        }
    }

    protected function handleMethods($userId = null)
    {
        if (defined('MRSHOP_DEV_MODE') && MRSHOP_DEV_MODE) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: *');
            header("Access-Control-Allow-Headers: *");
        }

        if ($this->method == 'OPTIONS') {
            exit;
        }

        $this->cacheCleanup();

        $classToLoad = $this->resourceList[$this->resource]['class'];

        if ($classToLoad == SetupWS::class &&
            ApiUtils::isAppRequested() &&
            !ApiUtils::isModuleActive(
                'mrshopapi',
                $this->dataHandler->getJsonDecodedList()
            )
        ) {
            ResponseHandler::methodNotAllowed('');
        }

        /** @var BaseWS $object */
        $object = new $classToLoad($this->dataHandler);

        if (array_key_exists('use_context_id', $this->resourceList[$this->resource])
            && $this->resourceList[$this->resource]['use_context_id']) {
            if ($this->method != 'GET' && $this->id != 'me') {
                ResponseHandler::badRequest($this->module->l('Resource not found', 'abstractrequesthandler'));
            }

            if (method_exists($object, 'getContextId')) {
                $this->id = $object->getContextId();
            } else {
                $this->id = $userId;
            }
        }

        $customClassMethod = isset($this->resourceList[$this->resource]['custom_class_method'])
            ? $this->resourceList[$this->resource]['custom_class_method']
            : null;

        if ($customClassMethod) {
            if (method_exists($object, $customClassMethod)) {
                if ($this instanceof AdminApiRequestHandler) {
                    $object->$customClassMethod();
                } else {
                    $object->$customClassMethod($userId);
                }
            } else {
                ResponseHandler::badRequest($this->module->l('Resource not found', 'abstractrequesthandler'));
            }
        }

        switch ($this->method) {
            case 'GET':
                $isDetailRequest = $this->id && method_exists($object, 'getDetail');
                $isCount = array_key_exists('count', $this->GET_params) && $this->GET_params['count'];
                $isListRequest = !$isDetailRequest && !$isCount && method_exists($object, 'getList');

                if ($isDetailRequest || $isListRequest || $isCount) {
                    $cachedFile = null;
                    $cacheEnabled = (!defined('MRSHOP_DEV_MODE') || MRSHOP_DEV_MODE == false) && $object->isCacheEnabled($isListRequest);

                    if ($cacheEnabled) {
                        $cacheHandler = new CacheHandler($this->dataHandler);
                        $cacheHandler->setResourceObject($object);
                        $identifier = $cacheHandler->getIdentifier($object, $this->GET_params, $isListRequest, $userId, $this->id);
                        $cachedFile = $cacheHandler->getCache($this->resource, $identifier);
                    }

                    if (!$cachedFile) {
                        if ($cacheEnabled) {
                            $object->setCacheParams($cacheHandler, $this->resource, $identifier);
                        }

                        if ($isDetailRequest) {
                            $object->getDetail($this->id, $userId);
                        } elseif ($isListRequest) {
                            $object->getList($this->GET_params, $userId);
                        } elseif ($isCount) {
                            $object->count($this->GET_params, $userId);
                        }
                    } else {
                        $cachedResponse = (array) json_decode($cachedFile, true);
                        if (method_exists($object, 'customResponse')) {
                            $object->customResponse($cachedResponse, $userId);
                        }

                        $object->response(true, null, $cachedResponse);
                    }
                } else {
                    ResponseHandler::badRequest($this->module->l('Resource not found', 'abstractrequesthandler'));
                }

                break;
            case 'POST':
            case 'PATCH':
            case 'PUT':
                if (method_exists($object, 'updateOrCreate')) {
                    $object->updateOrCreate($this->dataHandler->getBodyParams(), $this->id, $userId, $this->module);
                } else {
                    ResponseHandler::badRequest($this->module->l('Resource not found', 'abstractrequesthandler'));
                }

                break;
            case 'DELETE':
                if ($this->id) {
                    if (method_exists($object, 'deleteItem')) {
                        return $object->deleteItem($this->id, $userId);
                    }

                    ResponseHandler::badRequest($this->module->l('Resource not found', 'abstractrequesthandler'));
                } else {
                    ResponseHandler::error($this->module->l('Cannot perform DELETE without id', 'abstractrequesthandler'));
                }

                break;
            default:
                ResponseHandler::error($this->module->l('Bad request', 'abstractrequesthandler'));
        }
    }
}
