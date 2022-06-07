<?php

namespace lazyperson0710\blockLogger\task;

use lazyperson0710\blockLogger\event\PlayerEvent;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class LogDataSaveTask extends Task {

    public function onRun(): void {
        if (empty(PlayerEvent::getInstance()->getBlockLogTemp())) return;
        //$totalTime = microtime(true);
        //echo "保存を開始しました";
        Server::getInstance()->getAsyncPool()->submitTask(new AsyncLogDataWriteTask(/*$totalTime*/));

    }
}