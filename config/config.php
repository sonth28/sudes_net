<?php
class config {
    var $dbConnect;

    function _construct()
    {

    }

    function _destruct()
    {
        $this->dbConnect->close();
    }

    function connect()
    {
        $this->dbConnect = new mysqli('localhost', 'root', '', 'sudes_net');
        return $this->dbConnect;
    }

    function getStatusCodeMessage()
    {

    }
    function sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
//        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
        $status_header = 'HTTP/1.1 ' . $status;
        header($status_header);
        header('Content-type: ' . $content_type);
        echo $body;
    }
}
?>