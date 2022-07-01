<?php

namespace lazyperson0710\Gacha;

use lazyperson0710\Gacha\command\GachaCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    private array $allData;

    private array $gachaNameAll = [];
    private static Main $main;

    public function onEnable(): void {
        self::$main = $this;
        $this->saveResource('series.json');
        $this->getServer()->getCommandMap()->registerAll("Gacha", [
            new GachaCommand(),
        ]);
        $this->registerConfigData();
        if ($this->checkChance() === false) {
            $this->getLogger()->critical("確率が100%でないガチャが存在する為、プラグインを停止します");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function registerConfigData() {
        $this->allData = json_decode(file_get_contents($this->getDataFolder() . "series.json"), true);
        foreach ($this->allData as $key => $contents) {
            $this->gachaNameAll[] = [$key => $contents["name"]];
        }
    }

    public function checkChance(): bool {
        foreach ($this->gachaNameAll as $key => $name) {
            $rank = $this->allData[$key]["rank"];
            $result = $rank["C"] + $rank["UC"] + $rank["R"] + $rank["SR"] + $rank["L"];
            if ((float)$result !== 100.0) {
                $this->getLogger()->critical("{$name}の確率が合計{$result}%になっています");
                return false;
            }
        }
        $this->getLogger()->info("正常に確率が計算されました");
        return true;
    }

    public function getAllData(): array {
        return $this->allData;
    }

    public function getGachaName(): array {
        return $this->gachaNameAll;
    }

    public static function getInstance(): Main {
        return self::$main;
    }

}