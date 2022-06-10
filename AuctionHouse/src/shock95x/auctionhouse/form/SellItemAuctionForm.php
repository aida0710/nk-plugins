<?php

namespace shock95x\auctionhouse\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class SellItemAuctionForm extends CustomForm {

    private Input $SellingPrice;

    public function __construct(Player $player) {
        $this->SellingPrice = new Input("出品価格を入力してください\n価格は300円から1億円まで指定可能です\n\n3日間購入されなかった場合は出品リストから削除されます\n削除されたアイテムはform->[販売期限が切れたアイテム又はキャンセルされたアイテムを表示]から回収できます", 500);
        $item = $player->getInventory()->getItemInHand();
        $this
            ->setTitle("Auction System")
            ->addElements(
                new Label("現在出品しようとしようとしているアイテムの情報\nItemName - {$item->getName()}\nItemId/Meta - {$item->getId()}/{$item->getMeta()}\n数量 - {$item->getCount()}"),
                new label("出品ができないアイテムはシェルカーボックスのみとなります"),
                $this->SellingPrice,
            );
    }

    public function handleSubmit(Player $player): void {
        if ($this->SellingPrice->getValue() === "") {
            $player->sendMessage("§bAuction §7>> §c出品価格を入力してください");
            return;
        }
        if (!is_numeric($this->SellingPrice->getValue())) {
            $player->sendMessage("§bAuction §7>> §c整数を入力してください");
            return;
        }
        Server::getInstance()->dispatchCommand($player, "ah sell {$this->SellingPrice->getValue()}");
    }
}