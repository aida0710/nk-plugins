<?php

namespace lazyperson0710\Gacha;

use lazyperson0710\Gacha\command\GachaCommand;
use lazyperson0710\Gacha\database\GachaItemAPI;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    private static Main $main;

    public function onEnable(): void {
        self::$main = $this;
        GachaItemAPI::getInstance()->init();
        $this->getServer()->getCommandMap()->registerAll("Gacha", [
            new GachaCommand(),
        ]);
        if ($this->checkChance() === false) {
            $this->getLogger()->critical("確率が100%でないガチャが存在する為、プラグインを停止します");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function checkChance(): bool {
        foreach (GachaItemAPI::Category as $category) {
            $probability = GachaItemAPI::getInstance()->rankProbability[$category][0];
            $result = $probability["C"] + $probability["UC"] + $probability["R"] + $probability["SR"] + $probability["L"];
            if ((float)$result !== 100.0) {
                $this->getLogger()->critical("{$category}の確率が合計{$result}%になっています");
                return false;
            }
        }
        $this->getLogger()->info("正常に確率が計算されました");
        return true;
    }

    public static function getInstance(): Main {
        return self::$main;
    }

}