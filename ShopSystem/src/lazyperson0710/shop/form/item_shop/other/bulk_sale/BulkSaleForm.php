<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop\other\bulk_sale;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\shop\database\ItemShopAPI;
use lazyperson0710\shop\form\item_shop\future\ItemHoldingCalculation;
use lazyperson0710\shop\form\item_shop\future\ItemSell;
use lazyperson0710\shop\form\item_shop\future\LevelCheck;
use lazyperson0710\shop\form\item_shop\future\RestrictionShop;
use lazyperson0710\shop\form\item_shop\ItemSelectForm;
use lazyperson0710\shop\object\ItemShopObject;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ree_jp\stackstorage\api\StackStorageAPI;

class BulkSaleForm extends CustomForm {

    private int $shopNumber;
    private string $category;

    /** @var Toggle[] */
    private array $toggles;
    private array $virtualStorageCount = [];
    /** @var ItemShopObject[] */
    private array $categoryItems;
    private array $itemAllCount = [];

    public function __construct(Player $player, int $shopNumber, string $category) {
        var_dump('bulkSaleForm');
        $this->shopNumber = $shopNumber;
        $this->category = $category;
        $this->categoryItems = ItemShopAPI::getInstance()->getCategoryItems($shopNumber, $category);
        $this->getVirtualStorageCount($player, ItemShopAPI::getInstance()->getCategoryItems($shopNumber, $category));
        $this
            ->setTitle('Item Shop')
            ->addElement(
                new Label(
                    '仮想ストレージとインベントリ含む全ての場所から一括で売却出来ます' . PHP_EOL .
                    '売却したいアイテムのtoggleを有効にして送信を押してください' . PHP_EOL .
                    '※インベントリ内か仮想ストレージ内にアイテムが存在しない場合そのアイテムは無視されます' . PHP_EOL .
                    TextFormat::GRAY . '前のアイテムカテゴリ画面に戻るにはこのフォームをESCキーか右上の×ボタンから閉じてください'
                ),
            );
    }

    /**
     * @param Player           $player
     * @param ItemShopObject[] $items
     * @return void
     */
    private function getVirtualStorageCount(Player $player, array $items) : void {
        var_dump('getVirtualStorageCount');
        if ($items === []) return;
        StackStorageAPI::$instance->getCount($player->getXuid(), $item = array_shift($items)->getItem(),
            function ($virtualStorageItemCount) use ($player, $items, $item) : void {
                $this->virtualStorageCount[$item->getVanillaName()] = $virtualStorageItemCount;
                var_dump($item->getVanillaName() . ' -> ' . $virtualStorageItemCount);
                $this->getVirtualStorageCount($player, $items);
                if ($items === []) $this->setToggleElements($player);
            }, function () use ($player, $items, $item) : void {
                $this->virtualStorageCount[$item->getVanillaName()] = 0;
                $this->getVirtualStorageCount($player, $items);
                if ($items === []) $this->setToggleElements($player);
            },
        );
    }

    private function setToggleElements(Player $player) : void {
        var_dump('setToggleElements');
        //var_dump($this->virtualStorageCount);
        $haveItems = 0;
        foreach ($this->categoryItems as $item) {
            if (!$item instanceof ItemShopObject) throw new \RuntimeException('ItemShopObjectではありません');
            $inventory = ItemHoldingCalculation::getHoldingCount($player, $item->getItem());
            if (!isset($this->virtualStorageCount[$item->getItem()->getVanillaName()])) {
                $this->virtualStorageCount[$item->getItem()->getVanillaName()] = 0;
            }
            $virtualStorageCount = $this->virtualStorageCount[$item->getItem()->getVanillaName()];
            $allCount = $inventory + $virtualStorageCount;
            if ($allCount === 0) continue;
            $this->itemAllCount[$item->getItem()->getVanillaName()] = $allCount;
            $this->toggles[$item->getItem()->getVanillaName()] =
                new Toggle($item->getDisplayName() . '/ 購入: ' . number_format($item->getBuy()) . '円 / 売却: ' . number_format($item->getSell()) . '円' . PHP_EOL .
                    'Inventory: ' . number_format($inventory) . '/ VStorage: ' . number_format($virtualStorageCount) . '/ Total: ' . number_format($allCount), false);
        }
        if (isset($this->toggles)) {
            $this->addElements(new Label(TextFormat::RED . 'アイテムカテゴリの中に所持しているアイテムが存在しない為アイテムリストは表示されませんでした'));
            return;
        }
        foreach ($this->toggles as $toggle) {
            $this->addElement($toggle);
        }
    }

    public function handleSubmit(Player $player) : void {
        if (isset($this->toggles)) {
            SendMessage::Send($player, 'アイテムカテゴリに所持しているアイテムが存在しない為何も処理されませんでした', ItemShopAPI::PREFIX, false);
            $player->sendMessage(TextFormat::RED . 'アイテムカテゴリに所持しているアイテムが存在しない為何も処理されませんでした');
            return;
        }
        foreach ($this->toggles as $name => $toggle) {
            if (!$toggle->getValue()) continue;
            foreach ($this->categoryItems as $item) {
                if ($item->getItem()->getVanillaName() !== $name) continue;
                ItemSell::getInstance()->transaction($player, $this->itemAllCount[$name], $item, $this->virtualStorageCount[$name], true);
            }
        }
    }

    public function handleClosed(Player $player) : void {
        LevelCheck::sendForm($player, new ItemSelectForm($player, $this->shopNumber, $this->category), RestrictionShop::getInstance()->getRestrictionByShopNumber($this->shopNumber));
    }

}
