<?php
/**
 * eWAY Rapid Purchase Request
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Purchase Request
 *
 * Creates a payment URL using eWAY's Transparent Redirect
 *
 * @link https://eway.io/api-v3/#transparent-redirect
 */
class RapidPurchaseRequest extends AbstractRequest
{
    protected $tokenPayment = false;

    public function setTokenPayment($value)
    {
        $this->tokenPayment = (bool)$value;
    }

    public function getTokenPayment()
    {
        return $this->tokenPayment;
    }

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = $this->getBaseData();
        if ($this->tokenPayment) {
            $data['Method'] = 'TokenPayment';
            if ($this->getCardReference()) {
                $data['Customer']['TokenCustomerID'] = $this->getCardReference();
            }
        } else {
            $data['Method'] = 'ProcessPayment';
        }
        $data['TransactionType'] = $this->getTransactionType();
        $data['RedirectUrl'] = $this->getReturnUrl();

        $data['Payment'] = [];
        $data['Payment']['TotalAmount'] = $this->getAmountInteger();
        $data['Payment']['InvoiceNumber'] = $this->getTransactionId();
        $data['Payment']['InvoiceDescription'] = $this->getDescription();
        $data['Payment']['CurrencyCode'] = $this->getCurrency();
        $data['Payment']['InvoiceReference'] = $this->getInvoiceReference();

        if ($this->getItems()) {
            $data['Items'] = $this->getItemData();
        }

        return $data;
    }

    public function sendData($data)
    {
        $headers = [
            'Authorization' => 'Basic ' . base64_encode($this->getApiKey() . ':' . $this->getPassword())
        ];

        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), $headers, json_encode($data));

        return $this->response = new RapidResponse($this, json_decode((string) $httpResponse->getBody(), true));
    }

    protected function getEndpoint()
    {
        return $this->getEndpointBase() . '/CreateAccessCode.json';
    }
}
