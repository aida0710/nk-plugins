<?php

namespace deceitya\editEnchant;

use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;

class InspectionItem {

    /**
     * @param Player $player
     * @return bool
     */
    public function inspectionItem(Player $player): bool {
        $item = $player->getInventory()->getItemInHand();
        if (!$item instanceof Durable) {
            $player->sendMessage('§bEditEnchant §7>> §c現在所持しているアイテムは道具では無いためエンチャントを編集することは出来ません');
            return false;
        }
        if ($item->getNamedTag()->getTag('gacha_mining') === null) {
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                $player->sendMessage('§bEditEnchant §7>> §cこのアイテムはエンチャントを編集できません');
                return false;
            }
        }
        if ($item->getNamedTag()->getTag('4mining') !== null) {
            $player->sendMessage('§bEditEnchant §7>> §cこのアイテムはエンチャントを編集できません');
            return false;
        }
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
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