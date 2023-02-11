<?php

declare(strict_types = 0);
namespace deceitya\editEnchant\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use deceitya\editEnchant\form\ReduceInputForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;

class SendReduceInputFormButton extends Button {

	private EnchantmentInstance $enchant;
	private string $type;

	public function __construct(string $text, EnchantmentInstance $enchant, string $type, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->enchant = $enchant;
		$this->type = $type;
	}

	public function handleSubmit(Player $player) : void {
		SendForm::Send($player, (new ReduceInputForm($player, $this->enchant, $this->type)));
	}
}
