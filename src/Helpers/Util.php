<?php

namespace Zoho\CRM\Helpers;

use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMAttachment as Attachment;

trait Util
{
    /**
     * [parseRecords description]
     * @param  array  $records [description]
     * @return [type]          [description]
     */
    public static function parseRecords(array $records)
    {
        $response = [];

        foreach ($records as $record) {
            if ($record instanceof Record) {
                $response[] = self::parseRecord($record);
            }
        }

        return $response;
    }

    /**
     * [parseRecord description]
     * @param  Record $record [description]
     * @return [type]         [description]
     */
    public static function parseRecord(Record $record)
    {
        $response = [];

        $response['id'] = $record->getEntityId();
        $fields = $record->getData();

        foreach ($fields as $name => $value) {
            if ($value instanceof Record) {
                $response[$name] = [
                    'id' => $value->getEntityId(),
                    'label' => $value->getLookupLabel(),
                ];
            } else {
                $response[$name] = $value;
            }
        }

        return $response;
    }

    /**
     * [getCriteria description]
     * @param  [type] $criteriaPatternMap [description]
     * @return [type]                     [description]
     */
    public static function buildCriteria($criteria)
    {
        $field = $criteria['field'];
        $operator = $criteria['operator'];
        $value = $criteria['value'];

        return "({$field}:{$operator}:{$value})";
    }

    /**
     * [isMultidimensionalArray description]
     * @param  [type]  $array [description]
     * @return boolean        [description]
     */
    public static function isMultidimensionalArray($array)
    {
        if (is_array($array)) {
            $isMultidimensional = array_filter($array,'is_array');
            return count($isMultidimensional) > 0;
        }
        return false;
    }
}
