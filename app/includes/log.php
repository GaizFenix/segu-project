<?php

class log {
    private $logFile;

    public function __construct($logFile = 'log.txt') {
        $this->logFile = $logFile;
    }

    public function logRequest($username, $requestType, $request) {
        $date = date('Y-m-d H:i:s');
        $logEntry = "[$date] [$username] [$requestType] $request" . PHP_EOL;
        $this->writeLog($logEntry);
    }

    public function logError($username, $errorMessage) {
        $date = date('Y-m-d H:i:s');
        $logEntry = "[$date] [$username] [ERROR] $errorMessage" . PHP_EOL;
        $this->writeLog($logEntry);
    }

    private function writeLog($logEntry) {
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}

?>