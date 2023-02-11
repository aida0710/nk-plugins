<?php

declare(strict_types = 0);
namespace lazyperson710\sff\form\donation;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use Error;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\donation\Donation_10000;
use lazyperson0710\PlayerSetting\object\settings\donation\Donation_1500;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendForm;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use function is_null;

class DonationAcceptanceForm extends CustomForm {

	private Toggle $enable;

	public function __construct(
		private Player $player,
		private int $num,
		private string $donor,
	) {
		$amount = DonationForm::DonationAmount;
		$setting = PlayerSettingPool::getInstance()->getSettingNonNull($this->player);
		$message = null;
		$give = false;
		switch ($this->num) {
			case 1500:
				if (!$setting->getSetting(Donation_1500::getName())?->getValue()) {
					$give = true;
					$message = "チケットを100枚受け取ることが可能です。受け取りますか？\n\n寄付者 : {$this->donor}\n\n寄付金額 : 5000円";
				} else {
					$message = "既にチケットを受け取っています\n\n寄付者 : {$this->donor}\n\n寄付金額 : 5000円";
				}
				break;
			case 3000:
				$message = "道具の名前変更機能の実装\nCommand : /items\n\n寄付者 : {$this->donor}\n\n寄付金額 : 5000円";
				break;
			case 5000:
				$message = "ガチャに様々な効果を付与するアイテムの追加実装\nCommand : /gacha\n\n寄付者 : {$this->donor}\n\n寄付金額 : 5000円";
				break;
			case 8000:
				$message = "ReimariDarknessのログイン時のメッセージを変更。ログイン時の音を出力\n\n寄付者 : {$this->donor}\n\n寄付金額 : 6000円";
				break;
			case 10000:
				if (!$setting->getSetting(Donation_10000::getName())?->getValue()) {
					$give = true;
					$message = "ダイヤモンドを15個受け取ることが可能です。受け取りますか？\n\n寄付者 : {$this->donor}\n\n寄付金額 : 6000円";
				} else {
					$message = "既にアイテムを受け取っています\n\n寄付者 : {$this->donor}";
				}
				break;
			case 15000:
			default:
				break;
		}
		if (is_null($message)) {
			$message = "現在寄付額が足りていない為特典を受け取ることができません\n\n現在の寄付総額 : {$amount}円 | 必要寄付額 : {$num}円";
		}
		$this->enable = new Toggle('特典を受け取る', false);
		$this
			->setTitle('Donation Form')
			->addElement(new Label($message));
		if ($give) {
			$this->addElement($this->enable);
		} else $this->enable->setValue(false);
	}

	public function handleSubmit(Player $player) : void {
		$message = null;
		switch ($this->num) {
			case 1500:
				if (!$this->enable->getValue()) {
					break;
				}
				$setting = PlayerSettingPool::getInstance()->getSettingNonNull($this->player);
				if (!$setting->getSetting(Donation_1500::getName())?->getValue()) {
					TicketAPI::getInstance()->addTicket($this->player, 100);
					$setting->getSetting(Donation_1500::getName())?->setValue(true);
					$message = "\n\n§sチケットを100枚受け取りました";
					break;
				} else throw new Error('既にチケットを受け取っています');
			case 10000:
				if (!$this->enable->getValue()) {
					break;
				}
				$setting = PlayerSettingPool::getInstance()->getSettingNonNull($this->player);
				if (!$setting->getSetting(Donation_10000::getName())?->getValue()) {
					if (!$this->player->getInventory()->canAddItem(VanillaItems::DIAMOND()->setCount(15))) {
						$message = "\n\n§cインベントリに空きがありません";
						break;
					}
					$this->player->getInventory()->addItem(VanillaItems::DIAMOND()->setCount(15));
					$setting->getSetting(Donation_10000::getName())?->setValue(true);
					$message = "\n\n§sダイアモンドを15個受け取りました";
					break;
				} else throw new Error('既にチケットを受け取っています');
			default:
				break;
		}
		SendForm::Send($player, (new DonationForm($message)));
	}
}
