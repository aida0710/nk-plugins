<?php

namespace bbo51dog\limitedworld;

use bbo51dog\limitedworld\task\CheckPositionTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    public const CHECK_INTERVAL = 30;
    public const TELEPORT_INTERVAL = 15;

    protected function onEnable(): void {
        $config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, [
            "worlds" => [
                "world" => [
                    "x1" => 15000,
                    "x2" => -15000,
                    "z1" => 15000,
                    "z2" => -15000,
                ],
            ],
        ]);
        $config->save();
        $data = $config->getAll();
        if (empty($data["worlds"])) {
            $this->getLogger()->error("Worlds data not found");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $worlds = [];
        foreach ($data["worlds"] as $name => $worldData) {
            if (empty($worldData["x1"]) || empty($worldData["x2"]) || empty($worldData["z1"]) || empty($worldData["z2"])) {
                $this->getLogger()->error("Invalid data");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
            $worlds[] = new WorldProperty($name, $worldData["x1"], $worldData["x2"], $worldData["z1"], $worldData["z2"]);
        }
        $this->getScheduler()->scheduleRepeatingTask(new CheckPositionTask($this->getScheduler(), $worlds), self::CHECK_INTERVAL * 20);
    }
}