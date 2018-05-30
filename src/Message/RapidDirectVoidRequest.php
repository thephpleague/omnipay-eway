<?php
/**
 * eWAY Rapid Void Request
 */

namespace Omnipay\Eway\Message;

class RapidDirectVoidRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        $data = [];
        $data['TransactionId'] = $this->getTransactionReference();

        return $data;
    }

    protected function getEndpoint()
    {
        return $this->getEndpointBase() . '/CancelAuthorisation';
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), [
            'content-type' => 'application/json',
        ], json_encode($data));

        return $this->response = new RapidResponse($this, json_decode((string) $httpResponse->getBody(), true));
    }
}
