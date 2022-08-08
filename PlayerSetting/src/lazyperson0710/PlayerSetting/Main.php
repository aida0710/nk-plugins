<?php

namespace lazyperson0710\PlayerSetting;

use lazyperson0710\PlayerSetting\command\CheckCommand;
use lazyperson0710\PlayerSetting\command\TestCommand;
use lazyperson0710\PlayerSetting\eventListener\JoinEventListener;
use lazyperson0710\PlayerSetting\object\PlayerDataPool;
use lazyperson0710\PlayerSetting\task\DataSaveScheduler;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    protected static string $data_path;

    public static function getDataPath(): string {
        return self::$data_path;
    }

    public function onEnable(): void {
        self::$data_path = $this->getDataFolder();
        PlayerDataPool::init();
        $this->getServer()->getCommandMap()->registerAll("playerSetting", [
            new TestCommand(),
            new CheckCommand(),
        ]);
        $this->getScheduler()->scheduleRepeatingTask(new DataSaveScheduler(), 20);//取り合えずわかりやすいように、、、
        $this->getServer()->getPluginManager()->registerEvents(new JoinEventListener(), $this);
    }
}
