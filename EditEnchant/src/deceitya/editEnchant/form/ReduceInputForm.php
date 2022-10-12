<?php

namespace deceitya\editEnchant\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use deceitya\editEnchant\InspectionItem;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
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
            $player->sendMessage("§bEditEnchant §7>> §c所持しているアイテムが変更された可能性がある為処理を中断しました");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if (!is_numeric($this->reduceEnchantLevel->getValue())) {
            $player->sendMessage('§bEditEnchant §7>> §c整数を入力してください');
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if ($this->reduceEnchantLevel->getValue() < 1) {
            $player->sendMessage('§bEditEnchant §7>> §c1以上の値を入力してください');
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if ($this->reduceEnchantLevel->getValue() > $this->enchantmentInstance->getLevel()) {
            $player->sendMessage('§bEditEnchant §7>> §c削減したいレベルがエンチャントのレベルよりも大きいです');
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        SendForm::Send($player, (new ConfirmForm($player, $this->enchantmentInstance, $this->type, $this->reduceEnchantLevel->getValue())));
    }
}
