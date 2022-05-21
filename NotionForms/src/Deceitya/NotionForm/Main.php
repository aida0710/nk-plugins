<?php

namespace Deceitya\NotionForm;

use Deceitya\NotionForm\command\CommandListCommand;
use Deceitya\NotionForm\command\FunctionCommand;
use Deceitya\NotionForm\command\SpecificationCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    static array $function;
    static array $specification;
    static array $command;

    public function onEnable(): void {
        $this->saveResource('function.json');
        $this->saveResource('specification.json');
        $this->saveResource('command.json');
        self::$function = (new Config($this->getDataFolder() . 'function.json', Config::JSON))->getAll();
        self::$specification = (new Config($this->getDataFolder() . 'specification.json', Config::JSON))->getAll();
        self::$command = (new Config($this->getDataFolder() . 'command.json', Config::JSON))->getAll();
        $this->getServer()->getCommandMap()->registerAll("notionForm", [
            new FunctionCommand(),
            new SpecificationCommand(),
            new CommandListCommand()
        ]);
    }
}
