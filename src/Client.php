<?php

namespace Zoho\CRM;

class Client
{
    use Helpers\Credentials;
    /**
     * [protected description]
     * @var [type]
     */
    protected $client;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->client = new API($this->getAllCredentials());
    }

    /**
     * [getModules description]
     * @return [type] [description]
     */
    public function getModules()
    {
        return $this->client->getModules();
    }

    /**
     * [getFieldsByModule description]
     * @param  [type] $module [description]
     * @return [type]         [description]
     */
    public function getFieldsByModule($module)
    {
        return $this->client->getFieldsByModule($module);
    }

    /**
     * [getLayoutsByModule description]
     * @param  [type] $module [description]
     * @return [type]         [description]
     */
    public function getLayoutsByModule($module)
    {
        return $this->client->getLayoutsByModule($module);
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
        return $this->client->getRecords($module, $params, $expanded);
    }

    /**
     * [createRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    public function createRecords($module, $records, $params = [])
    {
        return $this->client->createRecords($module, $records, $params);
    }

    /**
     * [updateRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    public function updateRecords($module, $records, $params = [])
    {
        return $this->client->updateRecords($module, $records, $params);
    }

    /**
     * [upsertRecords description]
     * @param  [type] $module  [description]
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    public function upsertRecords($module, $records)
    {
        return $this->client->upsertRecords($module, $records);
    }

    /**
     * [deleteRecords description]
     * @param  [type] $module [description]
     * @param  [type] $ids    [description]
     * @return [type]         [description]
     */
    public function deleteRecords($module, $ids)
    {
        return $this->client->deleteRecords($module, $ids);
    }

    /**
     * [convertRecord description]
     * @param  [type] $module     [description]
     * @param  [type] $id         [description]
     * @param  array  $params     [description]
     * @return [type]             [description]
     */
    public function convertRecord($module, $id, $params = [])
    {
        return $this->client->convertRecord($module, $id, $params);
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
        return $this->client->searchRecords($module, $mapCriteria, $params, $expanded);
    }

    /**
     * [getRelatedRecords description]
     * @param  [type] $module        [description]
     * @param  [type] $id            [description]
     * @param  [type] $relatedModule [description]
     * @return [type]                [description]
     */
    public function getRelatedRecords($module, $id, $relatedModule, $params)
    {
        return $this->client->getRelatedRecords($module, $id, $relatedModule, $params);
    }

    /**
     * [getRecordById description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    public function getRecordById($module, $id)
    {
        return $this->client->getRecordById($module, $id);
    }

    /**
     * [createRecord description]
     * @param  [type] $module [description]
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    public function createRecord($module, $record)
    {
        return $this->client->createRecord($module, $record);
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
        return $this->client->updateRecord($module, $id, $record);
    }

    /**
     * [deleteRecord description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    public function deleteRecord($module, $id)
    {
        return $this->client->deleteRecord($module, $id);
    }

    /**
     * [getAttachments description]
     * @param  [type] $module [description]
     * @param  [type] $id     [description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function getAttachments($module, $id, $params)
    {
        return $this->client->getAttachments($module, $id, $params);
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
        return $this->client->downloadAttachment($module, $recordId, $attachmentId);
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
        return $this->client->uploadAttachment($module, $recordId, $filePath);
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
        return $this->client->deleteAttachment($module, $recordId, $attachmentId);
    }

    /**
     * [getAllProfiles description]
     * @param  [type] $orgName       [description]
     * @param  [type] $orgId     [description]
     * @return [type]               [description]
     */
    public function getAllProfiles($orgName, $orgId)
    {

        return $this->client->getAllProfiles($orgName, $orgId);
    }

    /**
     * [getAllProfiles description]
     * @param  [type] $orgName       [description]
     * @param  [type] $orgId        [description]
     * @param String $profileId     [descriptio]
     * @return [type]               [description]
     */
    public function getProfileById($orgName, $orgId, $profileId)
    {

        return $this->client->getProfileById($orgName, $orgId, $profileId);
    }

    /**
     * [getAllUsers description]
     * @param  [type] $orgName       [description]
     * @param  [type] $orgId        [description]
     * @return [type]               [description]
     */
    public function getAllUsers($orgName, $orgId)
    {
        return $this->client->getAllUsers($orgName, $orgId);
    }

    /**
     * [createNote description]
     * @param  [type] $module       [description]
     * @param  [type] $parentId        [description]
     * @param  [type] $notes        [description]
     * @return [type]               [description]
     */
    public function createNotes($module, $parentId, $notes)
    {
        return $this->client->createNotes($module, $parentId, $notes);
    }

    /**
     * [delete note description]
     * @param  [type] $module       [description]
     * @param  [type] $recordId        [description]
     * @param  [type] $noteId        [description]
     * @return [type]               [description]
     */
    public function deleteNote($module, $recordId, $noteId)
    {
        return $this->client->deleteNote($module, $recordId, $noteId);
    }
}
