<?php

namespace lazyperson0710\PlayerSetting\task;

use lazyperson0710\PlayerSetting\object\PlayerDataPool;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\SettingsJson;
use pocketmine\scheduler\Task;

class DataSaveScheduler extends Task {

    public function onRun(): void {
        SettingsJson::getInstance()->save(PlayerSettingPool::getInstance());
    }
}