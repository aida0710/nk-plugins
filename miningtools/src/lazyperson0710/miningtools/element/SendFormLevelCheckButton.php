<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\miningtools\extensions\CheckPlayerData;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SendFormLevelCheckButton extends Button {

    private Form $form;
    private int $level;
    private ?bool $handItemCheck;

    public function __construct(Form $form, string $text, int $level, ?bool $handItemCheck = false, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->form = $form;
        $this->level = $level;
        $this->handItemCheck = $handItemCheck;
    }

    public function handleSubmit(Player $player) : void {
        if (MiningLevelAPI::getInstance()->getLevel($player) < $this->level) {
            SendMessage::Send($player, 'レベル' . $this->level . '以上でないと開けません', 'MiningTool', false);
            return;
        }
        if ($this->handItemCheck === true) {
            if ((new CheckPlayerData())->checkMiningToolsNBT($player) === false) return;
        }
        SendForm::Send($player, $this->form);
    }
}
