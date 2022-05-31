<?php

declare(strict_types=1);
namespace lazyperson0710\blockLogger;

use lazyperson0710\blockLogger\database\DataBase;
use lazyperson0710\blockLogger\event\PlayerEvent;
use lazyperson0710\blockLogger\task\LogDataSaveTask;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    /** @var DataBase */
    private DataBase $log;
    private static Main $Main;

    public function onEnable(): void {
        self::$Main = $this;
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvent(), $this);
        //$this->getServer()->getCommandMap()->register($this->getName(), new LogCommand($this));
        $this->getScheduler()->scheduleRepeatingTask(new LogDataSaveTask(), 20 * 60);
        $this->log = new DataBase($this->getDataFolder());
    }

    public function getDatabase(): DataBase {
        return $this->log;
    }

    /* public function changeParam(Player $player) {
         if (!isset($this->data[$player->getName()]) or !$this->data[$player->getName()]) {
             $this->data[$player->getName()] = true;
             return;
         }
         $this->data[$player->getName()] = false;
     }

     public function isOn(Player $player): bool {
         if (isset($this->data[$player->getName()])) {
             return $this->data[$player->getName()];
         }
         return false;
     }*/
    public function onDisable(): void {
        $this->log->close();
    }

    public static function getInstance(): Main {
        return self::$Main;
    }

}