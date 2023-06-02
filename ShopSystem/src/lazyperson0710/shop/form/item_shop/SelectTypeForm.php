<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\shop\form\item_shop\element\SendMenuFormButton;
use lazyperson0710\shop\form\item_shop\future\ItemHoldingCalculation;
use lazyperson0710\shop\form\item_shop\future\RestrictionShop;
use lazyperson0710\shop\object\ItemShopObject;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;
use function number_format;
use const PHP_EOL;

class SelectTypeForm extends SimpleForm {

    public function __construct(Player $player, ItemShopObject $item) {
        var_dump('selectTypeForm');
        $restriction = RestrictionShop::getInstance()->getRestrictionByShopNumber($item->getShopId());
        $this
            ->setTitle('Item Shop')
            ->addElements(
                new SendMenuFormButton('購入画面に進む', new ItemBuyForm($player, $item), $restriction),
                new SendMenuFormButton('売却画面に進む', new ItemSellForm($player, $item), $restriction),
            );
        StackStorageAPI::$instance->getCount($player->getXuid(), $item->getItem(),
            function ($virtualStorageItemCount) use ($player, $item) : void {
                $this->setText(self::getLabel($player, $item, $virtualStorageItemCount));
            }, function () use ($player, $item) : void {
                $this->setText(self::getLabel($player, $item, 0));
            },
        );
    }

    public static function getLabel(Player $player, ItemShopObject $item, int $virtualStorageItemCount) : string {
        $inventoryItemCount = ItemHoldingCalculation::getInstance()->getHoldingCount($player, $item);
        return 'アイテム名: ' . $item->getDisplayName() . PHP_EOL .
            '購入価格: ' . number_format($item->getBuy()) . '円 / (x64' . number_format(($item->getBuy() * 64)) . '円' . PHP_EOL .
            '売却価格: ' . number_format($item->getSell()) . '円 / (x64' . number_format(($item->getSell() * 64)) . '円' . PHP_EOL . PHP_EOL .
            '現在の所持金: ' . number_format(EconomyAPI::getInstance()->myMoney($player)) . '円' . PHP_EOL .
            'インベントリに所有している数: ' . number_format($inventoryItemCount) . '個' . PHP_EOL .
            '仮想ストレージに所有している数: ' . number_format($virtualStorageItemCount) . '個' . PHP_EOL .
            '合計所持数(インベントリ + 仮想ストレージ): ' . number_format($inventoryItemCount + $virtualStorageItemCount) . '個';
    }

}
