<?php

declare(strict_types = 0);
namespace deceitya\editEnchant\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use deceitya\editEnchant\form\ConfirmForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;

class SendConfirmFormButton extends Button {

	private EnchantmentInstance $enchant;
	private string $type;
	private int $level;

	public function __construct(string $text, EnchantmentInstance $enchant, string $type, ?int $level = 0, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->enchant = $enchant;
		$this->type = $type;
		$this->level = $level;
	}

	public function handleSubmit(Player $player) : void {
		SendForm::Send($player, (new ConfirmForm($player, $this->enchant, $this->type, $this->level)));
	}
}
