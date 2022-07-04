<?php

namespace deceitya\editEnchant\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use deceitya\editEnchant\InspectionItem;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;

class ReduceInputForm extends CustomForm {

    private Input $reduceEnchantLevel;
    private EnchantmentInstance $enchantmentInstance;
    private string $type;

    public function __construct(Player $player, EnchantmentInstance $enchant, string $type) {
        $this->reduceEnchantLevel = new Input("削減したい分のレベルを入力してください", "1");
        $this->enchantmentInstance = $enchant;
        $this->type = $type;
        $this
            ->setTitle("Reduce Enchant")
            ->addElements(
                $this->reduceEnchantLevel,
            );
    }

    public function handleSubmit(Player $player): void {
        if (!(new InspectionItem())->inspectionItem($player)) {
            $player->sendMessage("§bEditEnchant §7>> §c想定されたエラーが発生しました");
            return;
        }
        if (!is_numeric($this->reduceEnchantLevel->getValue())) {
            $player->sendMessage('§bEditEnchant §7>> §c整数を入力してください');
            return;
        }
        if ($this->reduceEnchantLevel->getValue() < 1) {
            $player->sendMessage('§bEditEnchant §7>> §c1以上の値を入力してください');
            return;
        }
        if ($this->reduceEnchantLevel->getValue() > $this->enchantmentInstance->getLevel()) {
            $player->sendMessage('§bEditEnchant §7>> §c削減したいレベルがエンチャントのレベルよりも大きいです');
            return;
        }
        $player->sendForm(new ConfirmForm($player, $this->enchantmentInstance, $this->type, $this->reduceEnchantLevel->getValue()));
    }
}
