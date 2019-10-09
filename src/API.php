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
                'info' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                    'details' => $e->getExceptionDetails(),
                ],
                'records' => []
            ];
        }
    }

    /**
     * Update Records in specific Module
     *
     * @param String    $module         Module Name
     * @param Array     $records        Array of records to be updated
     * @param Array     $params         Array of parameters. Trigger ['workflow', 'approval', 'blueprint']
     * @return Array    $response
     */
    public function updateRecords($module, $records = [], $params = [])
    {
        try {
            $responseRecords = [];
            $zcrmRecords = [];
            foreach ($records as $record) {
                $zcrmRecord = Record::getInstance($module, null);
                foreach ($record as $key => $value) {
                    $zcrmRecord->setFieldValue($key, $value);
                }
                array_push($zcrmRecords, $zcrmRecord);
            }
            $moduleInstance = $this->restClient->getModuleInstance($module);
            $bulkApiResponse = $moduleInstance->updateRecords($zcrmRecords, $params);
            $entityResponses = $bulkApiResponse->getEntityResponses();
            foreach ($entityResponses as $entityResponse) {
                array_push($responseRecords, $entityResponse->getResponseJSON());
            }
            return $responseRecords;
        } catch (ZCRMException $e) {
            return [
                'code' => $e->getCode(),
                'details' => $e->getExceptionDetails(),
                'message' => $e->getMessage(),
                'exception_code' => $e->getExceptionCode(),
                'status' => 'error',
            ];
        }
    }

    /**
     * Delete Records by Ids
     *
     * @param String    $module         Module Name
     * @param Array     $ids            Array of record ids will be deleted
     * @return Array    $response
     */
    public function deleteRecords($module, $ids)
    {
        try {
            $responseRecords = [];
            $moduleInstance = $this->restClient->getModuleInstance($module);
            $bulkApiResponse = $moduleInstance->deleteRecords($ids);
            $entityResponses = $bulkApiResponse->getEntityResponses();
            foreach ($entityResponses as $entityResponse) {
                array_push($responseRecords, $entityResponse->getResponseJSON());
            }
            return $responseRecords;
        } catch (ZCRMException $e) {
            return [
                'code' => $e->getCode(),
                'details' => $e->getExceptionDetails(),
                'message' => $e->getMessage(),
                'exception_code' => $e->getExceptionCode(),
                'status' => 'error',
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
    public function searchRecords($module, $word, $params = [])
    {
        try {
            $page = isset($params['page']) ? $params['page'] : 1;
            $perPage = isset($params['perPage']) ? $params['perPage'] : 200;

            $moduleInstance = $this->restClient->getModuleInstance($module);
            $response = $moduleInstance->searchRecords($word, $page, $perPage);

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
                'info' => [
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
     * Get records related to a specific record
     * E.g (Accounts has multiple Contacts)
     *
     * @param String    $parentModule       Name of parent module
     * @param String    $parentId           Id of parent record
     * @param String    $childModule        Name of related module
     * @param Array     $params             Additional parameters
     *                                      Available keys: sortByField, sortOrder, page, perPage
     * @return Array    $response           Response in Array format
     */
    public function getRelatedRecords($parentModule, $parentId, $childModule, $params = [])
    {
        try {
            $zcrmRecords = [];
            $recordsResponse = [];
            $sortByField = $params['sortByField'] ?? null;
            $sortOrder = $params['sortOrder'] ?? null;
            $page = $params['page'] ?? 1;
            $perPage = $params['perPage'] ?? 200;

            $moduleInstance = $this->restClient->getRecordInstance($parentModule, $parentId);
            $bulkResponse = $moduleInstance->getRelatedListRecords($childModule, $sortByField, $sortOrder, $page, $perPage);
            $zcrmRecords = $bulkResponse->getData();
            $zcrmRequestInfo = $bulkResponse->getInfo();

            foreach ($zcrmRecords as $idx => $zcrmRecord) {
                $recordsResponse[$idx] = self::parseRecord($zcrmRecord);
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
                'code' => $e->getCode(),
                'details' => $e->getExceptionDetails(),
                'message' => $e->getMessage(),
                'exception_code' => $e->getExceptionCode(),
                'status' => 'error',
            ];
        }
    }

    /**
     * Upload attachment associated to specific parent record
     *
     * @param String $module    Parent Module Name
     * @param String $recordId  Record ID to upload and associate attachment
     * @param String $filePath  Absolute file path will be uploaded.
     * @return Array
     */
    public function uploadAttachment($module, $recordId, $filePath)
    {
        try {
            $recordInstance = $this->restClient->getRecordInstance($module, $recordId);
            $uploadedAttachment = $recordInstance->uploadAttachment($filePath);
            $response = [
                'http_code' => $uploadedAttachment->getHttpStatusCode(),
                'status' => $uploadedAttachment->getStatus(),
                'message' => $uploadedAttachment->getMessage(),
                'code' => $uploadedAttachment->getCode(),
                'details' => $uploadedAttachment->getDetails(),
            ];
            return $response;
        } catch (ZCRMException $e) {
            return [
                'http_code' => $e->getCode(),
                'details' => $e->getExceptionDetails(),
                'message' => $e->getMessage(),
                'code' => $e->getExceptionCode(),
                'status' => 'error',
            ];
        }
    }

    /**
     * Get Record By Id
     *
     * @param String    $module         Module Name
     * @param String    $id             Id of record will be returned
     * @return Json
     */
    public function getRecordById($module, $id)
    {
        try {
            $recordResponse = [];
            $apiResponse = $this->restClient->getModuleInstance($module)->getRecord($id); // APIResponse Instance
            $zcrmRecord = $apiResponse->getData(); // ZCRMRecord Instance
            $recordResponse = self::parseRecord($zcrmRecord);
            if ($zcrmRecord->getLineItems()) {
                $recordResponse['Product_Details'] = self::getLineItems($zcrmRecord->getLineItems());
            }
            $recordResponse['id'] = $id;
            return $recordResponse;
        } catch (ZCRMException $e) {
            return [
                'code' => $e->getCode(),
                'details' => $e->getExceptionDetails(),
                'message' => $e->getMessage(),
                'exception_code' => $e->getExceptionCode(),
                'status' => 'error',
            ];
        }
    }

    /**
     * Create Records
     *
     * @param String    $module         Module Name
     * @param Array     $records        Array of records to be updated
     * @param Array     $params         Array of parameters. Trigger [‘workflow’, ‘approval’, ‘blueprint’]
     * @return Array    $response
     */
    public function createRecords($module, $records = [], $params = [])
    {
        try {
            $responseRecords = [];
            $zcrmRecords = [];
            foreach ($records as $record) {
                $zcrmRecord = Record::getInstance($module, null);
                foreach ($record as $key => $value) {
                    $zcrmRecord->setFieldValue($key, $value);
                }
                array_push($zcrmRecords, $zcrmRecord);
            }
            $moduleInstance = $this->restClient->getModuleInstance($module);
            $bulkApiResponse = $moduleInstance->createRecords($zcrmRecords, $params);
            $entityResponses = $bulkApiResponse->getEntityResponses();
            foreach ($entityResponses as $entityResponse) {
                array_push($responseRecords, $entityResponse->getResponseJSON());
            }
            return $responseRecords;
        } catch (ZCRMException $e) {
            return [
                ‘code’ => $e->getCode(),
                ‘details’ => $e->getExceptionDetails(),
                ‘message’ => $e->getMessage(),
                ‘exception_code’ => $e->getExceptionCode(),
                ‘status’ => ‘error’,
            ];
        }
    }

    /**
     * Create Records
     *
     * @param String    $module         Module Name
     * @param Array     $id             Id of the record to fetch Attachments
     * @param Array     $params         Additional parameters
     *                                  Available keys: page, perPage
     * @return Array    $response       Response in Array format
     */
    public function getAttachments($module, $id, $params)
    {
        try {
            $page = $params['page'] ?? 1;
            $perPage = $params['perPage'] ?? 200;
            //
            $recordsResponse = [];
            $infoResponse = [];
            //
            $recordInstance = $this->restClient->getRecordInstance($module, $id);
            $bulkApiResponse = $recordInstance->getAttachments($page, $perPage);
            //
            if ($bulkApiResponse->getData()) {
                $zcrmAttachments = $bulkApiResponse->getData();
                $zcrmRequestInfo = $bulkApiResponse->getInfo();
                foreach ($zcrmAttachments as $index => $zcrmAttachment) {
                    $recordsResponse[$index] = self::getAttachmentData($zcrmAttachment);
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
            }
            return [
                'records' => [],
                'info' => [
                    'more_records' => false,
                    'count' => 0,
                ]
            ];
        } catch (ZCRMException $e) {
            return [
                'http_code' => $e->getCode(),
                'details' => $e->getExceptionDetails(),
                'message' => $e->getMessage(),
                'code' => $e->getExceptionCode(),
                'status' => 'error',
            ];
        }
    }
}
