<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop\future;

use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\shop\database\ItemShopAPI;
use lazyperson0710\shop\object\ItemShopObject;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use ree_jp\stackstorage\api\StackStorageAPI;

class ItemSell {

    use SingletonTrait;

    public function transaction(Player $player, int $sellCount, ItemShopObject $item, int $virtualStorageItemCount, bool $virtualStorageEnable) : void {
        $item->getItem()->setCount($sellCount);
        $inventoryItemCount = ItemHoldingCalculation::getHoldingCount($player, $item->getItem());
        if ($virtualStorageEnable && $virtualStorageItemCount === 0) {
            if ($sellCount <= $virtualStorageItemCount) {
                $resultSalePrice = number_format($this->buyItemFromStackStorage($player, $item, $sellCount));
                SendMessage::Send($player, '仮想ストレージから' . $sellCount . "個アイテムが売却され、所持金が{$resultSalePrice}円増えました", ItemShopAPI::PREFIX, true, 'break.amethyst_block');
                return;
            }
            $storageItemCount = $sellCount - $virtualStorageItemCount;
            if ($storageItemCount <= $inventoryItemCount) {
                $storageResultSalePrice = $this->buyItemFromStackStorage($player, $item, $virtualStorageItemCount);
                $inventoryResultSalePrice = $this->buyItemFromInventory($player, $item, $storageItemCount);
                $resultSalePrice = number_format($storageResultSalePrice + $inventoryResultSalePrice);
                SendMessage::Send($player, '仮想ストレージから' . number_format($virtualStorageItemCount) . '個とインベントリから' . number_format($storageItemCount) . '個、計' . number_format($virtualStorageItemCount + $storageItemCount) . "アイテムが売却され、所持金が{$resultSalePrice}円増えました", ItemShopAPI::PREFIX, true, 'break.amethyst_block');
                return;
            }
            SendMessage::Send($player, 'アイテムがない、もしくは足りません', ItemShopAPI::PREFIX, false, 'dig.chain');
            return;
        }
        if (!$player->getInventory()->contains($item->getItem())) {
            $storageItemCount = $sellCount - $inventoryItemCount;
            if ($storageItemCount <= $virtualStorageItemCount) {
                $storageResult = $this->buyItemFromStackStorage($player, $item, $storageItemCount); //$this->price * $storageItemCount;
                if ($inventoryItemCount === 0) {
                    SendMessage::Send($player, '仮想ストレージから' . number_format($storageItemCount) . '個アイテムが売却され、所持金が ' . number_format($storageResult) . '円増えました', ItemShopAPI::PREFIX, true, 'break.amethyst_block');
                    return;
                }
                $inventoryResult = $this->buyItemFromInventory($player, $item, $inventoryItemCount);
                $result = $inventoryResult + $storageResult;
                SendMessage::Send($player, '仮想ストレージから' . number_format($storageItemCount) . '個とインベントリから' . number_format($inventoryItemCount) . '個、計' . number_format($storageItemCount + $inventoryItemCount) . 'アイテムが売却され、所持金が' . number_format($result) . '円増えました', ItemShopAPI::PREFIX, true, 'break.amethyst_block');
                return;
            }
            SendMessage::Send($player, 'アイテムがない、もしくは足りません', ItemShopAPI::PREFIX, false, 'dig.chain');
            return;
        }
        $this->buyItemFromInventory($player, $item, $sellCount);
        $result = $item->getBuy() * $sellCount;
        SendMessage::Send($player, 'アイテムが' . number_format($sellCount) . '個売却され、所持金が' . number_format($result) . '円増えました', ItemShopAPI::PREFIX, true, 'break.amethyst_block');
        //(new LevelShopSellEvent($player, $item, 'sell'))->call();
    }

    private function buyItemFromStackStorage(Player $player, ItemShopObject $item, int $count) : int {
        $itemClone = (clone $item->getItem())->setCount($count);
        $storageResult = $item->getBuy() * $count;
        StackStorageAPI::$instance->remove($player->getXuid(), $itemClone);
        EconomyAPI::getInstance()->addMoney($player, $storageResult);
        return $storageResult;
    }

    private function buyItemFromInventory(Player $player, ItemShopObject $item, int $count) : int {
        $itemClone = (clone $item->getItem())->setCount($count);
        $result = $item->getBuy() * $count;
        $player->getInventory()->removeItem($itemClone);
        EconomyAPI::getInstance()->addMoney($player, $item->getBuy() * $count);
        return $result;
    }

}