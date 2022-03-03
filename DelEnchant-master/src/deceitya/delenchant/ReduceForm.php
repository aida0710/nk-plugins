<?php

namespace deceitya\delenchant;

use bbo51dog\bboform\form\SimpleForm;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class ReduceForm extends SimpleForm {

    public function __construct(Player $player) {
        $this->setTitle("Reduce Enchant");
        foreach ($player->getInventory()->getItemInHand()->getEnchantments() as $enchant) {
            $enchantName = $enchant->getType()->getName();
            if ($enchantName instanceof Translatable) {
                $enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
            }
            $this->addElement(
                new ReduceButton("$enchantName(Lv{$enchant->getLevel()})", $enchant)
            );
        }
    }
}
