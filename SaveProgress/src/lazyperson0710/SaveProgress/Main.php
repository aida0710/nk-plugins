<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public static string $DataFolder;

    protected function onEnable(): void {
        self::$DataFolder = $this->getDataFolder();
        $this->getServer()->getCommandMap()->registerAll("SaveProgress", [
            //コマンドを追加
        ]);
    }

    protected function onDisable(): void {
    }
}
