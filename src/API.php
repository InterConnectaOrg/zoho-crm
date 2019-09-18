<?php

namespace Zoho\CRM;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient as RESTClient;

use zcrmsdk\crm\crud\ZCRMModule as Module;
use zcrmsdk\crm\crud\ZCRMRecord as Record;

use zcrmsdk\crm\setup\users\ZCRMUser as User;
use zcrmsdk\crm\exception\ZCRMException;

class API
{
    use Helpers\Util;

    /**
     * [protected description]
     * @var [type]
     */
    protected $restClient;

    /**
     * [__construct description]
     * @param string $credentialsStorageMode [description]
     * @param [type] $credentialsTableName   [description]
     */
    public function __construct($credentials)
    {
        RESTClient::initialize($credentials);
        $this->restClient = RESTClient::getInstance();
    }

    /**
     * [getRecords description]
     * @param  [type]  $module   [description]
     * @param  array   $params   [description]
     * @param  boolean $expanded [description]
     * @return [type]            [description]
     */
    public function getRecords($module, $params = [])
    {
        try {
            $customViewId = isset($params['customViewId']) ? $params['customViewId'] : null;
            $sortBy = isset($params['sortBy']) ? $params['sortBy'] : null;
            $sortOrder = isset($params['sortOrder']) ? $params['sortOrder'] : null;
            $page = isset($params['page']) ? $params['page'] : 1;
            $perPage = isset($params['perPage']) ? $params['perPage'] : 200;

            $moduleInstance = $this->restClient->getModuleInstance($module);
            $response = $moduleInstance->getRecords($customViewId, $sortBy, $sortOrder, $page, $perPage);

            $records = $response->getData();
            $parsedRecords = self::parseRecords($records);

            return [
                'records' => $parsedRecords
            ];
        } catch (ZCRMException $e) {
            return [
                'info' =>[
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ],
                'records' => []
            ];
        }
    }

    /**
     * [searchRecords description]
     * @param  [type] $module   [description]
     * @param  [type] $criteria [description]
     * @param  array  $params   [description]
     * @return [type]           [description]
     */
    public function searchRecords($module, $criteria, $params = [])
    {
        try {
            $page = isset($params['page']) ? $params['page'] : 1;
            $perPage = isset($params['perPage']) ? $params['perPage'] : 200;

            $moduleInstance = $this->restClient->getModuleInstance($module);
            $response = $moduleInstance->searchRecordsByCriteria(self::buildCriteria($criteria), $page, $perPage);

            $records = $response->getData();
            $info = $response->getInfo();

            $parsedRecords = self::parseRecords($records);

            return [
                'records' => $parsedRecords,
                'info' => [
                    'more_records' => $info->getMoreRecords(),
                    'count' => $info->getRecordCount(),
                    'page' => $info->getPageNo(),
                    'per_page' => $info->getPerPage(),
                ],
            ];
        } catch (ZCRMException $e) {
            return [
                'info' =>[
                    'count' => 0,
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ],
                'records' => []
            ];
        }
    }
}
