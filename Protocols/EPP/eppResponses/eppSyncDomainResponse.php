<?php
namespace Metaregistrar\EPP;

use SimpleXMLElement;

class eppSyncDomainResponse extends eppResponse {
    /**
     * SimpleXMLElement representation of the response
     * @var SimpleXMLElement
     */
    private $xml;

    /**
     * eppSyncDomainResponse constructor.
     * Parse the XML response on instantiation.
     * @param string $response XML response from the server
     * @throws \Exception
     */
    public function __construct($response) {
        parent::__construct($response);
        $this->xml = new SimpleXMLElement($response);
    }

    /**
     * Check if the sync operation was successful
     * @return bool
     */
    public function isSuccess() {
        $resultCode = $this->xml->response->result['code'];
        return in_array((string)$resultCode, ['1000', '1001']); // Common success codes in EPP
    }

    /**
     * Retrieve the result code from the response
     * @return string|null
     */
    public function getResultCode() {
        return (string)$this->xml->response->result['code'];
    }

    /**
     * Get a human-readable message from the response
     * @return string|null
     */
    public function getMessage() {
        return (string)$this->xml->response->result->msg;
    }
}
?>
