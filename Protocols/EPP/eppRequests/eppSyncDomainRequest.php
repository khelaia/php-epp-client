<?php

namespace Metaregistrar\EPP;

use DateTime;
use SimpleXMLElement;

class eppSyncDomainRequest extends eppRequest {

    private $domainName;
    private $expMonthDay;

    public function __construct($domainName, $expMonthDay) {
        parent::__construct();
        $this->domainName = $domainName;
        $this->expMonthDay = $expMonthDay;
        $this->build();
    }

    public function build() {

        $update = $this->createElement('update');
        $this->getCommand()->appendChild($update);

        // <domain:update> element
        $domainUpdate = $this->createElement('domain:update');
        $domainUpdate->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $domainUpdate->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $domainUpdate->setAttribute('xsi:schemaLocation', 'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd');
        $update->appendChild($domainUpdate);

        // <domain:name> element
        $domainName = $this->createElement('domain:name', $this->domainName);
        $domainUpdate->appendChild($domainName);

        // <domain:chg> element
        $domainChg = $this->createElement('domain:chg');
        $domainUpdate->appendChild($domainChg);

        // <extension> element
        $extension = $this->getExtension();

        $tld = substr(strrchr($this->domainName, '.'), 1);
        $namestoreExt = $this->createElement('namestoreExt:namestoreExt');
        $namestoreExt->setAttribute('xmlns:namestoreExt', 'http://www.verisign-grs.com/epp/namestoreExt-1.1');
        $namestoreExt->appendChild($this->createElement('namestoreExt:subProduct', strtoupper($tld)));
        $extension->appendChild($namestoreExt);

        // <sync:update> element
        $syncUpdate = $this->createElement('sync:update');
        $syncUpdate->setAttribute('xmlns:sync', 'http://www.verisign.com/epp/sync-1.0');
        $syncUpdate->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $syncUpdate->setAttribute('xsi:schemaLocation', 'http://www.verisign.com/epp/sync-1.0 sync-1.0.xsd');
        $extension->appendChild($syncUpdate);

        // <sync:expMonthDay> element
        $expMonthDay = $this->createElement('sync:expMonthDay', $this->expMonthDay);
        $syncUpdate->appendChild($expMonthDay);

    }

}
