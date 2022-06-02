<?php

namespace lazyperson0710\blockLogger\task;

use lazyperson0710\blockLogger\event\PlayerEvent;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class LogDataSaveTask extends Task {

    public function onRun(): void {
        if (empty(PlayerEvent::getInstance()->getBlockLogTemp())) return;
        //$totalTime = microtime(true);
        Server::getInstance()->getAsyncPool()->submitTask(new AsyncLogDataWriteTask());
        //$totalTime = microtime(true) - $totalTime;
        //$totalTime = sprintf("%.7f", $totalTime);
        //echo "合計出力時間 : {$totalTime}\n";
    }
}