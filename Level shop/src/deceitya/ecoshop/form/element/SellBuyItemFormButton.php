<?php

namespace deceitya\ecoshop\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\PurchaseForm;
use deceitya\ecoshop\form\SellBuyForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class SellBuyItemFormButton extends Button {

    private int $itemId;

    /**
     * @param string $text
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, int $itemId, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->itemId = $itemId;
    }

    public function handleSubmit(Player $player): void {
        if (LevelShopAPI::getInstance()->getSell($this->itemId) === 0) {//売却値が0だった時選択がそもそもスキップされるように
            $item = ItemFactory::getInstance()->get($this->itemId);
            StackStorageAPI::$instance->getCount($player->getXuid(), $item, function ($count) use ($player, $item) {
                $this->callback($player, $item, $count);
            }, function () use ($player, $item) {
                $this->callback($player, $item, 0);
            });
        }
        $player->sendForm(new SellBuyForm($this->itemId, LevelShopAPI::getInstance()->getBuy($this->itemId), LevelShopAPI::getInstance()->getSell($this->itemId)));
    }

    public function callback(Player $player, Item $item, int $strage): void {
        $count = 0;
        $mymoney = EconomyAPI::getInstance()->mymoney($player);
        foreach ($player->getInventory()->getContents() as $v) {
            if ($item->getId() === $v->getId() && $item->getMeta() === $v->getMeta()) {
                $count += $v->getCount();
            }
        }
        $player->sendForm(new PurchaseForm($item, LevelShopAPI::getInstance()->getBuy($this->itemId), $count, $mymoney, $strage));
    }
}