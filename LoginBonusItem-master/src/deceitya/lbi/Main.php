<?php

namespace deceitya\lbi;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase implements Listener {

    use SingletonTrait;

    /** @var Config $lastBonusDateConfig */
    public static $lastBonusDateConfig;

    public function onLoad(): void {
        self::setInstance($this);
    }

    public function onEnable(): void {
        date_default_timezone_set('Asia/Tokyo');
        $this->reloadConfig();
        self::$lastBonusDateConfig = new Config($this->getDataFolder() . "lastBonus.yml", Config::YAML);
        $this->getScheduler()->scheduleRepeatingTask(new CheckChangeDateTask($this), 20 * 60);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function PlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if (date("Y/m/d") !== date("Y/m/d", explode('.', $player->getLastPlayed() / 1000)[0])) {
            self::check($player, true, true);
            return;
        }
        if (self::$lastBonusDateConfig->exists($player->getName())) {
            self::check($player);
        }
    }

    public static function check(Player $player, $save = true, $new = false): void {
        $lastBonus = self::$lastBonusDateConfig->get($player->getName(), []);
        if ($new === true) {
            foreach (self::getInstance()->getConfig()->getAll() as $key => $value) {
                $data = explode(":", $value);
                $lastBonus[$key] = ($lastBonus[$key] ?? 0) + ((int)$data[2]);
            }
        }
        $items = self::getBonusItems($lastBonus);
        $result = [];
        foreach ($items as $key => $item) {
            $newitem = $player->getInventory()->addItem($item);
            if (isset($newitem[0])) {
                $result[$key] = $newitem[0]->getCount();
            }
        }
        if (count($result) === 0) {
            self::$lastBonusDateConfig->remove($player->getName());
        } else {
            self::$lastBonusDateConfig->set($player->getName(), $result);
        }
        if ($save === true) {
            self::$lastBonusDateConfig->save();
        }
        $player->sendMessage((count($result) === 0) ?
            '§bLogin §7>> §aログインボーナスを受け取りました' :
            '§bLogin §7>> §aインベントリに空きがないため、ログインボーナスを受け取れませんでした。/bonusitemから保留になっているログインボーナスアイテムを受け取ろう');
    }

    /**
     * @param array<int, int> $array
     * @return Item[]
     */
    public static function getBonusItems(array $array): array {
        $result = [];
        foreach (self::getInstance()->getConfig()->getAll() as $key => $value) {
            if (!isset($array[$key])) {
                continue;
            }
            $result[$key] = self::decodeItem($value, $array[$key]);
        }
        return $result;
    }

    public static function decodeItem(string $value, ?int $count = null): Item {
        $data = explode(':', $value);
        $item = ItemFactory::getInstance()->get((int)$data[0], (int)$data[1], (int)$data[2]);
        if (count($data) === 5) {
            $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId((int)$data[3]), (int)$data[4]));
            $item->setCustomName("Login Bonus");
            $item->setLore([
                "アイテムを持ってタップすることでログインボーナスとアイテムを交換することができます",
            ]);
        }
        if ($count !== null) {
            $item->setCount($count);
        }
        return $item;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        /** @var Player $sender */
        if ($label === "bonuscollect") {
            if (self::$lastBonusDateConfig->exists($sender->getName())) {
                $form = new BonusViewForm($sender);
                $sender->sendForm($form);
                //self::check($sender);
                return true;
            }
            $sender->sendMessage("§bLogin §7>> §a現在、保留しているログインボーナスはありません。");
            return true;
        }
        if ($label === "forcebonuscollect") {
            if (!Server::getInstance()->isOp($sender->getName())) return true;
            $count = $args[0] ?? 1;
            $player = $sender;
            if (isset($args[1])) {
                $array = $args;
                unset($array[0]);
                $name = implode(" ", $array);
                if ($this->getServer()->getApiVersion()[0] === "3") {
                    $player = $this->getServer()->getOfflinePlayer($name);//pmmp api 3
                } else {
                    $player = $this->getServer()->getPlayerByPrefix($name);//pmmp api 4
                }
            }
            if ($player === null) {
                $sender->sendMessage("指定しました、ユーザー様に関しては、サーバーに存在しない為、ログインボーナスを錬成する事はできません。");
                return true;
            }
            for ($i = 1; $i <= $count; $i++) {
                self::check($player, false, true);
            }
            self::$lastBonusDateConfig->save();
            return true;
        }
        return true;
    }
}
