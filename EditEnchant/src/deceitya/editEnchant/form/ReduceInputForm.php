<?php

declare(strict_types = 0);
namespace deceitya\editEnchant\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use deceitya\editEnchant\InspectionItem;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;
use function is_numeric;

class ReduceInputForm extends CustomForm {

	private Input $reduceEnchantLevel;
	private EnchantmentInstance $enchantmentInstance;
	private string $type;

	public function __construct(Player $player, EnchantmentInstance $enchant, string $type) {
		$this->reduceEnchantLevel = new Input('削減したい分のレベルを入力してください', '1');
		$this->enchantmentInstance = $enchant;
		$this->type = $type;
		$this
			->setTitle('Reduce Enchant')
			->addElements(
				$this->reduceEnchantLevel,
			);
	}

	public function handleSubmit(Player $player) : void {
		if (!(new InspectionItem())->inspectionItem($player)) {
			SendMessage::Send($player, '所持しているアイテムが変更された可能性がある為処理を中断しました', 'EditEnchant', false);
			return;
		}
		if (!is_numeric($this->reduceEnchantLevel->getValue())) {
			SendMessage::Send($player, '整数を入力してください', 'EditEnchant', false);
			return;
		}
		if ($this->reduceEnchantLevel->getValue() < 1) {
			SendMessage::Send($player, '1以上の値を入力してください', 'EditEnchant', false);
			return;
		}
		if ($this->reduceEnchantLevel->getValue() > $this->enchantmentInstance->getLevel()) {
			SendMessage::Send($player, '削減したいレベルがエンチャントのレベルよりも大きいです', 'EditEnchant', false);
			return;
		}
		SendForm::Send($player, (new ConfirmForm($player, $this->enchantmentInstance, $this->type, (int) $this->reduceEnchantLevel->getValue())));
	}
}
