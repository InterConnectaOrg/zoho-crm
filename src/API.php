<?php

namespace Zoho\CRM;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\crud\ZCRMModule;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\setup\users\ZCRMUser;
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
     * [getModules description]
     * @return [type] [description]
     */
    public function getModules()
    {
        $response = [];
        $notAvailableModules = ['Visits', 'Actions_Performed'];
        try {
            $modules = $this->zohoRestClient->getAllModules()->getData();
            foreach ($modules as $module)
            {
                if ($module->isApiSupported() && !in_array($module->getAPIName(), $notAvailableModules)) {
                    // $metadataModule = $instance->getModule($module->getAPIName());
                    array_push($response, [
                        'id' => $module->getId(),
                        'api_name' => $module->getAPIName(),
                        'module_name' => $module->getModuleName(),
                        'singular_label' => $module->getSingularLabel(),
                        'plural_label' => $module->getPluralLabel(),
                        'fields' => $module->getFields()
                    ]);
                }
            }
            return $response;
        } catch (ZCRMException $e) {
            return [
                'info' =>[
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ],
                'modules' => []
            ];
        }
    }

    /**
     * [getFieldsByModule description]
     * @param  [type] $module [description]
     * @return [type]         [description]
     */
    public function getFieldsByModule($module)
    {
        $response = [];
        try {
            $moduleInstance = ZCRMModule::getInstance($module);
            $fieldsResponse = $moduleInstance->getAllFields();
            $fields = $fieldsResponse->getData();
            foreach ($fields as $field) {
                $layoutPermissions = [];
                $pickListValues = [];
                $lookupField = [];
                $convertMapFields = [];

                foreach ($field->getFieldLayoutPermissions() as $permission) {
                    array_push($layoutPermissions, $permission);
                }
                foreach ($field->getPickListFieldValues() as $pickList) {
                    array_push($pickListValues, $pickList->getDisplayValue());
                }
                if ($field->getLookupField() != null) {
                    $lookupField = ['module' => $field->getLookupField()->getModule()];
                }
                array_push($response, [
                    'id' => $field->getId(),
                    'api_name' => $field->getApiName(),
                    'data_type' => $field->getDataType(),
                    'label_name' => $field->getFieldLabel(),
                    'is_mandatory' => $field->isMandatory(),
                    'is_readonly' => $field->isReadOnly(),
                    'is_visible' => $field->isVisible(),
                    'is_unique' => $field->isUniqueField() ,
                    'is_custom_field' => $field->isCustomField(),
                    'default_value' => $field->getDefaultValue(),
                    'length' => $field->getLength(),
                    'created_source' => $field->getCreatedSource(),
                    'sequence_number' => $field->getSequenceNumber(),
                    'is_business_card_supported' => $field->isBusinessCardSupported(),
                    'is_formula' => $field->isFormulaField() ,
                    'formula_return_type' => $field->getFormulaReturnType() ,
                    'formula_expression' => $field->getFormulaExpression() ,
                    'is_currency' => $field->isCurrencyField() ,
                    'currency_precision' => $field->getPrecision() ,
                    'currency_rounding_option' => $field->getRoundingOption() ,
                    'is_autonumber' => $field->isAutoNumberField() ,
                    'autonumber_prefix' => $field->getPrefix() ,
                    'autonumber_sufix' => $field->getSuffix() ,
                    'autonumber_start_number' => $field->getStartNumber() ,
                    'picklist_values' => $pickListValues ,
                    'layout_permissions' =>$layoutPermissions ,
                    'lookup_field' => $lookupField ,
                ]);
                // $convertMap = $field->getConvertMapping();
                // if ($convertMap != null) {
                //     foreach ($convertMap as $key => $value) {
                //         echo $key . ":" . $value;
                //     }
                // }
            }
            return $response;
        } catch (ZCRMException $e) {
            return [
                'info' =>[
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ],
                'fields' => []
            ];
        }
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
                    $moduleInstance = ZCRMModule::getInstance()->getModuleInstance($module);
                    $bulkResponse = $moduleInstance->getRecords($cvId,$sortBy,$sortOrder,$page,$perPage);

                    $zcrmRecords = $bulkResponse->getData(); // $bulkResponse->getData(): return array of ZCRMRecord instances
                    $zcrmRequestInfo = $bulkResponse->getInfo();

                    foreach ($zcrmRecords as $idx => $zcrmRecord) {
                        // $response[$idx] = ZCRMUtil::getZcrmRecordData($zcrmRecord);
                        array_push($response, ZCRMUtil::getZcrmRecordData($zcrmRecord));
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

    /**
     * [updateRecord description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    public function updateRecord($module, $id, $record)
    {
        try {
            $response = [];
            $zcrmRecord = ZCRMRecord::getInstance($module, $id);
            foreach ($record as $key => $value) {
                $zcrmRecord->setFieldValue($key, $value);
            }
            $entityResponse = $zcrmRecord->update();
            $apiResponse = $entityResponse->getResponseJSON();
            $response = array_shift($apiResponse['data']);
            $response['id'] = $id;
            return $response;
        } catch (ZCRMException $e) {
            return [
                'info' =>[
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
     * [deleteRecord description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    public function deleteRecord($module, $id)
    {
        try {
            $response = [];
            $zcrmRecordIns = ZCRMRecord::getInstance($module, $id);
            $entityResponse = $zcrmRecord->delete();
            $response = $entityResponse->getResponseJSON();
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
     * [convertRecord description]
     * @param  string $module     [description]
     * @param  [type] $id         [description]
     * @param  array  $params     [description]
     * @param  array  $autoParams [description]
     * @return [type]             [description]
     */
    public function convertRecord($module = 'Leads', $id, $params = [], $autoParams = [])
    {
        try {
            $defaultParams = [
                'overwrite' => true,
                'notify_lead_owner' => false,
                'notify_new_entity_owner' => false,
            ];
            $defaultParams = array_merge($defaultParams, $params);
            $record = ZCRMRecord::getInstance($module, $id);
            $dealRecord = isset($autoParams['deal']) ? ZCRMModule::getInstance('Deals')->getRecord($autoParams['deal'])->getData() : null;
            $userInstance = isset($autoParams['user']) ? ZCRMUser::getInstance($autoParams['user'], null) : null;
            $response = $record->convert($dealRecord, $userInstance, $params);
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
     * [getAttachments description]
     * @param  [type] $module   [description]
     * @param  [type] $recordId [description]
     * @param  [type] $params   [description]
     * @return [type]           [description]
     */
    public function getAttachments($module, $recordId, $params)
    {
        try {
            $zcrmRecords = [];
            $infoResponse = [];
            $recordsResponse = [];
            $params['page'] = ($params['page']) ?? 1;
            $params['perPage'] = ($params['perPage']) ?? 200;

            $bulkApiResponse = ZCRMRecord::getInstance($module, $recordId)
                                    ->getAttachments($params['page'], $params['perPage']);
            $zcrmAttachments = $bulkApiResponse->getData();
            $zcrmRequestInfo = $bulkApiResponse->getInfo();
            foreach ($zcrmAttachments as $idx => $zcrmAttachment) {
                $recordsResponse[$idx] = ZCRMUtil::getAttachmentData($zcrmAttachment);
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
                'attachments' => []
            ];
        }
    }

    /**
     * [downloadAttachment description]
     * @param  [type] $module       [description]
     * @param  [type] $recordId     [description]
     * @param  [type] $attachmentId [description]
     * @return [type]               [description]
     */
    public function downloadAttachment($module, $recordId, $attachmentId)
    {
        try {
            $record = ZCRMRecord::getInstance($module,$recordId);
            $fileResponseIns = $record->downloadAttachment($attachmentId);
            $attachmentResponse = [
                'name' => $fileResponseIns->getFileName(),
                'status' => $fileResponseIns->getHttpStatusCode(),
                'content' => $fileResponseIns->getFileContent()
            ];
            return $attachmentResponse;
            /*
                OBS: The functions that implements this method must have these lines to return the attachment file as downloadable
                response($downloadedAttachment['content'])
                        ->header('Content-Disposition', "attachment;filename*=UTF-8''". $downloadedAttachment['name'])
                        ->header('Content-Type', 'application/x-downLoad');
            */
        }
        catch (ZCRMException $e) {
            return [
                'info' =>[
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ],
                'attachments' => []
            ];
        }
    }

    /**
     * [uploadAttachment description]
     * @param  [type] $module   [description]
     * @param  [type] $recordId [description]
     * @param  [type] $filePath [description]
     * @return [type]           [description]
     */
    public function uploadAttachment($module, $recordId, $filePath)
    {
        try {
            $recordInstance = ZCRMRecord::getInstance($module, $recordId);
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
                'info' =>[
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ],
                'attachments' => []
            ];
        }
    }

    /**
     * [deleteAttachment description]
     * @param  [type] $module       [description]
     * @param  [type] $recordId     [description]
     * @param  [type] $attachmentId [description]
     * @return [type]               [description]
     */
    public function deleteAttachment($module, $recordId, $attachmentId)
    {
        try {
            $recordInstance = ZCRMRecord::getInstance($module, $recordId);
            $deletedAttachment = $recordInstance->deleteAttachment($attachmentId);
            $response = [
                'http_code' => $deletedAttachment->getHttpStatusCode(),
                'status' => $deletedAttachment->getStatus(),
                'message' => $deletedAttachment->getMessage(),
                'code' => $deletedAttachment->getCode(),
                'details' => $deletedAttachment->getDetails(),
            ];
            return $response;
        } catch(ZCRMException $e) {
            return [
                'info' =>[
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'exception_code' => $e->getExceptionCode(),
                ],
                'attachments' => []
            ];
        }
    }
}
