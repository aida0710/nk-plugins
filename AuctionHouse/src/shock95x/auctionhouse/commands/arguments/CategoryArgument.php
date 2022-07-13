<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\commands\arguments;

use pocketmine\command\CommandSender;
use shock95x\auctionhouse\category\Category;
use shock95x\auctionhouse\category\ICategory;
use shock95x\auctionhouse\libs\CortexPE\Commando\args\StringEnumArgument;

class CategoryArgument extends StringEnumArgument {

    public function getEnumValues(): array {
        $names = array_keys(Category::getAll());
        return array_map('strtolower', $names);
    }

    public function getTypeName(): string {
        return "name";
    }

    public function parse(string $argument, CommandSender $sender): ICategory {
        return Category::get($argument);
    }
}