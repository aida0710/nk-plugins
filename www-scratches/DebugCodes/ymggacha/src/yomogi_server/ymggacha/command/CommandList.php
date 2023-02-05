<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\command;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandMap;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use RuntimeException;
use ymggacha\src\yomogi_server\ymggacha\command\gacha\GachaCommand;
use ymggacha\src\yomogi_server\ymggacha\command\gacha_ticket\GachaTicketCommand;

class CommandList {

    use SingletonTrait;

    /** @var array<BaseCommand> */
    private array $commands = [];

    public function __construct(PluginBase $plugin) {
        $this->setup($plugin);
    }

    /**
     * @throws RuntimeException
     */
    public function registerToMap(CommandMap $map): void {
        foreach ($this->commands as $cmd) {
            $map->register($cmd->getOwningPlugin()->getName() . $cmd->getName(), $cmd);
        }
    }

    private function registerCommand(BaseCommand $command): void {
        $this->commands[] = $command;
    }

    private function setup(PluginBase $plugin): void {
        $this->registerCommand(new GachaCommand($plugin));
        $this->registerCommand(new GachaTicketCommand($plugin));
    }
}
