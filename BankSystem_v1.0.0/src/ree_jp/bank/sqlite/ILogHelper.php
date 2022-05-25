<?php

namespace ree_jp\bank\sqlite;

use ree_jp\bank\result\ResultLog;

interface ILogHelper {

    /**
     * @return LogHelper
     */
    static function getInstance(): LogHelper;

    /**
     * @param string $bank
     * @return bool
     */
    public function isExists(string $bank): bool;

    /**
     * @param string $bank
     * @return ResultLog
     */
    public function getLog(string $bank): ResultLog;

    /**
     * @param string $bank
     * @param string $message
     */
    public function addLog(string $bank, string $message): void;
}