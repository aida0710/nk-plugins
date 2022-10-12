<?php

namespace lazyperson710\sff\form\police;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class GameModeSelectForm extends CustomForm {

    private Dropdown $dropdown;

    public function __construct(Player $player) {
        $gameMode = [
            "Survival",
            "Spectator",
        ];
        $this->dropdown = new Dropdown("ゲームモードを選択してください", $gameMode);
        $this
            ->setTitle("Police System")
            ->addElement($this->dropdown);
    }

    public function handleSubmit(Player $player): void {
        switch ($this->dropdown->getSelectedOption()) {
            case "Survival":
                $player->setGamemode(GameMode::SURVIVAL());
                $player->sendMessage("§bPolice §7>> §aサバイバルモードに変更しました");
                SoundPacket::Send($player, 'note.harp');
                return;
            case "Spectator":
                $player->setGamemode(GameMode::SPECTATOR());
                $player->sendMessage("§bPolice §7>> §aスペクテイターモードに変更しました");
                SoundPacket::Send($player, 'note.harp');
                return;
        }
    }
}