<?php
/**
 * eWAY Rapid Shared Page Update Card Request
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Shared Page Update Card Request
 *
 * Creates a payment URL using eWAY's Responsive Shared Page
 *
 * @link https://eway.io/api-v3/#responsive-shared-page
 */
class RapidSharedUpdateCardRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('returnUrl');

        $data = $this->getBaseData();
        $data['Method'] = 'CreateTokenCustomer';
        $data['TransactionType'] = 'Purchase';
        $data['RedirectUrl'] = $this->getReturnUrl();

        // Shared page parameters (optional)
        $data['CancelUrl'] = $this->getCancelUrl();
        $data['LogoUrl'] = $this->getLogoUrl();
        $data['HeaderText'] = $this->getHeaderText();
        $data['Language'] = $this->getLanguage();
        $data['CustomerReadOnly'] = $this->getCustomerReadOnly();
        $data['CustomView'] = $this->getCustomView();

        $data['Payment'] = array();
        $data['Payment']['TotalAmount'] = 0;

        $data['Customer'] = array();
        $data['Customer']['TokenCustomerID'] = $this->getTokenCustomerId();

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, json_encode($data))
            ->setAuth($this->getApiKey(), $this->getPassword())
            ->send();

        return $this->response = new RapidSharedResponse($this, $httpResponse->json());
    }

    protected function getEndpoint()
    {
        return $this->getEndpointBase().'/CreateAccessCodeShared.json';
    }

    public function getCancelUrl()
    {
        return $this->getParameter('cancelUrl');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancelUrl', $value);
    }

    public function getLogoUrl()
    {
        return $this->getParameter('logoUrl');
    }

    public function setLogoUrl($value)
    {
        return $this->setParameter('logoUrl', $value);
    }

    public function getHeaderText()
    {
        return $this->getParameter('headerText');
    }

    public function setHeaderText($value)
    {
        return $this->setParameter('headerText', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getCustomerReadOnly()
    {
        return $this->getParameter('customerReadOnly');
    }

    public function setCustomerReadOnly($value)
    {
        return $this->setParameter('customerReadOnly', $value);
    }

    public function getCustomView()
    {
        return $this->getParameter('customView');
    }

    public function setCustomView($value)
    {
        return $this->setParameter('customView', $value);
    }

    public function getVerifyCustomerPhone()
    {
        return $this->getParameter('verifyCustomerPhone');
    }

    public function setVerifyCustomerPhone($value)
    {
        return $this->setParameter('verifyCustomerPhone', $value);
    }

    public function getVerifyCustomerEmail()
    {
        return $this->getParameter('verifyCustomerEmail');
    }

    public function setVerifyCustomerEmail($value)
    {
        return $this->setParameter('verifyCustomerEmail', $value);
    }

    public function getTokenCustomerId()
    {
        return $this->getParameter('tokenCustomerId');
    }

    public function setTokenCustomerId($value)
    {
        return $this->setParameter('tokenCustomerId', $value);
    }
}
