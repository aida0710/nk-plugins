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
        $diamondCost = self::DIAMOND_COST;
        $netheriteCost = self::NETHERITE_COST;
        switch ($mode) {
            case 'diamond':
                $this->cost = self::DIAMOND_COST;
                $explanation = new Label("DiamondMiningTools\n\n必要金額 : {$diamondCost}\n\n「送信」を押すと確認画面に飛びます\n\nしゃべるとピッケルの機能\n採掘地点を中心とした3x3の範囲採掘\n範囲破壊が行われるのはツールに対応したブロックのみになります\n\n斧の機能\nマインオールのような機能を持ち、採掘したブロック(原木と葉っぱが対象)に隣接するブロックを破壊していく");
                break;
            case 'netherite':
                $this->cost = self::NETHERITE_COST;
                $explanation = new Label("NetheriteMiningTools\n\n必要金額 : {$netheriteCost}\n\n「送信」を押すと確認画面に飛びます\n\nしゃべるとピッケルの機能\n採掘地点を中心とした3x3の範囲採掘\n範囲破壊が行われるのはツールに対応したブロックのみになります\n\n斧の機能\nマインオールのような機能を持ち、採掘したブロック(原木と葉っぱが対象)に隣接するブロックを破壊していく");
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
            ->addElements(
                $explanation,
                $this->toolType,
            );
    }

    public function handleSubmit(Player $player): void {
        var_dump($this->toolType->getSelectedOption());
        $player->sendForm(new BuyForm($player, $this->mode, $this->cost, $this->toolType->getSelectedOption()));
    }
}