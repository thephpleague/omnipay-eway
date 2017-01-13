<?php
/**
 * eWAY Rapid Response
 */

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * eWAY Rapid Response
 *
 * This is the response class for Rapid Direct & Transparent Redirect (Rapid)
 *
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
            $card = $this->request->getCard();
            return array(
                'EWAY_ACCESSCODE' => $this->data['AccessCode'],
                'EWAY_CARDNAME' => $card->getFirstName() . ' ' . $card->getLastName(),
                'EWAY_CARDNUMBER' => $card->getNumber(),
                'EWAY_CARDEXPIRYMONTH' => $card->getExpiryMonth(),
                'EWAY_CARDEXPIRYYEAR' => $card->getExpiryYear(),
                'EWAY_CARDCVN' => $card->getCvv(),
            );
        }
    }

    /**
     * Get a card reference (eWAY Token), for createCard requests.
     *
     * @return string|null
     */
    public function getCardReference()
    {
        if (isset($this->data['Customer']['TokenCustomerID'])) {
            return $this->data['Customer']['TokenCustomerID'];
        }

        return null;
    }

    /**
     * Get InvoiceNumber - merchant reference for a transaction
     */
    public function getInvoiceNumber()
    {
        return $this->data['InvoiceNumber'];
    }
}
