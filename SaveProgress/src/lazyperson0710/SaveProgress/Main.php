<?php

namespace lazyperson0710\SaveProgress;

use lazyperson0710\ticket\SaveTask;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public static string $DataFolder;

    protected function onEnable(): void {
        self::$DataFolder = $this->getDataFolder();
        $this->getServer()->getPluginManager()->registerEvents(new QuitEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new JoinEvent(), $this);
        $this->getServer()->getCommandMap()->registerAll("SaveProgress", [
            //コマンドを追加
        ]);
        $this->getScheduler()->scheduleRepeatingTask(new SaveTask(), 20 * 60);
    }

    protected function onDisable(): void {
        //todo all data save
    }
}
