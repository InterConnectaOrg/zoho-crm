<?php

namespace Zoho\CRM;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient as RESTClient;

use zcrmsdk\crm\crud\ZCRMModule as Module;
use zcrmsdk\crm\crud\ZCRMRecord as Record;

use zcrmsdk\crm\setup\users\ZCRMUser as User;
use zcrmsdk\crm\exception\ZCRMException;

class API implements APIInterface
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
            $response = [];

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
                'records' => $response
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

    /*
    public function searchRecords($module, $mapCriteria, $params = [], $expanded = false)
    {
        try {
            $zcrmRecords = [];
            $recordsResponse = [];
            $moreRecords = false;
            $page = isset($params['page']) ? $params['page'] : 1;
            $perPage = isset($params['perPage']) ? $params['perPage'] : 200;

            if ($expanded) {
                $moreRecords = true;
                while ($moreRecords) {
                    $bulkApiResponse = ZCRMModule::getInstance($module)
                                                ->searchRecordsByCriteria(Resolver::getCriteria($mapCriteria), $page, $perPage);
                    $zcrmRecords = $bulkApiResponse->getData(); // $bulkResponse->getData(): array of ZCRMRecord instances
                    $zcrmRequestInfo = $bulkApiResponse->getInfo();
                    foreach ($zcrmRecords as $idx => $zcrmRecord) {
                        array_push($recordsResponse, ZCRMUtil::getZcrmRecordData($zcrmRecord));
                    }
                    $moreRecords = $zcrmRequestInfo->getMoreRecords();
                    $page++;
                }
                return [
                    'records' => $recordsResponse,
                    'count' => count($recordsResponse)
                ];
            }
            $bulkApiResponse = ZCRMModule::getInstance($module)
                                            ->searchRecordsByCriteria(Resolver::getCriteria($mapCriteria), $page, $perPage);
            $zcrmRecords = $bulkApiResponse->getData(); // $bulkResponse->getData(): array of ZCRMRecord instances
            $zcrmRequestInfo = $bulkApiResponse->getInfo();
            foreach ($zcrmRecords as $idx => $zcrmRecord) {
                $recordsResponse[$idx] = ZCRMUtil::getZcrmRecordData($zcrmRecord);
            }
            $infoResponse = [
                'more_records' => $zcrmRequestInfo->getMoreRecords(),
                'count' => $zcrmRequestInfo->getRecordCount(),
                'page' => $zcrmRequestInfo->getPageNo(),
                'per_page' => $zcrmRequestInfo->getPerPage(),
            ];
            return [
                'records' => $recordsResponse,
                'info' => $infoResponse,
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
    */
}
