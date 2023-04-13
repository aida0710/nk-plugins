<?php

declare(strict_types = 1);

namespace lazyperson0710\ShopSystem\form\levelShop;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopSystem\form\levelShop\element\SendMenuFormButton;
use lazyperson0710\ShopSystem\form\levelShop\future\ItemHoldingCalculation;
use lazyperson0710\ShopSystem\form\levelShop\future\RestrictionShop;
use lazyperson0710\ShopSystem\form\levelShop\future\ShopText;
use lazyperson0710\ShopSystem\object\ShopItem;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;

class SelectTypeForm extends SimpleForm implements ShopText {

	public function __construct(Player $player, ShopItem $item) {
		$restriction = RestrictionShop::getInstance()->getRestrictionByShopNumber($item->getShopId());
		$this
			->setTitle(self::TITLE)
			->setText(
				'アイテム名: ' . $item->getDisplayName() . PHP_EOL .
				'購入価格: ' . number_format($item->getBuy()) . '円 / (x64' . number_format(($item->getBuy() * 64)) . '円' . PHP_EOL .
				'売却価格: ' . number_format($item->getSell()) . '円 / (x64' . number_format(($item->getSell() * 64)) . '円' . PHP_EOL . PHP_EOL .
				'現在の所持金: ' . number_format(EconomyAPI::getInstance()->myMoney($player)) . '円' . PHP_EOL .
				'インベントリに所有している数: ' . number_format(ItemHoldingCalculation::getInstance()->getHoldingCount($player, $item)) . '個' . PHP_EOL .
				'仮想ストレージに所有している数: ' . number_format(ItemHoldingCalculation::getInstance()->getVirtualStorageCount($player, $item)) . '個',
			)
			->addElements(
				new SendMenuFormButton('購入画面に進む', new ItemBuyForm($player, $item), $restriction),
				new SendMenuFormButton('売却画面に進む', new ItemSellForm($player, $item), $restriction),
			);
	}

}