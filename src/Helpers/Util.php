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
            $isMultidimensional = array_filter($array,'is_array');
            return count($isMultidimensional) > 0;
        }
        return false;
    }
}
