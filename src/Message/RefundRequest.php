<?php

namespace Omnipay\Eway\Message;

/**
* eWAY Refund Request
* http://api-portal.anypoint.mulesoft.com/eway/api/eway-rapid-31-api/docs/reference/refunds
*/
class RefundRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = $this->getBaseData();
        $data['Refund']['TotalAmount'] = $this->getAmountInteger();
        $data['Refund']['TransactionID'] = $this->getTransactionReference();
        $data['Refund']['InvoiceNumber'] = $this->getTransactionId();
        $data['Refund']['InvoiceDescription'] = $this->getDescription();
        $data['Refund']['CurrencyCode'] = $this->getCurrency();
        $data['Refund']['InvoiceReference'] = $this->getInvoiceReference();

        if ($this->getItems()) {
            $data['Items'] = $this->getItemData();
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, json_encode($data))
            ->setAuth($this->getApiKey(), $this->getPassword())
            ->send();

        return $this->response = new RefundResponse($this, $httpResponse->json());
    }

    public function getEndpoint()
    {
        return $this->getEndpointBase().'/DirectRefund.json';
    }
}
