<?php

namespace deceitya\miningtools\task;

use deceitya\miningtools\setting\MiningToolSettings;
use pocketmine\scheduler\Task;

class SettingDataSaveTask extends Task {

    public function onRun(): void {
        MiningToolSettings::getInstance()->dataSave();
    }
}