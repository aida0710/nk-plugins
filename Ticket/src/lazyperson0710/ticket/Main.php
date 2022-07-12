<?php

namespace lazyperson0710\ticket;

use lazyperson0710\ticket\command\TicketCommand;
use lazyperson0710\ticket\EventListener\BreakEventListener;
use lazyperson0710\ticket\EventListener\JoinEventListener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new BreakEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new JoinEventListener(), $this);
        $this->getServer()->getCommandMap()->registerAll("ticketApi", [
            new TicketCommand(),
        ]);
        if (!file_exists($this->getDataFolder())) {
            mkdir($this->getDataFolder() . "TicketData.yml");
        }
        $this->getScheduler()->scheduleRepeatingTask(new SaveTask(),  20 * 60);
        TicketAPI::getInstance()->setCache($this->getDataFolder() . "TicketData.yml");
    }

    protected function onDisable(): void {
        TicketAPI::getInstance()->dataSave();
    }
}
