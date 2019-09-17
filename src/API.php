<?php

namespace Zoho\CRM;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient as RESTClient;

use zcrmsdk\crm\crud\ZCRMModule as Module;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMRecord as Record;
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
    protected $zohoRestClient;

    /**
     * [__construct description]
     * @param string $credentialsStorageMode [description]
     * @param [type] $credentialsTableName   [description]
     */
    public function __construct($credentials)
    {
        ZCRMRestClient::initialize($credentials);
        $this->zohoRestClient = ZCRMRestClient::getInstance();
    }

    /**
     * [getRecords description]
     * @param  [type]  $module   [description]
     * @param  array   $params   [description]
     * @param  boolean $expanded [description]
     * @return [type]            [description]
     */
    public function getRecords($module, $params = [], $expanded = false)
    {
        try {
            $response = [];
            $cvId = isset($params['cvId']) ? $params['cvId'] : null; // custom view
            $sortBy = isset($params['sortBy']) ? $params['sortBy'] : null;
            $sortOrder = isset($params['sortOrder']) ? $params['sortOrder'] : null;
            $page = isset($params['page']) ? $params['page'] : 1;
            $perPage = isset($params['perPage']) ? $params['perPage'] : 200;

            if ($expanded) {
                $moreRecords = true;
                while ($moreRecords) {
                    $moduleInstance = $this->zohoRestClient->getModuleInstance($module);
                    $bulkResponse = $moduleInstance->getRecords($cvId,$sortBy,$sortOrder,$page,$perPage);

                    $zcrmRecords = $bulkResponse->getData(); // $bulkResponse->getData(): return array of ZCRMRecord instances
                    $zcrmRequestInfo = $bulkResponse->getInfo();

                    foreach ($zcrmRecords as $idx => $zcrmRecord) {
                        // $response[$idx] = ZCRMUtil::getZcrmRecordData($zcrmRecord);
                        array_push($response, $this->getZcrmRecordData($zcrmRecord));
                    }
                    $moreRecords = $zcrmRequestInfo->getMoreRecords();
                    $page++;
                }
                return [
                    'records' => $response,
                    'count' => count($response)
                ];
            }

            $moduleInstance = ZCRMModule::getInstance($module);
            $bulkResponse = $moduleInstance->getRecords($cvId,$sortBy,$sortOrder,$page,$perPage);

            $zcrmRecords = $bulkResponse->getData(); // $bulkResponse->getData(): return array of ZCRMRecord instances
            $zcrmRequestInfo = $bulkResponse->getInfo();

            foreach ($zcrmRecords as $idx => $zcrmRecord) {
                $response[$idx] = ZCRMUtil::getZcrmRecordData($zcrmRecord);
            }
            $infoResponse = [
                'more_records' => $zcrmRequestInfo->getMoreRecords(),
                'count' => $zcrmRequestInfo->getRecordCount(),
                'page' => $zcrmRequestInfo->getPageNo(),
                'per_page' => $zcrmRequestInfo->getPerPage(),
            ];
            return [
                'records' => $response,
                'info' => $infoResponse,
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
     * [createRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @param  array  $trigger [description]
     * @return [type]          [description]
     */
    public function createRecords($module, $records, $trigger = ['workflow'])
    {
        try {
            $response = [];
            $zcrmRecords = [];
            if (is_array($records)) {
                foreach ($records as $idx => $record) {
                    $zcrmRecord = ZCRMRecord::getInstance($module, null);
                    foreach ($record as $key => $value) {
                        $zcrmRecord->setFieldValue($key, $value);
                    }
                    array_push($zcrmRecords, $zcrmRecord);
                }
                $bulkApiResponse = ZCRMModule::getInstance($module)->createRecords($zcrmRecords, $trigger);
                $entityResponses = $bulkApiResponse->getEntityResponses();
                foreach ($entityResponses as $entityResponse) {
                    array_push($response, $entityResponse->getResponseJSON());
                }
                return $response;
            }
        } catch (ZCRMException $e) {
            return [
                'info' =>[
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                    'details' => $e->getExceptionDetails()
                ],
                'records' => []
            ];
        }
    }

    /**
     * [updateRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    public function updateRecords($module, $records)
    {
        try {
            $response = [];
            $zcrmRecords = [];
            foreach ($records as $idx => $record) {
                $zcrmRecord = ZCRMRecord::getInstance($module, null);
                foreach ($record as $key => $value) {
                    $zcrmRecord->setFieldValue($key, $value);
                }
                array_push($zcrmRecords, $zcrmRecord);
            }
            $bulkApiResponse = ZCRMModule::getInstance($module)->updateRecords($zcrmRecords);
            $entityResponses = $bulkApiResponse->getEntityResponses();
            foreach ($entityResponses as $entityResponse) {
                array_push($response, $entityResponse->getResponseJSON());
            }
            return $response;
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
     * [upsertRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    public function upsertRecords($module, $records)
    {
        try {
            $response = [];
            $zcrmRecords = [];
            foreach ($records as $idx => $record) {
                $zcrmRecord = ZCRMRecord::getInstance($module, null);
                foreach ($record as $key => $value) {
                    $zcrmRecord->setFieldValue($key, $value);
                }
                array_push($zcrmRecords, $zcrmRecord);
            }
            $bulkApiResponse = ZCRMModule::getInstance($module)->upsertRecords($zcrmRecords);
            $entityResponses = $bulkApiResponse->getEntityResponses();
            foreach ($entityResponses as $entityResponse) {
                array_push($response, $entityResponse->getResponseJSON());
            }
            return $response;
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
     * [deleteRecords description]
     * @param  [type] $module [description]
     * @param  [type] $ids    [description]
     * @return [type]         [description]
     */
    public function deleteRecords($module, $ids)
    {
        try {
            $response = [];
            $zcrmRecords = [];
            $bulkApiResponse = ZCRMModule::getInstance($module)->deleteRecords($ids);
            $entityResponses = $bulkApiResponse->getEntityResponses();
            foreach ($entityResponses as $entityResponse) {
                array_push($response, $entityResponse->getResponseJSON());
            }
            return $response;
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
     * @param  [type]  $module      [description]
     * @param  [type]  $mapCriteria [description]
     * @param  array   $params      [description]
     * @param  boolean $expanded    [description]
     * @return [type]               [description]
     */
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

    /**
     * [getRelatedRecords description]
     * @param  [type] $module        [description]
     * @param  [type] $id            [description]
     * @param  [type] $relatedModule [description]
     * @return [type]                [description]
     */
    public function getRelatedRecords($module, $id, $relatedModule)
    {
        try {
            $zcrmRecords = [];
            $recordsResponse = [];
            $moduleInstance = ZCRMRecord::getInstance($module, $id);
            $bulkResponse = $moduleInstance->getRelatedListRecords($relatedModule);
            $zcrmRecords = $bulkResponse->getData();
            $zcrmRequestInfo = $bulkResponse->getInfo();

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
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ],
                'records' => []
            ];
        }
    }

    /**
     * [getRecordById description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    public function getRecordById($module, $id)
    {
        try {
            $recordResponse = [];
            $apiResponse = ZCRMModule::getInstance($module)->getRecord($id);// APIResponse Instance
            $zcrmRecord = $apiResponse->getData();// ZCRMRecord Instance
            $recordResponse = ZCRMUtil::getZcrmRecordData($zcrmRecord);
            if ($zcrmRecord->getLineItems()) {
                $recordResponse['Product_Details'] = ZCRMUtil::getZcrmLineItems($zcrmRecord->getLineItems());
            }
            $recordResponse['id'] = $id;
            return $recordResponse;
        } catch (ZCRMException $e) {
            return [
                'info' =>[
                    'count' => 0,
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                    'details' => $e->getExceptionDetails(),
                ],
                'record' => []
            ];
        }
    }

    /**
     * [createRecord description]
     * @param  [type] $module [description]
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    public function createRecord($module, $record)
    {
        try {
            $response = [];
            $zcrmRecords = [];
            $zcrmRecord = ZCRMRecord::getInstance($module, null);
            foreach ($record as $key => $value) {
                $zcrmRecord->setFieldValue($key, $value);
            }
            array_push($zcrmRecords, $zcrmRecord);
            $bulkApiResponse = ZCRMModule::getInstance($module)->createRecords($zcrmRecords);
            $entityResponses = $bulkApiResponse->getEntityResponses();
            foreach ($entityResponses as $entityResponse) {
                array_push($response, $entityResponse->getResponseJSON());
            }
            $record = array_shift($response);
            $record['id'] = $record['details']['id'] ?? '';
            return $record;
        } catch (ZCRMException $e) {
            return [
                'info' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ]
            ];
        }
    }
}
