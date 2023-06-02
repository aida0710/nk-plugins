<?php

declare(strict_types = 0);

namespace lazyperson0710\Gacha\Calculation;

use lazyperson0710\Gacha\database\GachaItemAPI;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use function count;
use function mt_rand;

class ItemRegister {

    private string $categoryName;

    public function __construct($categoryName, $rank) {
        $this->categoryName = $categoryName;
        $this->Items($rank);
    }

    public function Items(string $rank) : Item {
        $count = count(GachaItemAPI::getInstance()->gachaItems[$this->categoryName][$rank]);
        $count -= 1;
        $count = mt_rand(0, $count);
        $data = GachaItemAPI::getInstance()->gachaItems[$this->categoryName][$rank][$count];
        $item = $data['item'];
        if ($data['name'] !== null) {
            $item->setCustomName($data['name']);
        }
        if ($data['lore'] !== null) {
            $item->setLore([$data['lore']]);
        }
        foreach ($data['enchants'] as $key => $enchant) {
            $item->addEnchantment(new EnchantmentInstance($enchant, $data['level'][$key]));
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
