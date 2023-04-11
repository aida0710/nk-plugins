<?php

declare(strict_types = 0);

namespace ree_jp\bank\sqlite;

use ree_jp\bank\result\ResultLog;

interface ILogHelper {

	static function getInstance() : LogHelper;

	public function isExists(string $bank) : bool;

	public function getLog(string $bank) : ResultLog;

	public function addLog(string $bank, string $message) : void;
}
