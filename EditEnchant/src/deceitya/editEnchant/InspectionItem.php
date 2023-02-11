<?php

declare(strict_types = 0);
namespace deceitya\editEnchant;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;

class InspectionItem {

	public function inspectionItem(Player $player) : bool {
		$item = $player->getInventory()->getItemInHand();
		if (!$item instanceof Durable) {
			SendMessage::Send($player, '現在所持しているアイテムは道具では無いためエンチャントを編集することは出来ません', 'EditEnchant', true);
			return false;
		}
		if ($item->getNamedTag()->getTag('gacha_mining') === null) {
			if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
				SendMessage::Send($player, 'このアイテムはエンチャントを編集できません', 'EditEnchant', false);
				return false;
			}
		}
		if ($item->getNamedTag()->getTag('4mining') !== null) {
			SendMessage::Send($player, 'このアイテムはエンチャントを編集できません', 'EditEnchant', false);
			return false;
		}
		if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
			SendMessage::Send($player, 'このアイテムはエンチャントを編集できません', 'EditEnchant', false);
			return false;
		}
		if ($item->hasEnchantment(VanillaEnchantments::PUNCH())) {
			SendMessage::Send($player, '衝撃エンチャントが付与されているため編集できません', 'EditEnchant', false);
			return false;
		}
		/** @var ?ListTag $enchListTag */
		$enchListTag = $item->getNamedTag()->getListTag(Item::TAG_ENCH);
		if ($enchListTag === null || $enchListTag->count() === 0) {
			SendMessage::Send($player, 'このアイテムはエンチャントを編集できません', 'EditEnchant', false);
			return false;
		}
		return true;
	}

}
