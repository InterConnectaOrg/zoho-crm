<?php

namespace Zoho\CRM;

use ZCRMRecord as Record;
use ZCRMAttachment as Attachment;

trait Util
{
    /**
     * [getAttachmentData description]
     * @param  Attachment $zcrmAttachment [description]
     * @return array
     */
    public static function getAttachment(Attachment $attachment)
    {
        $parentRecord = $attachment->getParentRecord();
        $createdBy = $attachment->getCreatedBy();
        $modifiedBy = $attachment->getModifiedBy();
        $owner = $attachment->getOwner();

        return [
            'id' => $attachment->getId(),
            'name' => $attachment->getFileName(),
            'type' => $attachment->getFileType(),
            'size' => $attachment->getSize(),
            'parent' => [
                'module' => $attachment->getParentModule(),
                'entity_id' => $parentRecord->getEntityId(),
                'id' => $attachment->getParentId(),
                'name' => $attachment->getParentName(),
            ],
            'created_by' => [
                'id' => $createdBy->getId(),
                'name' => $createdBy->getName(),
            ],
            'modified_by' => [
                'id' => $modifiedBy->getId(),
                'name' => $modifiedBy->getName(),
            ],
            'owner' => [
                'id' => $owner->getId(),
                'name' => $owner->getName(),
            ],
            'created_time' => $attachment->getCreatedTime(),
            'modified_time' => $attachment->getModifiedTime(),
        ];
    }

    /**
     * [getZcrmRecordData description]
     * @param  ZCRMRecord $zcrmRecord [description]
     * @return array
     */
    public static function getRecord(Record $record)
    {
        $record = $record->getData();

        $response = [];
        $response['id'] = $record->getEntityId();
        foreach ($record as $key => $value) {
            if ($value instanceof Record) {
                $response[$key] = [
                    'id' => $value->getEntityId(),
                    'label' => $value->getLookupLabel(),
                ];
            } else {
                $response[$key] = $value;
            }
        }

        return $response;
    }

    /**
     * [getZcrmLineItems description]
     * @param  [type] $zcrmLineItems [description]
     * @return [type]                [description]
     */
    public static function getLineItems($lineItems)
    {
        $response = [];
        foreach ($lineItems as $index => $lineItem) {
            $response[$index] = [
                'List_Price' => $lineItem->getListPrice(),
                'Quantity' => $lineItem->getQuantity(),
                'Description' => $lineItem->getDescription(),
                'Total' => $lineItem->getTotal(),
                'Discount' => $lineItem->getDiscount(),
                'Discount_Percentage' => $lineItem->getDiscountPercentage(),
                'Total_After_Discount' => $lineItem->getTotalAfterDiscount(),
                'Tax_Amount' => $lineItem->getTaxAmount(),
                'Net_Total' => $lineItem->getNetTotal(),
                'Line_Tax' => $lineItem->getLineTax(),
            ];

            if ($lineItem->getProduct() instanceof Record) {
                $product = $lineItem->getProduct();
                $response[$index]['Product'] = array_merge($product->getData(), [
                    'id' => $product->getEntityId(),
                    'name' => $product->getLookupLabel(),
                ]);
            }
        }

        return $response;
    }

    /**
     * [getCriteria description]
     * @param  [type] $criteriaPatternMap [description]
     * @return [type]                     [description]
     */
    public static function getCriteria($criteriaPatternMap)
    {
        $criteriaPatternArray = collect($criteriaPatternMap)
                                ->filter(function ($criteriaItem, $criteriaIndex) {
                                    return !is_string($criteriaItem);
                                })
                                ->map(function ($criteriaItem, $criteriaIndex) {
                                    if (self::isMultidimensionalArray($criteriaItem)) {
                                        return self::getCriteria($criteriaItem);
                                    }

                                    return '(' . $criteriaItem['field_name'] . ':' . $criteriaItem['search_condition'] ?? 'equals' . ':' . $criteriaItem['field_value'] . ')';
                                })
                                ->all();

        return '(' . implode(isset($criteriaPatternMap['operator']) ? $criteriaPatternMap['operator'] : '', $criteriaPatternArray) . ')';
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
