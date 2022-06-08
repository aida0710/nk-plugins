<?php

namespace deceitya\miningtools\normal;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class ConfirmForm extends CustomForm {

    private Dropdown $toolType;
    private string $mode;
    public const DIAMOND_COST = 150000;
    public const NETHERITE_COST = 1500000;
    private int $cost;

    public function __construct(Player $player, string $mode) {
        $this->mode = $mode;
        switch ($mode) {
            case 'diamond'://todo しっかりtextを書いてください
                $this->cost = self::DIAMOND_COST;
                $explanation = new Label("dummy text");
                break;
            case 'netherite':
                $this->cost = self::NETHERITE_COST;
                $explanation = new Label("dummy text2");
                break;
            default:
                Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                return;
        }
        $type = [
            "つるはし",
            "しゃべる",
            "おの",
        ];
        $this->toolType = new Dropdown("購入したいツールタイプを選択してください", $type);
        $this
            ->setTitle("Mining Tools")
            ->addElements($explanation);
    }

    public function handleSubmit(Player $player): void {
        var_dump($this->toolType->getSelectedOption());
        $player->sendForm(new BuyForm($player, $this->mode, $this->cost, $this->toolType->getSelectedOption()));
    }
}