<?php

namespace lazyperson0710\Gacha;

use lazyperson0710\Gacha\command\GachaCommand;
use lazyperson0710\Gacha\database\GachaItemAPI;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {

    use SingletonTrait;

    public function onEnable(): void {
        GachaItemAPI::getInstance()->init();
        $this->getServer()->getCommandMap()->registerAll("Gacha", [
            new GachaCommand(),
        ]);
        if ($this->checkChance() === false) {
            throw new \Error("Gacha : 確率が100%でないガチャが存在する為、プラグインを停止します");
        }
    }

    public function checkChance(): bool {
        foreach (GachaItemAPI::Category as $category) {
            $result = 0;
            $probability = GachaItemAPI::getInstance()->rankProbability[$category][0];
            foreach ($probability as $value) {
                $result = bcadd($result, $value, 2);
            }
            if ((float)$result !== 100.0) {
                throw new \Error($category . "の確率が合計" . $result . "%になっています");
            }
        }
        $this->getLogger()->info("正常に確率が計算されました");
        return true;
    }

}