<?php

declare(strict_types = 0);

namespace onebone\economyapi\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\command\PayCommand;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;

class PayCommandConfirmation extends CustomForm {

    private Toggle $toggle;
    private EconomyAPI $plugin;
    private Player $target;
    private int $amount;

    public function __construct(EconomyAPI $plugin, Player $target, int $amount) {
        $this->plugin = $plugin;
        $this->target = $target;
        $this->amount = $amount;
        $this->toggle = new Toggle('送金を行いますか？', false);
        $this
            ->setTitle('PaySystem')
            ->addElements(
                new Label("振り込み金額を確認してください\n"),
                new Label("振り込み金額: §e{$amount}"),
                new Label("振り込み先: §e{$target->getName()}\n"),
                $this->toggle,
                new Label("送金をしない場合はトグルを押さずにformを閉じてください\nまた、この確認画面を表示しないには/settingから設定できます"),
            );
    }

    public function handleSubmit(Player $player) : void {
        if ($this->toggle->getValue() === true) {
            (new PayCommand($this->plugin))->Calculation($this->plugin, $player, $this->target, $this->amount);
        } else {
            SendMessage::Send($player, 'トグルをtrueにしないでformを送信したため送金は行われませんでした', 'Economy', true);
        }
    }
}
