<?php

namespace lazyperson0710\PlayerSetting\task;

use lazyperson0710\PlayerSetting\object\PlayerDataPool;
use pocketmine\scheduler\Task;

class DataSaveScheduler extends Task {

    public function onRun(): void {
        PlayerDataPool::finalize();
    }
}