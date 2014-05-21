<?php

namespace Omnipay\Eway\Message;

/**
* eWAY Abstract Request
*
*/
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://api.ewaypayments.com';
    protected $testEndpoint = 'https://api.sandbox.ewaypayments.com';

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getPartnerId()
    {
        return $this->getParameter('partnerId');
    }

    public function setPartnerId($value)
    {
        return $this->setParameter('partnerId', $value);
    }

    public function getTransactionType()
    {
        return $this->getParameter('transactionType');
    }

    /**
     * Sets the transaction type
     * One of "Purchase" (default), "MOTO" or "Recurring"
     */
    public function setTransactionType($value)
    {
        return $this->setParameter('transactionType', $value);
    }

    public function getShippingMethod()
    {
        return $this->getParameter('shippingMethod');
    }

    public function setShippingMethod($value)
    {
        return $this->setParameter('shippingMethod', $value);
    }

    public function getInvoiceReference()
    {
        return $this->getParameter('invoiceReference');
    }

    public function setInvoiceReference($value)
    {
        return $this->setParameter('invoiceReference', $value);
    }

    protected function getItemData()
    {
        $itemArray = array();
        $items = $this->getItems();
        if ($items) {
            foreach ($items as $item) {
                $data = array();
                $data['SKU'] = strval($item->getName());
                $data['Description'] = strval($item->getDescription());
                $data['Quantity'] = strval($item->getQuantity());
                $cost = $this->formatCurrency($item->getPrice());
                $data['UnitCost'] = strval($this->getCostInteger($cost));
                $itemArray[] = $data;
            }
        }

        return $itemArray;
    }

    protected function getCostInteger($amount)
    {
        return (int) round($amount * pow(10, $this->getCurrencyDecimalPlaces()));
    }

    public function getEndpointBase()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
