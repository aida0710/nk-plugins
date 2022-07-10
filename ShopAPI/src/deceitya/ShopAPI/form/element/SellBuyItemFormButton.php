<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\LevelShopAPI;
use deceitya\ShopAPI\form\levelShop\other\SearchShop\InputItemForm;
use deceitya\ShopAPI\form\levelShop\PurchaseForm;
use deceitya\ShopAPI\form\levelShop\SellBuyForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class SellBuyItemFormButton extends Button {

    private int $itemId;
    private int $itemMeta;

    /**
     * @param string $text
     * @param int $itemId
     * @param int $itemMeta
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, int $itemId, int $itemMeta, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->itemId = $itemId;
        $this->itemMeta = $itemMeta;
    }

    public function handleSubmit(Player $player): void {
        $api = LevelShopAPI::getInstance();
        if (MiningLevelAPI::getInstance()->getLevel($player) < LevelShopAPI::getInstance()->getLevel($this->itemId, $this->itemMeta)) {
            $error = "§c要求されたレベルに達していない為処理が中断されました\n要求レベル -> lv.{$api->getLevel($this->itemId, $this->itemMeta)}\n§r";
            $player->sendForm(new InputItemForm($error));
            return;
        }
        if (LevelShopAPI::getInstance()->getSell($this->itemId, $this->itemMeta) == 0) {//売却値が0だった時選択がそもそもスキップされるように
            $item = ItemFactory::getInstance()->get($this->itemId, $this->itemMeta);
            StackStorageAPI::$instance->getCount($player->getXuid(), $item, function ($count) use ($player, $item) {
                $this->callback($player, $item, $count);
            }, function () use ($player, $item) {
                $this->callback($player, $item, 0);
            });
            return;
        }
        $player->sendForm(new SellBuyForm($this->itemId, $this->itemMeta, LevelShopAPI::getInstance()->getBuy($this->itemId, $this->itemMeta), LevelShopAPI::getInstance()->getSell($this->itemId, $this->itemMeta)));
    }

    public function callback(Player $player, Item $item, int $strage): void {
        $count = 0;
        $mymoney = EconomyAPI::getInstance()->mymoney($player);
        foreach ($player->getInventory()->getContents() as $v) {
            if ($item->getId() === $v->getId() && $item->getMeta() === $v->getMeta()) {
                $count += $v->getCount();
            }
        }
        $player->sendForm(new PurchaseForm($item, LevelShopAPI::getInstance()->getBuy($this->itemId, $this->itemMeta), $count, $mymoney, $strage));
    }
}