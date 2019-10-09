<?php

namespace Zoho\CRM\Helpers;

use zcrmsdk\crm\crud\ZCRMRecord as Record;
use zcrmsdk\crm\crud\ZCRMAttachment as Attachment;
use zcrmsdk\crm\crud\ZCRMInventoryLineItem as LineItem;


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
        if ($record->getLineItems()) {
            $response['Product_Details'] = self::getLineItems($record->getLineItems());
        }

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
     * Get Quotes, Invoices, SalesOrder Line Items
     */
    public static function getLineItems($lineItems)
    {
        $response = [];
        foreach ($lineItems as $index => $lineItem) {
            if ($lineItem instanceof LineItem) {
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
     * @param  Array             $array [description]
     * @return boolean        [description]
     */
    public static function isMultidimensionalArray($array)
    {
        if (is_array($array)) {
            $isMultidimensional = array_filter($array, 'is_array');
            return count($isMultidimensional) > 0;
        }
        return false;
    }

    /**
     * Get Attachment Data
     * @param  Attachment    $zcrmAttachment        Attachment Object
     * @return Array         $response              Response in Array format   
     */
    public static function getAttachmentData(Attachment $zcrmAttachment)
    {
        $response = [];
        $parentRecord = $zcrmAttachment->getParentRecord();
        $createdBy = $zcrmAttachment->getCreatedBy();
        $modifiedBy = $zcrmAttachment->getModifiedBy();
        $owner = $zcrmAttachment->getOwner();
        $response = [
            'id' => $zcrmAttachment->getId(),
            'name' => $zcrmAttachment->getFileName(),
            'type' => $zcrmAttachment->getFileType(),
            'size' => $zcrmAttachment->getSize(),
            'parent' => [
                'module' => $zcrmAttachment->getParentModule(),
                'entity_id' => $parentRecord->getEntityId(),
                'id' => $zcrmAttachment->getParentId(),
                'name' => $zcrmAttachment->getParentName(),
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
            'created_time' => $zcrmAttachment->getCreatedTime(),
            'modified_time' => $zcrmAttachment->getModifiedTime(),
        ];
        return $response;
    }
}
