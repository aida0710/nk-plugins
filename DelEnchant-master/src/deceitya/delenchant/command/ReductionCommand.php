<?php

namespace deceitya\delenchant\command;

use deceitya\delenchant\ReduceForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;

class ReductionCommand extends Command {

    public function __construct() {
        parent::__construct("enreduce", "エンチャントを削減する");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!($sender instanceof Player)) {
            return true;
        }
        $item = $sender->getInventory()->getItemInHand();
        if ($item->getNamedTag()->getInt('MiningTools_3', -1) !== -1 || $item->getNamedTag()->getInt('4mining', -1) !== -1 || $item->getNamedTag()->getInt('MiningTools_Expansion', -1) !== -1 || $item->hasEnchantment(VanillaEnchantments::PUNCH())) {
            $sender->sendMessage('§bReduceEnchant §7>> §cこのアイテムはエンチャントを削減できません');
            return true;
        }
        /** @var ?ListTag $enchListTag */
        $enchListTag = $item->getNamedTag()->getListTag(Item::TAG_ENCH);
        if ($enchListTag === null || $enchListTag->count() === 0) {
            $sender->sendMessage('§bReduceEnchant §7>> §cこのアイテムはエンチャントを削減できません');
            return true;
        }
        $sender->sendForm(new ReduceForm($sender));
        return true;
    }

}