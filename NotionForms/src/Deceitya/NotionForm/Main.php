<?php

namespace Deceitya\NotionForm;

use Deceitya\NotionForm\command\FunctionCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    static array $function;
    static array $specification;

    public function onEnable(): void {
        $this->saveResource('function.json');
        $this->saveResource('specification.json');
        self::$function = (new Config($this->getDataFolder() . 'function.json', Config::JSON))->getAll();
        self::$specification = (new Config($this->getDataFolder() . 'specification.json', Config::JSON))->getAll();
        $this->getServer()->getCommandMap()->registerAll("notionForm", [
            new FunctionCommand(),
        ]);
    }
}
