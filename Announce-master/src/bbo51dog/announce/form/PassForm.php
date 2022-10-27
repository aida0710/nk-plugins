<?php

namespace bbo51dog\announce\form;

use bbo51dog\announce\service\AnnounceService;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendBroadcastMessage;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\Server;

class PassForm extends CustomForm {

    private const PASS = "利用規約に同意";

    private Input $input;

    public function __construct() {
        $this->input = new Input(
            "利用規約は読みましたでしょうか？\n\n利用規約に同意できる場合は下の欄に「利用規約に同意」と入力して「送信」を押してください\nまた、「」は不要の為ご注意ください",
            "利用規約に同意する？"
        );
        $this
            ->setTitle("PassSystem")
            ->addElement($this->input);
    }

    public function handleSubmit(Player $player): void {
        if ($this->input->getValue() !== self::PASS) {
            SendMessage::Send($player, "ワードが間違っている為解放できませんでした", "Pass", false);
            SendMessage::Send($player, "利用規約に同意する場合は利用規約に同意と入力してください", "Pass", false);
            return;
        }
        $name = $player->getName();
        AnnounceService::confirm($name);
        SendMessage::Send($player, "サーバーのコマンド使用権限を解放しました！", "Pass", true);
        SendMessage::Send($player, "最初はWarpCompassから天然資源>nature-1に移動して資源を取ってみよう！", "Pass", true);
        SendBroadcastMessage::Send("{$player->getName()}さんがパスワードを解除しました！", "NewPlayer");
        Server::getInstance()->dispatchCommand($player, "warp lobby");
        $player->sendTitle("§bなまけものサーバーにようこそ！", "§b是非楽しんで行ってね！");
        $bonus = ItemFactory::getInstance()->get(-195);
        $bonus->setCustomName("Login Bonus");
        $bonus->setLore([
            "lore1" => "初回限定ログインボーナスアイテム",
        ]);
        if (!$player->getInventory()->contains($bonus) && $player->getInventory()->canAddItem($bonus)) {
            $player->getInventory()->addItem($bonus);
        }
        SendForm::Send($player, (new AnnounceForm(AnnounceService::getAnnounceIdByName($name))));
        AnnounceService::setAlreadyRead($name, true);
    }

}