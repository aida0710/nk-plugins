<?php

namespace Deceitya\MiningLevel\Event;

use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Embed;
use bbo51dog\pmdiscord\element\Embeds;
use DateTime;
use DateTimeInterface;
use Deceitya\MiningLevel\Form\MiningLevelUPForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\event\MiningToolsBreakEvent;
use InfoSystem\InfoSystem;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\LevelUpTitleSetting;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use ree_jp\stackStorage\api\StackStorageAPI;

class EventListener implements Listener {

    /** @var array */
    private array $config;

    public function __construct(Config $config) {
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
    public function CountBlock(MiningToolsBreakEvent $event): void {
        if ($event->isCancelled()) {
            return;
        }
        $player = $event->getPlayer();
        $this->getBreakEvent($event, $player);
    }

    public function getBreakEvent(BlockBreakEvent|MiningToolsBreakEvent $event, Player $player) {
        $player = $event->getPlayer();
        if (in_array($player->getPosition()->getWorld()->getFolderName(), $this->config['world'])) {
            return;
        }
        $api = MiningLevelAPI::getInstance();
        $block = $event->getBlock();
        $exp = ($this->config[$block->getId() . ':' . $block->getMeta()] ?? $this->config['default'] ?? 0) + $api->getExp($player);
        $originalLevel = $api->getLevel($player);
        $level = $originalLevel;
        $upexp = $api->getLevelUpExp($player);
        for ($up = 0; $exp >= $upexp; $up++) {
            $exp -= $upexp;
            $upexp += $level;
            $level++;
            if (isset($this->config['item'][$level])) {
                $data = explode(':', $this->config['item'][$level]);
                $item = ItemFactory::getInstance()->get($data[0], $data[1], $data[2]);
                if ($player->getInventory()->canAddItem($item)) {
                    $player->getInventory()->addItem($item);
                } else {
                    StackStorageAPI::$instance->add($player->getXuid(), $item);
                    $player->sendActionBarMessage("§bStorage §7>> §aインベントリに空きが無いため" . $item->getName() . "が倉庫にしまわれました");
                }
                $player->sendMessage("§bLevel §7>> §aレベルアップボーナスとして{$item->getName()}が付与されました");
            } elseif ($level % 5 == 0) {
                EconomyAPI::getInstance()->addMoney($player, 8000);
                $player->sendMessage("§bLevel §7>> §aレベルアップボーナスとして8000円が付与されました");
            }
        }
        $api->setLevel($player, $level);
        $api->setExp($player, $exp);
        $api->setLevelUpExp($player, $upexp);
        $name = $player->getName();
        if ($up > 0) {
            if ($level % 5 == 0) {
                $name = $player->getName();
                $webhook = Webhook::create("https://discord.com/api/webhooks/931209546603593791/UPL48PM8DQtUwb0ulupP3i1xgL3JmvQ4zN87Wo6Il0ynGgLRsBfGT076cPdPF9HzYS5N");
                $embed = (new Embed())
                    ->setTitle("{$name}がLv.{$originalLevel}からLv.{$level}にレベルアップしました")
                    ->setColor(13421619)
                    ->setAuthorName("Mining Level Up")
                    ->setTime((new DateTime())->format(DateTimeInterface::ATOM));
                $embeds = new Embeds();
                $embeds->add($embed);
                $webhook->add($embeds);
                $webhook->send();
            }
            if ($level % 50 == 0) {
                $player->getServer()->broadcastMessage("§bLevel §7>> §e{$name}がLv.{$originalLevel}からLv.{$level}にレベルアップしました");
            } else {
                $player->sendMessage("§bLevel §7>> §a{$name}がLv.{$originalLevel}からLv.{$level}にレベルアップしました");
                if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(LevelUpTitleSetting::getName())?->getValue() === true) {
                    $player->sendtitle("§bMining Level UP", "§aLv.{$originalLevel} -> Lv.{$level}");
                }
                $msg = null;
                switch ($level) {
                    case 2:
                        $player->sendMessage("§bTutorial §7>> §g/shopでアイテムを売買できます！");
                        break;
                    case 3:
                        $player->sendMessage("§bTutorial §7>> §gアイテムを修繕するにはかなとこをスニークしてタップしてみて！");
                        $player->sendMessage("ガチャTicketを3枚配布しました！/gachaで利用することができます");
                        TicketAPI::getInstance()->addTicket($player, 3);
                        break;
                    case 5:
                        $player->sendMessage("§bTutorial §7>> §gストレージは/stを活用してみよう");
                        $player->sendMessage("§bTutorial §7>> §gネザーディメンション1が解放されました！");
                        break;
                    case 8:
                        $player->sendMessage("§bTutorial §7>> §gエンチャントなどをしたいときは/en、エフェクトは/efでできるよ！");
                        break;
                    case 10:
                        $player->sendMessage("§bTutorial §7>> §g死ぬと所持金の半分がなくなります。。。銀行を活用してみよう！銀行に預けると死んでもアイテムは消えません！/bank");
                        break;
                    case 15:
                        $msg .= "インベントリの物を一気にストレージに入れたいときは/stall !!\n";
                        $msg .= "オーバーワールド4&javaが解放されました！通常よりも鉱石の量が多いのが特徴です。ただし、javaのワールドはmininglevel経験値が取得できないため注意が必要です！\n";
                        $msg .= "DiamondMiningToolsが解放されました！範囲破壊が可能ですがDiamondグレードでは修繕ができないためご注意ください。。。\n";
                        break;
                    case 18:
                        $player->sendMessage("§bTutorial §7>> §gログインボーナスをアイテムと交換するには/bonusでできます！");
                        break;
                    case 20:
                        $player->sendMessage("§bTutorial §7>> §g浜松市が解放されました！25レベルでshop2が解放されます！");
                        $player->sendMessage("§bTutorial §7>> §gネザーディメンション2,3が解放されました！");
                        $player->sendMessage("ガチャTicketを15枚配布しました！");
                        TicketAPI::getInstance()->addTicket($player, 15);
                        break;
                    case 25:
                        $player->sendMessage("LevelShop2が解放されました！浜松市に行って農業をやってみよう！\n");
                        $player->sendMessage("MyWarpでのワープ地点上限が5 -> 10になりました！/mw\n");
                        break;
                    case 30:
                        $msg .= "サーバーオリジナルレシピが存在します！/recipeから見れるよ！\n";
                        $msg .= "PVPワールドが解放されました！/pvp\n";
                        $msg .= "連続で同じメッセージを発言できるようになりました\n";
                        $msg .= "インベントリからアイテムを一括売却できる機能が解放されました！/shop\n";
                        $msg .= "shopにてアイテムを検索する機能が解放されました/shop\n";
                        $msg .= "MiningWorldが解放されました！一週間に一度リセットされる特殊なワールドです！\n";
                        break;
                    case 45:
                        $player->sendMessage("§bTutorial §7>> §gショップ2が解放されました！/shopから確認してみよう！");
                        break;
                    case 50:
                        $msg .= "LevelShop3が解放されました！/shop";
                        $msg .= "MyWarpでのワープ地点上限が10 -> 15になりました！/mw";
                        break;
                    case 80:
                        $msg .= "Shop4が解放されました！/shop";
                        $msg .= "コマンドから道具の修繕が可能になりました！/repair";
                        break;
                    case 100:
                        $player->sendMessage("§bTutorial §7>> §gアイテムの修繕で失敗しないようになりました！/repair");
                        break;
                    case 120:
                        $msg .= "LevelShop5が解放されました！/shop";
                        $msg .= "NetheriteMiningTools機能強化が解放されました！Gachaで手に入るアイテムを使って強化しよう！範囲拡張や耐久強化などが出来ます！/mt";
                        break;
                    case 180:
                        $player->sendMessage("§bTutorial §7>> §gLevelShop6が解放されました！/shop");
                        break;
                    case 200:
                        $player->sendMessage("§bTutorial §7>> §gエンドディメンション1~3が解放されました！");
                        break;
                    case 250:
                        $player->sendMessage("§bTutorial §7>> §gLevelShop7が解放されました！/shop");
                        break;
                }
                if (!is_null($msg)) {
                    SendForm::Send($player, (new MiningLevelUPForm($msg)));
                }
            }
            (new MiningLevelUpEvent($player, $originalLevel, $level))->call();
            /** @var InfoSystem $plugin */
            $plugin = $player->getServer()->getPluginManager()->getPlugin("InfoSystem");
            $plugin->ChangeTag($player);
        }
    }

}
