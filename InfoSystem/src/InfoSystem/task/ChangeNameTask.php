<?php

declare(strict_types=1);

namespace InfoSystem\task;

use InfoSystem\InfoSystem;
use pocketmine\scheduler\Task;

class ChangeNameTask extends Task {

	public array $args;

	public function __construct(array $args = []) {
		$this->args = $args;
	}

	public function onRun() : void {
		InfoSystem::getInstance()->ChangeTag(...$this->args);
	}
}
