<?php

namespace lazyperson0710\ticket;

use lazyperson0710\ticket\command\TicketCommand;
use lazyperson0710\ticket\EventListener\BreakEventListener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new BreakEventListener(), $this);
        if (!file_exists($this->getDataFolder())) {
            mkdir($this->getDataFolder() . "TicketData.yml");
        }
        TicketAPI::init($this->getDataFolder() . "TicketData.yml");
        TicketAPI::getInstance()->setCache();
        $this->getServer()->getCommandMap()->registerAll("ticketApi", [
            new TicketCommand(),
        ]);
    }

    protected function onDisable(): void {
        TicketAPI::getInstance()->save();
    }
}
