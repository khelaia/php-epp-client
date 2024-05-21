<?php

namespace Metaregistrar\EPP;

use DateTime;
use SimpleXMLElement;

class eppSyncDomainRequest extends eppDomainRequest {
    /**
     * eppSyncDomainRequest constructor.
     * @param string $domainName Domain to be synced
     * @param DateTime $syncDate Date to sync the domain to
     */
    public function __construct($domainName, $syncDate = null) {
        parent::__construct('sync');  // Ensuring the parent constructor is called
        $this->setSyncDomain($domainName, $syncDate);
    }

    /**
     * Set the domain and sync date.
     * @param string $domainName
     * @param DateTime|null $syncDate Defaults to 15th of next month if null
     */
    private function setSyncDomain($domainName, $syncDate) {
        if (!$syncDate) {
            $syncDate = new DateTime('first day of next month');
            $syncDate->modify('+14 days'); // Setting to the 15th of next month
        }
        $syncDateFormatted = $syncDate->format('Y-m-d');

        $this->addDomainUpdate($domainName, $syncDateFormatted);
    }

    /**
     * Add domain update elements to the XML.
     * @param string $domainName
     * @param string $syncDateFormatted
     */
    private function addDomainUpdate($domainName, $syncDateFormatted) {
        $this->addCommand('update');
        $this->addUpdate('domain', 'update');
        $this->addDomainName($domainName);
        $this->addExtension('syncDate', $syncDateFormatted);
    }
}