<?php

declare(strict_types = 1);
namespace nkserver\ranking\form;

use lazyperson710\core\packet\SendForm;
use nkserver\ranking\libs\form\SimpleForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class BackableForm extends SimpleForm {

    protected ?Form $before;

    public function __construct(?Form $before) {
        $this->before = $before;
    }

    public function back(Player $player): void {
        if ($this->before === null) return;
        SendForm::Send($player, ($this->before));
    }
}