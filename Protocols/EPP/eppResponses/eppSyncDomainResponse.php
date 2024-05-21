<?php
namespace Metaregistrar\EPP;

use SimpleXMLElement;

class eppSyncDomainResponse extends eppResponse {


    /**
     * eppSyncDomainResponse constructor.
     * Parse the XML response on instantiation.
     * @param string $response XML response from the server
     * @throws \Exception
     */
    public function __construct($response) {
        parent::__construct($response);
    }
}
