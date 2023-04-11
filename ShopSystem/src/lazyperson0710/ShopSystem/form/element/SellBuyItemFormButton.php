<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\ShopSystem\database\LevelShopAPI;
use lazyperson0710\ShopSystem\form\levelShop\other\SearchShop\InputItemForm;
use lazyperson0710\ShopSystem\form\levelShop\temp\PurchaseForm;
use lazyperson0710\ShopSystem\form\levelShop\temp\SellBuyForm;
use lazyperson0710\ShopSystem\object\LevelShopItem;
use lazyperson0710\ShopSystem\object\ShopItem;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class SellBuyItemFormButton extends Button {

	private ShopItem $item;

	public function __construct(string $text, ShopItem $item, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->item = $item;
	}

	public function handleSubmit(Player $player) : void {
		$api = LevelShopAPI::getInstance();
		if (MiningLevelAPI::getInstance()->getLevel($player) < LevelShopAPI::getInstance()->getShop($this->itemId, $this->itemMeta)) {
			$error = "§c要求されたレベルに達していない為処理が中断されました\n要求レベル -> lv.{$api->getShop($this->itemId, $this->itemMeta)}\n§r";
			SendForm::Send($player, (new InputItemForm($error)));
			SoundPacket::Send($player, 'dig.chain');
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
		SendForm::Send($player, (new SellBuyForm($this->itemId, $this->itemMeta, LevelShopAPI::getInstance()->getBuy($this->itemId, $this->itemMeta), LevelShopAPI::getInstance()->getSell($this->itemId, $this->itemMeta))));
	}

	public function callback(Player $player, Item $item, int $storage) : void {
		$count = 0;
		$myMoney = (int) EconomyAPI::getInstance()->mymoney($player);
		foreach ($player->getInventory()->getContents() as $inventoryItem) {
			if ($item->getId() === $inventoryItem->getId() && $item->getMeta() === $inventoryItem->getMeta()) {
				$count += $inventoryItem->getCount();
			}
		}
		SendForm::Send($player, (new PurchaseForm(new LevelShopItem($item, LevelShopAPI::getInstance()->getBuy($this->itemId, $this->itemMeta), $count, $myMoney, $storage))));
	}
}
