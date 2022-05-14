<?php

declare(strict_types=1);
namespace nkserver\ranking;

use nkserver\ranking\command\RankingCommand;
use nkserver\ranking\event\handler\EventListener;
use nkserver\ranking\libs\CortexPE\Commando\PacketHooker;
use nkserver\ranking\object\PlayerDataPool;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    protected static string $data_path;
    protected static RankingCommand $ranking_command;

    public static function getDataPath(): string {
        return self::$data_path;
    }

    public function onEnable(): void {
        if (!PacketHooker::isRegistered()) PacketHooker::register($this);
        self::$data_path = $this->getDataFolder();
        self::$ranking_command = new RankingCommand($this);
        PlayerDataPool::init();
        EventListener::init();
        $this->getServer()->getCommandMap()->register($this->getName(), self::$ranking_command);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener, $this);
    }

    public function onDisable(): void {
        PlayerDataPool::finalize();
        $this->getServer()->getCommandMap()->unregister(self::$ranking_command);
    }
}