<?php

declare(strict_types=1);
namespace lazyperson710\core\listener;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\donation\Donation_10000;
use lazyperson0710\PlayerSetting\object\settings\donation\Donation_1500;
use lazyperson0710\PlayerSetting\object\settings\normal\JoinItemsSetting;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use function in_array;

class JoinPlayerEvent implements Listener {

	public static array $joinMessage = [];

	public const NBT_ROOT = "sff";
	public const NBT_ID = "id";

	public const ID_INFORMATION = 1;
	public const ID_COMMAND_EXECUTION = 2;
	public const ID_WARP = 3;
	public const ID_SETTINGS = 4;
	public const ID_TOS = 15;

	/**
	 * @return void
	 * @priority HIGHEST
	 */
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		if (!$player->hasPlayedBefore()) {
			$this->setItem($player, VanillaItems::BOOK(), self::ID_TOS, "利用規約本", ["タップすると利用規約を表示します", "利用規約web版 -> https://www.nkserver.net/tos.html"]);
			self::$joinMessage[$player->getName()][] = "初めまして！ようこそなまけものサーバーへ！";
			self::$joinMessage[$player->getName()][] = "最初は利用規約に同意して機能を開放してください";
			self::$joinMessage[$player->getName()][] = "/passコマンドを使用するか前に進むことで利用規約に同意できます";
		} else self::$joinMessage[$player->getName()][] = "なまけものサーバーにようこそ！";
		if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(JoinItemsSetting::getName())?->getValue() === true) {
			$this->sendJoinItem($player);
		} else self::$joinMessage[$player->getName()][] = "設定されている為アイテムが付与されませんでした。アイテムは/joinitemから取得可能です";
		$donationNotice = in_array(false, [
			PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(Donation_1500::getName())?->getValue(),
			PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(Donation_10000::getName())?->getValue(),
		], true);
		if ($donationNotice === false) {
			self::$joinMessage[$player->getName()][] = "§l§e☆ : 受け取ってない寄付特典のプレゼントが存在します！";
			self::$joinMessage[$player->getName()][] = "§l§e☆ : /donationコマンドで確認できます";
		}
		if (isset(self::$joinMessage[$player->getName()])) {
			$player->sendMessage("===============");
			foreach (self::$joinMessage[$player->getName()] as $message) {
				$player->sendMessage("§7>> §a" . $message);
			}
			$player->sendMessage("===============");
		}
		self::$joinMessage[$player->getName()] = [];
	}

	public static function sendJoinItem(Player $player) {
		(new JoinPlayerEvent())->setItem($player, VanillaItems::PAPER(), self::ID_INFORMATION, "Information", ["お知らせやサーバーの仕様などが確認できます", "公式サイト -> https://www.nkserver.net"]);
		(new JoinPlayerEvent())->setItem($player, VanillaItems::COOKIE(), self::ID_COMMAND_EXECUTION, "CommandExecution", ["サーバーの主なコマンドが簡単に実行できます"]);
		(new JoinPlayerEvent())->setItem($player, VanillaItems::COMPASS(), self::ID_WARP, "WarpCompass", ["サーバー上の他ワールドに移動することができます"]);
		(new JoinPlayerEvent())->setItem($player, VanillaBlocks::OAK_SAPLING()->asItem(), self::ID_SETTINGS, "PlayerSettings", ["プレイヤーの設定を変更することができます"]);
	}

	public function setItem(Player $player, Item $item, int $id, string $customName, array $lore) {
		$item->setCustomName($customName);
		$item->setLore($lore);
		$item->getNamedTag()->setTag(
			self::NBT_ROOT,
			(new CompoundTag())
				->setInt(self::NBT_ID, $id),
		);
		if (!$player->getInventory()->contains($item) && $player->getInventory()->canAddItem($item)) {
			$player->getInventory()->addItem($item);
		}
	}

}
