<?php

namespace deceitya\delenchant;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ClosureButton;
use bbo51dog\bboform\form\ModalForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;

class DelButton extends Button {

    private EnchantmentInstance $enchantmentInstance;

    public function __construct(string $text, EnchantmentInstance $enchantmentInstance) {
        parent::__construct($text);
        $this->enchantmentInstance = $enchantmentInstance;
    }

    public function handleSubmit(Player $player): void {
        $confirmForm = (new ModalForm(new ClosureButton("はい", null, function (Player $player, Button $button) {
            $item = $player->getInventory()->getItemInHand();
            $cost = $item->getEnchantment($this->enchantmentInstance->getType())->getLevel() * 3000;
            if ($cost <= EconomyAPI::getInstance()->myMoney($player)) {
                $player->getInventory()->removeItem($item);
                $item->removeEnchantment($this->enchantmentInstance->getType());
                if (empty($item->getEnchantments())) {
                    $item->removeEnchantments();
                }
                $player->getInventory()->addItem($item);
                EconomyAPI::getInstance()->reduceMoney($player, $cost);
                $player->sendMessage("§bDeleteEnchant §7>> §aエンチャントを削除しました");
            } else {
                $player->sendMessage('§bDeleteEnchant §7>> §cお金が足りないため実行出来ません');
            }
        }), new Button("いいえ")));
        $player->sendForm($confirmForm);
    }
}