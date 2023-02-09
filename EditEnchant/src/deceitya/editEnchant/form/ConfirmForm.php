<?php

declare(strict_types = 1);
namespace deceitya\editEnchant\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use deceitya\editEnchant\InspectionItem;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class ConfirmForm extends CustomForm {

	private EnchantmentInstance $enchant;
	private string $type;
	private int $level;

	public function __construct(Player $player, EnchantmentInstance $enchant, string $type, ?int $level = 0) {
		$this->enchant = $enchant;
		$this->type = $type;
		$this->level = $level;
		$enchantName = $enchant->getType()->getName();
		if ($enchantName instanceof Translatable) {
			$enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
		}
		$mode = '未定義のエラー';
		$cost = '未定義のエラー';
		switch ($this->type) {
			case 'del':
				$mode = '削除';
				$cost = $enchant->getLevel() * 3000;
				break;
			case 'reduce':
				$mode = '削減';
				$cost = $this->level * 1500;
				break;
		}
		$this
			->setTitle('Edit Enchant')
			->addElements(
				new Label("本当に{$enchantName}(Lv{$enchant->getLevel()})を{$mode}しますか？\n\n削除は削除したいエンチャントのレベルにつき3000円\n削減は削減したいエンチャントのレベルにつき1500円となります"),
				new Label("コスト -> {$cost}円"),
			);
	}

	public function handleSubmit(Player $player) : void {
		if (!(new InspectionItem())->inspectionItem($player)) {
			SendMessage::Send($player, '所持しているアイテムが変更された可能性がある為処理を中断しました', 'EditEnchant', false);
			return;
		}
		$inv = $player->getInventory();
		$item = $inv->getItemInHand();
		$enchantName = $this->enchant->getType()->getName();
		if ($enchantName instanceof Translatable) {
			$enchantName = Server::getInstance()->getLanguage()->translate($enchantName);
		}
		switch ($this->type) {
			case 'reduce':
				$cost = $this->level * 1500;
				if ($cost <= EconomyAPI::getInstance()->myMoney($player)) {
					if ($this->enchant->getLevel() - $this->level < 1) {
						SendMessage::Send($player, 'エンチャントレベルが0以下になってしまうため実行出来ませんでした', 'EditEnchant', false);
						return;
					}
					$inv->removeItem($item);
					$item->removeEnchantment($this->enchant->getType(), $this->level);
					$item->addEnchantment(new EnchantmentInstance($this->enchant->getType(), $this->enchant->getLevel() - $this->level));
					$inv->addItem($item);
					EconomyAPI::getInstance()->reduceMoney($player, $cost);
					SendMessage::Send($player, "{$enchantName}(Lv{$this->enchant->getLevel()})を{$cost}円で{$this->level}レベル削減しました", 'EditEnchant', true);
				} else {
					SendMessage::Send($player, 'お金が足りないため実行出来ませんでした', 'EditEnchant', false);
				}
				break;
			case 'del':
				$cost = $item->getEnchantment($this->enchant->getType())->getLevel() * 3000;
				if ($cost <= EconomyAPI::getInstance()->myMoney($player)) {
					$inv->removeItem($item);
					$item->removeEnchantment($this->enchant->getType());
					if (empty($item->getEnchantments())) {
						$item->removeEnchantments();
					}
					$inv->addItem($item);
					EconomyAPI::getInstance()->reduceMoney($player, $cost);
					SendMessage::Send($player, "{$enchantName}(Lv{$this->enchant->getLevel()})を{$cost}円で削除しました", 'EditEnchant', true);
				} else {
					SendMessage::Send($player, 'お金が足りないため実行出来ませんでした', 'EditEnchant', false);
				}
		}
	}

}
