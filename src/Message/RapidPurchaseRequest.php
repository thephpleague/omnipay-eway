<?php

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid 3.0 Purchase Request
 *
 * Creates a payment URL using eWAY's Transparent Redirect
 * http://api-portal.anypoint.mulesoft.com/eway/api/eway-rapid-31-api/docs/reference/transparent-redirect
 */
class RapidPurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = array();
        $data['Method'] = 'ProcessPayment';
        $data['DeviceID'] = 'https://github.com/adrianmacneil/omnipay';
        $data['CustomerIP'] = $this->getClientIp();
        $data['RedirectUrl'] = $this->getReturnUrl();
        $data['PartnerID'] = $this->getPartnerId();
        $data['TransactionType'] = $this->getTransactionType();
        $data['ShippingMethod'] = $this->getShippingMethod();

        $data['Payment'] = array();
        $data['Payment']['TotalAmount'] = $this->getAmountInteger();
        $data['Payment']['InvoiceNumber'] = $this->getTransactionId();
        $data['Payment']['InvoiceDescription'] = $this->getDescription();
        $data['Payment']['CurrencyCode'] = $this->getCurrency();
        $data['Payment']['InvoiceReference'] = $this->getInvoiceReference();

        $data['Customer'] = array();
        $card = $this->getCard();
        if ($card) {
            $data['Customer']['FirstName'] = $card->getFirstName();
            $data['Customer']['LastName'] = $card->getLastName();
            $data['Customer']['CompanyName'] = $card->getCompany();
            $data['Customer']['Street1'] = $card->getAddress1();
            $data['Customer']['Street2'] = $card->getAddress2();
            $data['Customer']['City'] = $card->getCity();
            $data['Customer']['State'] = $card->getState();
            $data['Customer']['PostalCode'] = $card->getPostCode();
            $data['Customer']['Country'] = strtolower($card->getCountry());
            $data['Customer']['Email'] = $card->getEmail();
            $data['Customer']['Phone'] = $card->getPhone();

            $data['ShippingAddress']['FirstName'] = $card->getShippingFirstName();
            $data['ShippingAddress']['LastName'] = $card->getShippingLastName();
            $data['ShippingAddress']['Street1'] = $card->getShippingAddress1();
            $data['ShippingAddress']['Street2'] = $card->getShippingAddress2();
            $data['ShippingAddress']['City'] = $card->getShippingCity();
            $data['ShippingAddress']['State'] = $card->getShippingState();
            $data['ShippingAddress']['Country'] = strtolower($card->getShippingCountry());
            $data['ShippingAddress']['PostalCode'] = $card->getShippingPostcode();
            $data['ShippingAddress']['Phone'] = $card->getShippingPhone();
        }

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

        return $this->response = new RapidResponse($this, $httpResponse->json());
    }

    public function getEndpoint()
    {
        return $this->getEndpointBase().'/CreateAccessCode.json';
    }
}
