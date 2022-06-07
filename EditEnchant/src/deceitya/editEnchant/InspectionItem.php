<?php

namespace deceitya\editEnchant;

use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;

class InspectionItem {

    public function inspectionItem(Player $player): bool {
        $item = $player->getInventory()->getItemInHand();
        if ($item->getNamedTag()->getInt('MiningTools_3', -1) !== -1 || $item->getNamedTag()->getInt('4mining', -1) !== -1 || $item->getNamedTag()->getInt('MiningTools_Expansion', -1) !== -1 || $item->hasEnchantment(VanillaEnchantments::PUNCH())) {
            $player->sendMessage('§bEditEnchant §7>> §cこのアイテムはエンチャントを編集できません');
            return false;
        }
        if ($item->hasEnchantment(VanillaEnchantments::PUNCH())) {
            $player->sendMessage('§bEditEnchant §7>> §c衝撃エンチャントが付与されているため編集できません');
            return false;
        }
        /** @var ?ListTag $enchListTag */
        $enchListTag = $item->getNamedTag()->getListTag(Item::TAG_ENCH);
        if ($enchListTag === null || $enchListTag->count() === 0) {
            $player->sendMessage('§bEditEnchant §7>> §cこのアイテムはエンチャントを編集できません');
            return false;
        }
        return true;
    }

}