<?php

namespace lazyperson0710\Gacha\Calculation;

use lazyperson0710\Gacha\Main;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class ItemRegister {

    public function __construct($key, $rank) {
        $this->Items($key, $rank);
    }

    public function Items($key, $rank): Item {
        $count = count(Main::getInstance()->getAllData()[$key]["items"][0][$rank]);
        $count -= 1;
        $count = mt_rand(0, $count);
        $data = Main::getInstance()->getAllData()[$key]["items"][0][$rank][$count];
        $item = ItemFactory::getInstance()->get($data['id'], $data['meta'], $data['count']);
        if ($data['name'] !== null) {
            $item->setCustomName($data['name']);
        }
        if ($data['description'] !== null) {
            $item->setLore([$data['description']]);
        }
        foreach ($data['enchants'] as $enchant) {
            $item->addEnchantment(
                new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($enchant['id']),
                    $enchant['level']
                )
            );
        }
        if ($data['nbt'] !== null) {
            foreach ($data['nbt'] as $setNbt) {
                $nbt = $item->getNamedTag();
                $nbt->setInt($setNbt, 1);
            }
        }
        return $item;
    }
}