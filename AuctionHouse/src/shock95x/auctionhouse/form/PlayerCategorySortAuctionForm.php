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

    public function __construct() {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $name = $player->getName();
            if ($player->getName() === $name) {
                $names[] .= "自身の出品リストを表示";
                continue;
            }
            $names[] .= $name;
        }
        if (is_null($names)) {
            $names[] .= "自身の出品リストを表示";
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
            if ($value === "自身の出品リストを表示") {
                Server::getInstance()->dispatchCommand($player, "ah listings");
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