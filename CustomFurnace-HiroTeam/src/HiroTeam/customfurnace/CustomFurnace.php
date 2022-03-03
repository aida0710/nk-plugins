<?php

namespace HiroTeam\customfurnace;

use pocketmine\crafting\FurnaceRecipe;
use pocketmine\crafting\FurnaceType;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class CustomFurnace extends PluginBase {

    public function onEnable(): void {
        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            $this->saveResource('config.yml');
        }
        $this->furnaceDataCache();
    }

    public function furnaceDataCache(): void {
        foreach ($this->getAllAdd() as $recipe) {
            $result = $this->getItem($recipe["result"]);
            $recipes = $this->getItem($recipe["recipe"]);
            $recipe = new FurnaceRecipe(
                $result,
                $recipes
            );
            $this->getServer()->getCraftingManager()->getFurnaceRecipeManager(FurnaceType::FURNACE())->register($recipe);
        }
    }

    public function getAllAdd(): array {
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        return $config->getAll()["add"];
    }

    public function getItem(array $item): Item {
        return ItemFactory::getInstance()->get($item[0], $item[1]);
    }
}