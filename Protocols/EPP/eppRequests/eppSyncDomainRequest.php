<?php

namespace Metaregistrar\EPP;

use DateTime;
use SimpleXMLElement;

class eppSyncDomainRequest extends eppDomainRequest
{

    protected $xml;
    /**
     * eppSyncDomainRequest constructor.
     * @param string $domainName Domain to be synced
     * @param DateTime $syncDate Date to sync the domain to
     * @param string $type Type of the domain request, assuming 'sync' for synchronization
     */
    function __construct($domainName, $syncDate = null, $type = 'sync') {
        // Call to parent constructor with the type
        parent::__construct($type);
        $this->setSyncDomain($domainName, $syncDate);
    }

    /**
     * Prepare the XML for the SYNC command
     * @param string $domainName
     * @param DateTime $syncDate
     */
    private function setSyncDomain($domainName, $syncDate) {
        if (!$syncDate) {
            // If no specific sync date provided, default to the 15th of the next month
            $syncDate = new DateTime('first day of next month');
            $syncDate->modify('+14 days'); // Moving to the 15th of next month
        }

        // Directly creating XML element for SYNC command
        $this->xml = new SimpleXMLElement('<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"></epp>');
        $command = $this->xml->addChild('command');
        $sync = $command->addChild('sync');
        $sync->addChild('domainName', $domainName);
        $sync->addChild('syncDate', $syncDate->format('Y-m-d'));
    }
}