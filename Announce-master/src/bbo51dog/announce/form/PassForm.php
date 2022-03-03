<?php

namespace bbo51dog\announce\form;

use bbo51dog\announce\MessageFormat;
use bbo51dog\announce\service\AnnounceService;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class PassForm extends CustomForm {

    private const MESSAGE_WRONG_PASS = MessageFormat::PREFIX_PASS . TextFormat::RED . "パスワードが間違っています。";
    private const PASS = "利用規約に同意";

    private Input $input;

    public function __construct() {
        $this->input = new Input(
            "利用規約は読みましたでしょうか？\n\n利用規約に同意できる場合は下の欄に「利用規約に同意」と入力して「送信」を押してください",
            "利用規約に同意する？"
        );
        $this
            ->setTitle("PassSys")
            ->addElement($this->input);
    }

    public function handleClosed(Player $player): void {
        $player->sendMessage(self::MESSAGE_WRONG_PASS);
    }

    public function handleSubmit(Player $player): void {
        if ($this->input->getValue() !== self::PASS) {
            $player->sendMessage(self::MESSAGE_WRONG_PASS);
            return;
        }
        $name = $player->getName();
        AnnounceService::confirm($name);
        $player->sendMessage(MessageFormat::PREFIX_PASS . TextFormat::GREEN . "サーバーの全ての機能をアクティベートしました。");
        $player->sendMessage(MessageFormat::PREFIX_PASS . TextFormat::GREEN . "「Warp portal」や「Command execution」を使って見よう！");
        Server::getInstance()->broadcastMessage(TextFormat::AQUA . "NewPlayer " . TextFormat::GRAY . ">> " . TextFormat::YELLOW . "{$player->getName()}さんがパスワードを解除しました！");
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
        $player->sendForm(new AnnounceForm(AnnounceService::getAnnounceIdByName($name)));
        AnnounceService::setAlreadyRead($name, true);
    }

}