<?php

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * eWAY Rapid 3.0 Purchase Response
 */
class RapidResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isRedirect()
    {
        return isset($this->data['FormActionURL']);
    }

    public function getRedirectUrl()
    {
        return isset($this->data['FormActionURL']) ? $this->data['FormActionURL'] : null;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        if ($this->isRedirect()) {
            return array(
                'EWAY_ACCESSCODE' => $this->data['AccessCode'],
            );
        }
    }
}
