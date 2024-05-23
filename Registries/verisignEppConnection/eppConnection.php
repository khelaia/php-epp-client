<?php
namespace Metaregistrar\EPP;

class verisignEppConnection extends eppConnection {
    
    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::useExtension('verisign');
        $this->enableRgp();
        $this->enableDnssec();
        parent::enableLaunchphase('claims');
    }

    public function syncDomain($domainName, $newExpirationDate) {
        $xml = $this->createSyncCommandXML($domainName, $newExpirationDate);
        return $this->writeAndRead($xml);
    }

    private function createSyncCommandXML($domainName, $newExpirationDate) {
        $xml = new \DOMDocument('1.0', 'UTF-8');

        $epp = $xml->createElement('epp');
        $epp->setAttribute('xmlns', 'urn:ietf:params:xml:ns:epp-1.0');
        $epp->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $epp->setAttribute('xsi:schemaLocation', 'urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd');
        $xml->appendChild($epp);

        $command = $xml->createElement('command');
        $epp->appendChild($command);

        $sync = $xml->createElement('sync');
        $sync->setAttribute('xmlns', 'urn:ietf:params:xml:ns:sync-1.0');
        $command->appendChild($sync);

        $domain = $xml->createElement('domain');
        $sync->appendChild($domain);

        $name = $xml->createElement('name', $domainName);
        $domain->appendChild($name);

        $exDate = $xml->createElement('exDate', $newExpirationDate);
        $domain->appendChild($exDate);

        $clTRID = $xml->createElement('clTRID', 'ABC-12345');
        $command->appendChild($clTRID);

        return $xml->saveXML();
    }

}
