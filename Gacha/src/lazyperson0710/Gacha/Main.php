<?php

namespace lazyperson710\Gacha;

use lazyperson710\Gacha\command\GachaCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    private static Main $main;

    public function onEnable(): void {
        self::$main = $this;
        $this->getServer()->getCommandMap()->registerAll("Gacha", [
            new GachaCommand(),
        ]);
    }

    /*
     * 起動時にすること
     *
     * 確率のチェックC,UC,R,SR,L
     * 構文チェック(可能なのか不明)
     *
     * あとは全部当たってから処理
     * */
    public function chanceCheck() {
        foreach (json_decode(file_get_contents(Main::getInstance()->getDataFolder() . "series.json"), true) as $content) {

        }
    }

    public static function getInstance(): Main {
        return self::$main;
    }

}