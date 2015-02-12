<?php

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * eWAY Rapid Shared Purchase Response
 */
class RapidSharedResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isRedirect()
    {
        return isset($this->data['SharedPaymentUrl']);
    }

    public function getRedirectUrl()
    {
        return isset($this->data['SharedPaymentUrl']) ? $this->data['SharedPaymentUrl'] : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
