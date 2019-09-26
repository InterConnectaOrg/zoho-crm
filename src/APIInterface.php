<?php

namespace Zoho\CRM;

interface APIInterface
{
    /**
     * [getModules description]
     * @return [type] [description]
     */
    function getModules();

    /**
     * [getFieldsByModule description]
     * @param  [type] $module [description]
     * @return [type]         [description]
     */
    function getFieldsByModule($module);

    /**
     * [getRecords description]
     * @param  [type] $module [description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    function getRecords($module, $params);

    /**
     * [createRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    function createRecords($module, $records, $params);

    /**
     * [updateRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    function updateRecords($module, $records, $params);

    /**
     * [upsertRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    function upsertRecords($module, $records);

    /**
     * [deleteRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    function deleteRecords($module, $records);

    /**
     * [searchRecords description]
     * @param  [type] $module      [description]
     * @param  [type] $mapCriteria [description]
     * @return [type]              [description]
     */
    function searchRecords($module, $mapCriteria);

    /**
     * [getRecordById description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    function getRecordById($module, $id);

    /**
     * [updateRecord description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    function updateRecord($module, $id, $record);

    /**
     * [deleteRecord description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    function deleteRecord($module, $id);

    /**
     * [getAttachments description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    function getAttachments($module, $id, $params);

    /**
     * [downloadAttachment description]
     * @param  [type] $module       [description]
     * @param  [type] $id           [description]
     * @param  [type] $attachmentId [description]
     * @return [type]               [description]
     */
    function downloadAttachment($module, $id, $attachmentId);

    /**
     * [uploadAttachment description]
     * @param  [type] $module   [description]
     * @param  [type] $id       [description]
     * @param  [type] $filePath [description]
     * @return [type]           [description]
     */
    function uploadAttachment($module, $id, $filePath);

    /**
     * [deleteAttachment description]
     * @param  [type] $module       [description]
     * @param  [type] $id           [description]
     * @param  [type] $attachmentId [description]
     * @return [type]               [description]
     */
    function deleteAttachment($module, $id, $attachmentId);
}
