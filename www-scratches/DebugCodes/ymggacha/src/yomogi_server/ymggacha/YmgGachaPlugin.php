<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\TaskScheduler;
use ymggacha\src\yomogi_server\ymggacha\command\CommandList;
use ymggacha\src\yomogi_server\ymggacha\item\HardHastePowder;
use ymggacha\src\yomogi_server\ymggacha\item\HastePowder;
use ymggacha\src\yomogi_server\ymggacha\item\YomogiGachaTicket;
use ymggacha\src\yomogi_server\ymggacha\listener\ListenerList;

class YmgGachaPlugin extends PluginBase {

    private static ?TaskScheduler $taskScheduler = null;

    public static function getTaskScheduler() : ?TaskScheduler {
        return self::$taskScheduler;
    }

    protected function onEnable() : void {
        self::$taskScheduler = $this->getScheduler();
        $this->registerCommand();
        $this->registerItems();
        $this->registerListeners();
    }

    private function registerCommand() : void {
        try {
            PacketHooker::register($this);
        } catch (HookAlreadyRegistered $err) {
            $logger = $this->getLogger();
            $logger->error('already registered hook, by ' . $this->getName());
            $logger->error($err->getMessage());
            $logger->error($err->getTraceAsString());
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        (new CommandList($this))->registerToMap($this->getServer()->getCommandMap());
    }

    private function registerItems() : void {
        $factory = ItemFactory::getInstance();
        $factory->register(new HastePowder());
        $factory->register(new HardHastePowder());
        $factory->register(new YomogiGachaTicket());
    }

    private function registerListeners() : void {
        foreach ((new ListenerList())->getAll() as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $this);
        }
    }
}
