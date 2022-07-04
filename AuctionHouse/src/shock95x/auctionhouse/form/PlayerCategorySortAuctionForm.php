<?php

namespace shock95x\auctionhouse\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerCategorySortAuctionForm extends CustomForm {

    private Dropdown $dropdown;
    private Input $offlinePlayer;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $onlinePlayer = $onlinePlayer->getName();
            if ($player->getName() === $onlinePlayer) {
                continue;
            }
            $names[] .= $onlinePlayer;
        }
        if (is_null($names)) {
            $names[] .= "現在オンラインのプレイヤーは自身以外に存在しません";
        }
        $this->dropdown = new Dropdown("出品リストを表示されたいプレイヤーを選択してください\n", $names);
        $this->offlinePlayer = new Input("また、オフラインプレイヤーを指定したい場合はこちらにゲームタグを入力してください\nこちらに入力すると上記のプレイヤーを選択しても無効になり、こちらの処理が優先されます", $player->getName());
        $this
            ->setTitle("Auction System")
            ->addElements(
                $this->dropdown,
                $this->offlinePlayer,
            );
    }

    public function handleSubmit(Player $player): void {
        if ($this->offlinePlayer->getValue() === "") {
            $value = $this->dropdown->getSelectedOption();
            if ($value === "現在オンラインのプレイヤーは自身以外に存在しません") {
                $player->sendMessage("§bAuction §7>> §c現在オンラインのプレイヤーは自身以外に存在しない為、表示したいプレイヤーの名前をInputに直接入力してください");
                return;
            }
        } else {
            $value = $this->offlinePlayer->getValue();
            if ($value === $player->getName()) {
                Server::getInstance()->dispatchCommand($player, "ah listings");
                return;
            }
        }
        Server::getInstance()->dispatchCommand($player, "ah listings {$value}");
    }
}