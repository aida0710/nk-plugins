<?php

declare(strict_types = 1);
namespace lazyperson0710\blockLogger\task;

use lazyperson0710\blockLogger\event\PlayerEvent;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use function microtime;

class LogDataSaveTask extends Task {

	public function onRun() : void {
		if (empty(PlayerEvent::getInstance()->getBlockLogTemp())) return;
		$totalTime = microtime(true);
		//echo "保存を開始しました\n";
		Server::getInstance()->getAsyncPool()->submitTask(new AsyncLogDataWriteTask($totalTime));
	}
}
