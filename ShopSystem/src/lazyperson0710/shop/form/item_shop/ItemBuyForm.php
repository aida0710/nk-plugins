<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\shop\database\ItemShopAPI;
use lazyperson0710\shop\object\ItemShopObject;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class ItemBuyForm extends CustomForm {

    private Input $count;
    private Toggle $onVirtualStorage;
    private ItemShopObject $item;

    public function __construct(Player $player, ItemShopObject $item) {
        var_dump('itemBuyForm');
        $this->count = new Input('購入する個数を入力してください', '個数');
        $this->onVirtualStorage = new Toggle('仮想ストレージに送る', false);
        $this->item = $item;
        $this->setTitle('Item Shop');
        StackStorageAPI::$instance->getCount($player->getXuid(), $item->getItem(),
            function ($virtualStorageItemCount) use ($player, $item) : void {
                $this->addElements(
                    new Label(SelectTypeForm::getLabel($player, $item, $virtualStorageItemCount)),
                    $this->count,
                    $this->onVirtualStorage,
                );
            }, function () use ($player, $item) : void {
                $this->addElements(
                    new Label(SelectTypeForm::getLabel($player, $item, 0)),
                    $this->count,
                    $this->onVirtualStorage,
                );
            },
        );
    }

    public function handleClosed(Player $player) : void {
        SendForm::Send($player, new SelectTypeForm($player, $this->item));
    }

    public function handleSubmit(Player $player) : void {
        $count = $this->count->getValue();
        if (!is_numeric($count)) {
            SendMessage::Send($player, '1以上の整数を入力してください', ItemShopAPI::PREFIX, false, 'dig.chain');
            return;
        }
        $count = (int) $count;
        if ($count <= 0) {
            SendMessage::Send($player, '1以上の整数を入力してください', ItemShopAPI::PREFIX, false, 'dig.chain');
            return;
        }
        $totalPrice = $this->item->getBuy() * $count;
        if (EconomyAPI::getInstance()->myMoney($player) < $totalPrice) {
            $insufficientAmount = $totalPrice - EconomyAPI::getInstance()->myMoney($player);
            SendMessage::Send($player, 'お金が' . number_format($insufficientAmount) . '円足りませんでした。合計必要金額:' . number_format($totalPrice) . '円', ItemShopAPI::PREFIX, false, 'dig.chain');
            return;
        }
        $this->item->getItem()->setCount($count);
        if ($this->onVirtualStorage->getValue() === true) {
            EconomyAPI::getInstance()->reduceMoney($player, $this->item->getBuy() * $count);
            StackStorageAPI::$instance->add($player->getXuid(), $this->item->getItem());
            $totalPrice = $this->item->getBuy() * $count;
            SendMessage::Send($player, $this->item->getDisplayName() . 'を' . number_format($count) . '個購入し、仮想ストレージに転送しました。使用金額 : ' . number_format($totalPrice) . '円', ItemShopAPI::PREFIX, true, 'break.amethyst_block');
            return;
        }
        if (!$player->getInventory()->canAddItem($this->item->getItem())) {
            SendMessage::Send($player, 'インベントリに空きはありません', ItemShopAPI::PREFIX, false, 'dig.chain');
            return;
        }
        $player->getInventory()->addItem($this->item->getItem());
        EconomyAPI::getInstance()->reduceMoney($player, $this->item->getBuy() * $count);
        $totalPrice = $this->item->getBuy() * $count;
        SendMessage::Send($player, $this->item->getDisplayName() . 'を' . number_format($count) . '個購入しました。使用金額 : ' . number_format($totalPrice) . '円', ItemShopAPI::PREFIX, true, 'break.amethyst_block');
    }
}
