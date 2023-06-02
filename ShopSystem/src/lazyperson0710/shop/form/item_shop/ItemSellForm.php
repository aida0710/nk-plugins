<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\shop\form\item_shop\future\ItemSell;
use lazyperson0710\shop\object\ItemShopObject;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class ItemSellForm extends CustomForm {

    private ItemShopObject $item;
    private Input $count;
    private Toggle $virtualStorageEnable;

    public function __construct(Player $player, ItemShopObject $item) {
        var_dump('itemSellForm');
        $this->item = $item;
        $this->count = new Input('売却する個数を入力してください', '1');
        $this->virtualStorageEnable = new Toggle('仮想ストレージに送る', false);
        $this->setTitle('Item Shop');
        StackStorageAPI::$instance->getCount($player->getXuid(), $item->getItem(),
            function ($virtualStorageItemCount) use ($player, $item) : void {
                $this->addElements(
                    new Label(SelectTypeForm::getLabel($player, $item, $virtualStorageItemCount)),
                    $this->count,
                    $this->virtualStorageEnable,
                );
            }, function () use ($player, $item) : void {
                $this->addElements(
                    new Label(SelectTypeForm::getLabel($player, $item, 0)),
                    $this->count,
                    $this->virtualStorageEnable,
                );
            },
        );
    }

    public function handleSubmit(Player $player) : void {
        $sellCount = $this->count->getValue();
        if (!is_numeric($sellCount)) {
            SendMessage::Send($player, '1以上の整数を入力してください', 'LevelShop', false, 'dig.chain');
            return;
        }
        if ((int) $sellCount <= 0) {
            SendMessage::Send($player, '1以上の整数を入力してください', 'LevelShop', false, 'dig.chain');
            return;
        }
        $item = $this->item;
        $virtualStorageEnable = $this->virtualStorageEnable->getValue();
        StackStorageAPI::$instance->getCount($player->getXuid(), $this->item->getItem(),
            function ($count) use ($player, $sellCount, $item, $virtualStorageEnable) : void {
                ItemSell::getInstance()->transaction($player, $sellCount, $item, $count, $virtualStorageEnable);
            }, function () use ($player, $sellCount, $item, $virtualStorageEnable) : void {
                ItemSell::getInstance()->transaction($player, $sellCount, $item, 0, $virtualStorageEnable);
            },
        );
    }

}
