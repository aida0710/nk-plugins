<?php

namespace deceitya\lbi;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

class CheckChangeDateTask extends Task {

    public $plugin;
    public $date;

    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
        $this->date = date("Y/m/d");
    }

    public function onRun(): void {
        if ($this->date !== date("Y/m/d")) {
            $this->date = date("Y/m/d");
            Main::$lastBonusDateConfig->setAll([]);
            foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                $this->plugin->check($player, false, true);
            }
            Main::$lastBonusDateConfig->save();
        }
    }
}
