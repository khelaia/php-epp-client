<?php

namespace Metaregistrar\EPP;

use DateTime;
use SimpleXMLElement;

class eppSyncDomainRequest extends eppDomainRequest {
    /**
     * Domain name to be synced
     * @var string
     */
    private $domainName;

    /**
     * Sync date formatted as YYYY-MM-DD
     * @var string
     */
    private $syncDateFormatted;

    /**
     * eppSyncDomainRequest constructor.
     * @param string $domainName Domain to be synced
     * @param DateTime|null $syncDate Date to sync the domain to, defaults to 15th of the next month
     */
    public function __construct($domainName, $syncDate = null) {
        parent::__construct('sync');
        $this->domainName = $domainName;
        $this->syncDateFormatted = $syncDate ? $syncDate->format('Y-m-d') : (new DateTime('first day of next month'))->modify('+14 days')->format('Y-m-d');
    }

    /**
     * Overriding a hypothetical buildCommand() method from the base class to construct the specific XML for sync command.
     */
    protected function buildCommand() {
        $update = $this->createElement('update');
        $domainUpdate = $this->createElement('domain:update');
        $domainUpdate->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');

        $nameElement = $this->createElement('domain:name', $this->domainName);
        $domainUpdate->appendChild($nameElement);

        $extension = $this->createElement('extension');
        $syncElement = $this->createElement('sync:update');
        $syncElement->setAttribute('xmlns:sync', 'urn:ietf:params:xml:ns:epp-sync-1.0');
        $syncDateElement = $this->createElement('sync:date', $this->syncDateFormatted);
        $syncElement->appendChild($syncDateElement);

        $extension->appendChild($syncElement);
        $update->appendChild($domainUpdate);
        $update->appendChild($extension);

        $this->epp->appendChild($update);
    }
}