<?php

declare(strict_types = 0);

namespace Deceitya\MiningLevel\Event;

use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Embed;
use bbo51dog\pmdiscord\element\Embeds;
use DateTime;
use DateTimeInterface;
use Deceitya\MiningLevel\Form\MiningLevelUPForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use InfoSystem\InfoSystem;
use InfoSystem\task\ChangeNameTask;
use lazyperson0710\miningtools\event\MiningToolsBreakEvent;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\LevelUpDisplaySetting;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendBroadcastMessage;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundActionBarMessage;
use lazyperson710\core\packet\SendToastPacket;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use ree_jp\stackStorage\api\StackStorageAPI;
use function explode;
use function in_array;
use function is_null;

class EventListener implements Listener {

	use SingletonTrait;

	private array $config;

	public function __construct(Config $config) {
		self::setInstance($this);
		$this->config = $config->getAll();
	}

	public function onPlayerJoin(PlayerJoinEvent $event) {
		$api = MiningLevelAPI::getInstance();
		$player = $event->getPlayer();
		if (!$api->playerDataExists($player)) {
			$api->createPlayerData($player);
		}
	}

	/*
	public function Quit(PlayerQuitEvent $event){
		  MiningLevelAPI::getInstance()->clearCache($event->getPlayer()->getName());
	}
*/
	/**
	 * @priority HIGHEST
	 */
	public function onBlockBreak(BlockBreakEvent $event) {
		if ($event->isCancelled()) {
			return;
		}
		$player = $event->getPlayer();
		$this->getBreakEvent($event, $player);
	}

	/**
	 * @priority HIGHEST
	 */
	public function CountBlock(MiningToolsBreakEvent $event) : void {
		if ($event->isCancelled()) {
			return;
		}
		$player = $event->getPlayer();
		$this->getBreakEvent($event, $player);
	}

	public function LevelCalculation($player, $exp) : void {
		$api = MiningLevelAPI::getInstance();
		$originalLevel = $api->getLevel($player);
		$level = $originalLevel;
		$upExp = $api->getLevelUpExp($player);
		for ($up = 0; $exp >= $upExp; $up++) {
			$exp -= $upExp;
			$upExp += $level;
			$level++;
			(function (Player $player, int $originalLevel, int $level) {
				(new MiningLevelUpEvent($player, $originalLevel, $level))->call();
				$this->LevelUpBonus($player, $originalLevel, $level);
				$this->DiscordWebHook($player, $originalLevel, $level);
				InfoSystem::getInstance()->getScheduler()->scheduleDelayedTask(new ChangeNameTask([$player]), 10);
				match (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(LevelUpDisplaySetting::getName())?->getValue()) {
					'title' => $player->sendtitle('Mining Level UP', "Lv.{$originalLevel} -> Lv.{$level}"),
					'toast' => SendToastPacket::Send($player, 'Mining Level UP Message', "Lv.{$originalLevel}からLv.{$level}にレベルアップしました！"),
					default => null,
				};
				SoundPacket::Send($player, 'random.levelup');
			})($player, $originalLevel, $level);
		}
		$api->setLevel($player, $level);
		$api->setExp($player, $exp);
		$api->setLevelUpExp($player, $upExp);
	}

	private function getBreakEvent(BlockBreakEvent|MiningToolsBreakEvent $event, Player $player) : void {
		if (in_array($player->getPosition()->getWorld()->getFolderName(), $this->config['world'], true)) {
			return;
		}
		$api = MiningLevelAPI::getInstance();
		$block = $event->getBlock();
		$exp = ($this->config[$block->getId() . ':' . $block->getMeta()] ?? $this->config['default'] ?? 0) + $api->getExp($player);
		$this->LevelCalculation($player, $exp);
	}

	private function DiscordWebHook(Player $player, int $originalLevel, int $level) : void {
		if ($level % 5 == 0) {
			$name = $player->getName();
			$webhook = Webhook::create('https://discord.com/api/webhooks/931209546603593791/UPL48PM8DQtUwb0ulupP3i1xgL3JmvQ4zN87Wo6Il0ynGgLRsBfGT076cPdPF9HzYS5N');
			$embed = (new Embed())
				->setTitle("{$name}がLv.{$originalLevel}からLv.{$level}にレベルアップしました")
				->setColor(13421619)
				->setAuthorName('Mining Level Up')
				->setTime((new DateTime())->format(DateTimeInterface::ATOM));
			$embeds = new Embeds();
			$embeds->add($embed);
			$webhook->add($embeds);
			$webhook->send();
		}
	}

	private function LevelUpBonus(Player $player, int $originalLevel, int $level) {
		if (isset($this->config['item'][$level])) {
			$data = explode(':', $this->config['item'][$level]);
			$item = ItemFactory::getInstance()->get((int) $data[0], (int) $data[1], (int) $data[2]);
			if ($player->getInventory()->canAddItem($item)) {
				$player->getInventory()->addItem($item);
			} else {
				StackStorageAPI::$instance->add($player->getXuid(), $item);
				SendNoSoundActionBarMessage::Send($player, 'インベントリに空きが無いため' . $item->getName() . 'が倉庫にしまわれました', 'Storage', true);
			}
			SendMessage::Send($player, "レベルアップボーナスとして{$item->getName()}が付与されました", 'Level', true);
		} elseif ($level % 5 == 0) {
			EconomyAPI::getInstance()->addMoney($player, 8000);
			SendMessage::Send($player, 'レベルアップボーナスとして8000円が付与されました', 'Level', true);
		}
		if ($level % 50 == 0) {
			SendBroadcastMessage::Send("{$player->getName()}がLv.{$originalLevel}からLv.{$level}にレベルアップしました", 'Level');
		} else {
			SendMessage::Send($player, "{$player->getName()}がLv.{$originalLevel}からLv.{$level}にレベルアップしました", 'Level', true);
		}
		$msg = null;
		switch ($level) {
			case 2:
				SendMessage::Send($player, TextFormat::GOLD . '/shopでアイテムを売買できます！', 'Tutorial', true);
				break;
			case 3:
				SendMessage::Send($player, TextFormat::GOLD . 'アイテムを修繕するにはかなとこをスニークしてタップしてみて！', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを3枚配布しました！/gachaで利用することができます', 'Tutorial', true);
				TicketAPI::getInstance()->addTicket($player, 3);
				break;
			case 5:
				SendMessage::Send($player, TextFormat::GOLD . 'ストレージは/stを活用してみよう', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'ネザーディメンション1が解放されました！', 'Tutorial', true);
				break;
			case 8:
				SendMessage::Send($player, TextFormat::GOLD . 'エンチャントなどをしたいときは/en、エフェクトは/efでできるよ！', 'Tutorial', true);
				break;
			case 10:
				SendMessage::Send($player, TextFormat::GOLD . '死ぬと所持金の半分がなくなります。。。銀行を活用してみよう！銀行に預けると死んでも預けた分の残高は消えません！/bank', 'Tutorial', true);
				break;
			case 15:
				SendMessage::Send($player, TextFormat::GOLD . 'オーバーワールド4&javaが解放されました！通常よりも鉱石の量が多いのが特徴です。ただし、javaのワールドはmininglevel経験値が取得できないため注意が必要です！', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'DiamondMiningToolsが解放されました！範囲破壊が可能ですがDiamondグレードでは修繕ができないためご注意ください。。。', 'Tutorial', true);
				break;
			case 18:
				SendMessage::Send($player, TextFormat::GOLD . 'ログインボーナスをアイテムと交換するには/bonusでできます！', 'Tutorial', true);
				break;
			case 20:
				SendMessage::Send($player, TextFormat::GOLD . '浜松市が解放されました！25レベルでshop2が解放されます！', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'ネザーディメンション2,3が解放されました！', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを15枚配布しました！', 'Tutorial', true);
				TicketAPI::getInstance()->addTicket($player, 15);
				break;
			case 25:
				SendMessage::Send($player, TextFormat::GOLD . 'LevelShop2が解放されました！浜松市に行って農業をやってみよう！', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'MyWarpでのワープ地点上限が5 -> 10になりました！/mw', 'Tutorial', true);
				break;
			case 30:
				SendMessage::Send($player, TextFormat::GOLD . 'サーバーオリジナルレシピが存在します！/recipeから見れるよ！', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'PVPワールドが解放されました！/pvp', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . '連続で同じメッセージを発言できるようになりました', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'インベントリからアイテムを一括売却できる機能が解放されました！/shop', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'shopにてアイテムを検索する機能が解放されました/shop', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'MiningWorldが解放されました！一週間に一度リセットされる特殊なワールドです！', 'Tutorial', true);
				break;
			case 45:
				SendMessage::Send($player, TextFormat::GOLD . 'ショップ2が解放されました！/shopから確認してみよう！', 'Tutorial', true);
				break;
			case 50:
				SendMessage::Send($player, TextFormat::GOLD . 'LevelShop3が解放されました！/shop', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'MyWarpでのワープ地点上限が10 -> 15になりました！/mw', 'Tutorial', true);
				break;
			case 80:
				SendMessage::Send($player, TextFormat::GOLD . 'Shop4が解放されました！/shop', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'コマンドから道具の修繕が可能になりました！/repair', 'Tutorial', true);
				break;
			case 100:
				SendMessage::Send($player, TextFormat::GOLD . 'アイテムの修繕で失敗しないようになりました！/repair', 'Tutorial', true);
				break;
			case 120:
				SendMessage::Send($player, TextFormat::GOLD . 'LevelShop5が解放されました！/shop', 'Tutorial', true);
				SendMessage::Send($player, TextFormat::GOLD . 'NetheriteMiningTools機能強化が解放されました！Gachaで手に入るアイテムを使って強化しよう！範囲拡張や耐久強化などが出来ます！/mt', 'Tutorial', true);
				break;
			case 180:
				SendMessage::Send($player, TextFormat::GOLD . 'LevelShop6が解放されました！/shop', 'Tutorial', true);
				break;
			case 200:
				SendMessage::Send($player, TextFormat::GOLD . 'エンドディメンション1~3が解放されました！', 'Tutorial', true);
				break;
			case 250:
				SendMessage::Send($player, TextFormat::GOLD . 'LevelShop7が解放されました！/shop', 'Tutorial', true);
				break;
			case 300:
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを3000枚配布しました！', 'LevelUpBonus', true);
				TicketAPI::getInstance()->addTicket($player, 3000);
				break;
			case 400:
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを4000枚配布しました！', 'LevelUpBonus', true);
				TicketAPI::getInstance()->addTicket($player, 4000);
				break;
			case 500:
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを1.5万枚配布しました！', 'LevelUpBonus', true);
				TicketAPI::getInstance()->addTicket($player, 15000);
				break;
			case 600:
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを1.6万枚配布しました！', 'LevelUpBonus', true);
				TicketAPI::getInstance()->addTicket($player, 16000);
				break;
			case 700:
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを1.7万枚配布しました！', 'LevelUpBonus', true);
				TicketAPI::getInstance()->addTicket($player, 17000);
				break;
			case 800:
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを1.8万枚配布しました！', 'LevelUpBonus', true);
				TicketAPI::getInstance()->addTicket($player, 18000);
				break;
			case 900:
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを1.9万枚配布しました！', 'LevelUpBonus', true);
				TicketAPI::getInstance()->addTicket($player, 19000);
				break;
			case 1000:
				SendMessage::Send($player, TextFormat::GOLD . 'ガチャTicketを10万枚配布しました！', 'LevelUpBonus', true);
				TicketAPI::getInstance()->addTicket($player, 100000);
				break;
		}
		if (!is_null($msg)) {
			SendForm::Send($player, (new MiningLevelUPForm($msg)));
		}
	}

}
