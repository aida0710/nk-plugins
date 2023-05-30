<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\shop\form\item_shop\common\buy\ItemBuyForm;
use lazyperson0710\shop\form\item_shop\common\sell\ItemSellForm;
use lazyperson0710\shop\form\item_shop\element\SendMenuFormButton;
use lazyperson0710\shop\form\item_shop\future\ItemHoldingCalculation;
use lazyperson0710\shop\form\item_shop\future\RestrictionShop;
use lazyperson0710\shop\form\item_shop\future\ShopText;
use lazyperson0710\shop\object\ItemShopObject;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use function number_format;
use const PHP_EOL;

class SelectTypeForm extends SimpleForm implements ShopText {

	public function __construct(Player $player, ItemShopObject $item) {
		$restriction = RestrictionShop::getInstance()->getRestrictionByShopNumber($item->getShopId());
		$this
			->setTitle(self::TITLE)
			->setText(self::getLabel($player, $item))
			->addElements(
				new SendMenuFormButton('購入画面に進む', new ItemBuyForm($player, $item), $restriction),
				new SendMenuFormButton('売却画面に進む', new ItemSellForm($player, $item), $restriction),
			);
	}

	public static function getLabel(Player $player, ItemShopObject $item) : string {
		$inventoryItemCount = ItemHoldingCalculation::getInstance()->getHoldingCount($player, $item);
		$virtualStorageItemCount = ItemHoldingCalculation::getInstance()->getVirtualStorageCount($player, $item);
		return 'アイテム名: ' . $item->getDisplayName() . PHP_EOL .
			'購入価格: ' . number_format($item->getBuy()) . '円 / (x64' . number_format(($item->getBuy() * 64)) . '円' . PHP_EOL .
			'売却価格: ' . number_format($item->getSell()) . '円 / (x64' . number_format(($item->getSell() * 64)) . '円' . PHP_EOL . PHP_EOL .
			'現在の所持金: ' . number_format(EconomyAPI::getInstance()->myMoney($player)) . '円' . PHP_EOL .
			'インベントリに所有している数: ' . number_format($inventoryItemCount) . '個' . PHP_EOL .
			'仮想ストレージに所有している数: ' . number_format($virtualStorageItemCount) . '個' . PHP_EOL .
			'合計所持数(インベントリ + 仮想ストレージ): ' . number_format($inventoryItemCount + $virtualStorageItemCount) . '個';
	}

}
