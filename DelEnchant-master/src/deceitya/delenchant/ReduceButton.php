<?php

namespace deceitya\delenchant;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ClosureButton;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\ClosureCustomForm;
use bbo51dog\bboform\form\CustomForm;
use bbo51dog\bboform\form\ModalForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;

class ReduceButton extends Button {

    private EnchantmentInstance $enchantmentInstance;

    public function __construct(string $text, EnchantmentInstance $enchantmentInstance) {
        parent::__construct($text);
        $this->enchantmentInstance = $enchantmentInstance;
    }

    public function handleSubmit(Player $player): void {
        $input = (new Input("削減するレベル", "ここにレベルを入力"));
        $confirmForm = (new ModalForm(new ClosureButton("はい", null, function (Player $player, Button $button) use ($input) {
            if (!preg_match('/\d+$/', $input->getValue())) {
                $player->sendMessage('§bReduceEnchant §7>> §c整数を入力してください');
                return;
            }
            if (!preg_match('/^[0-9]+$/', $input->getValue())) {
                $player->sendMessage("§bReduceEnchant §7>> §c整数を入力してください");
                return;
            }
            $item = $player->getInventory()->getItemInHand();
            $level = (int)$input->getValue();
            $cost = $level * 1500;
            if ($cost <= EconomyAPI::getInstance()->myMoney($player)) {
                if ($this->enchantmentInstance->getLevel() - $level < 1) {
                    $player->sendMessage('§bReduceEnchant §7>> §cエンチャントレベルが0になってしまうため実行出来ませんでした');
                    return;
                }
                $player->getInventory()->removeItem($item);
                $item->removeEnchantment($this->enchantmentInstance->getType(), $level);
                $item->addEnchantment(new EnchantmentInstance($this->enchantmentInstance->getType(), $this->enchantmentInstance->getLevel() - $level));
                $player->getInventory()->addItem($item);
                EconomyAPI::getInstance()->reduceMoney($player, $cost);
                $player->sendMessage("§bReduceEnchant §7>> §aエンチャントを削減しました");
            } else {
                $player->sendMessage('§bReduceEnchant §7>> §cお金が足りないため実行出来ません');
            }
        }), new Button("いいえ")));
        $levelForm = (new ClosureCustomForm(function (Player $player, CustomForm $form) use ($confirmForm) {
            $player->sendForm($confirmForm);
        }))
            ->setTitle("Reduce Enchant")
            ->addElement($input);
        $player->sendForm($levelForm);
    }
}