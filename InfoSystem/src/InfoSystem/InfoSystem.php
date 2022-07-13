<?php

namespace InfoSystem;

use Deceitya\MiningLevel\MiningLevelAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;

class InfoSystem extends PluginBase implements Listener {

    public const owner = [
        'lazyperson710',
    ];

    public const debugger = [
        'sloth0710',
        'asapon128',
    ];

    /**
     * @var Config
     */
    private $config;

    /**
     * @var EconomyAPI
     */
    private $money;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, ["デフォルト称号" => "§b鯖民"]);
        $this->money = EconomyAPI::getInstance();
        if ($this->money == null) {
            $this->getLogger()->error("§cEconomyAPI が存在しないため、サーバーを終了します");
            $this->getServer()->shutdown();
        }
        $this->level = MiningLevelAPI::getInstance();
        if ($this->level == null) {
            $this->getLogger()->error("§cMiningLevelSystem が存在しないため、サーバーを終了します");
            $this->getServer()->shutdown();
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        $name = $sender->getName();
        if ($label === "tag") {
            if ($sender instanceof Player) {
                $text = "作成したい称号を入力してください。 (15文字まで)\n色変更の部分は字数にカウントされません。\n太文字の使用はできません";
                if (Server::getInstance()->isOp($name)) $text = "作成したい称号を入力してください。 (15文字まで)\n色変更の部分は字数にカウントされません";
                $data = [
                    "type" => "custom_form",
                    "title" => "TagSystem",
                    "content" => [
                        [
                            "type" => "label",
                            "text" => $text,
                        ],
                        [
                            "type" => "input",
                            "text" => "作成する称号",
                            "placeholder" => "ナマケモノ",
                            "default" => "",
                        ],
                    ],
                ];
                $this->createWindow($sender, $data, 78533);
            } else {
                $sender->sendMessage("コマンドはプレイヤーのみ使用できます");
            }
        }
        return true;
    }

    public function createWindow(Player $player, $data, int $id) {
        $pk = new ModalFormRequestPacket();
        $pk->formId = $id;
        $pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
        $player->getNetworkSession()->sendDataPacket($pk);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = strtolower($player->getName());
        $folder = $this->getFolder($name);
        $this->data[$name] = new Config($folder, Config::JSON, [
            "tag" => $this->config->get("デフォルト称号"),
        ]);
        $this->data[$name]->save();
        $this->getScheduler()->scheduleDelayedTask(new CPTask($this, "ChangeTag", [$player]), 10);
    }

    public function getFolder($name) {
        $sub = substr($name, 0, 1);
        $upper = strtoupper($sub);
        $folder = $this->getDataFolder() . $upper . '/';
        if (!file_exists($folder)) mkdir($folder);
        $lower = strtolower($name);
        return $folder .= $lower . '.json';
    }

    public function onDataPacketReceiveEvent(DataPacketReceiveEvent $event) {
        $pk = $event->getPacket();
        if ($pk instanceof ModalFormResponsePacket) {
            $player = $event->getOrigin()->getPlayer();
            $name = strtolower($player->getName());
            $id = $pk->formId;
            $data = $pk->formData;
            $result = json_decode($data);
            if ($data != "null\n") {
                if ($id === 78533) {
                    if ($result[1] === "") {
                        $player->sendMessage("§bTag §7>> §c未記入です");
                        return true;
                    }
                    if (str_contains($result[1], "l")) {
                        if (!Server::getInstance()->isOp($player->getName())) {
                            $player->sendMessage("§bTag §7>> §c太文字を使用することはできません");
                            return true;
                        }
                    }
                    if (Server::getInstance()->isOp($player->getName())) {
                        $this->data[$name]->set("tag", $result[1]);
                        $this->data[$name]->save();
                        $player->sendMessage("§bTag §7>> §a称号を " . $result[1] . " §r§aに変更しました");
                        $this->getScheduler()->scheduleDelayedTask(new CPTask($this, "ChangeTag", [$player]), 10);
                    } else {
                        $Section = mb_substr_count($result[1], "§");
                        $check = mb_strlen($result[1]);
                        $count = $Section * 2;
                        $count1 = $check - $count;
                        if ($count1 >= 16) {
                            $player->sendMessage("§bTag §7>> §c称号の文字数は最大でも15文字となっています");
                        } else {
                            $this->data[$name]->set("tag", $result[1]);
                            $this->data[$name]->save();
                            $player->sendMessage("§bTag §7>> §a称号を " . $result[1] . " §r§aに変更しました");
                            $this->getScheduler()->scheduleDelayedTask(new CPTask($this, "ChangeTag", [$player]), 10);
                        }
                    }
                }
            }
        }
        return true;
    }

    public function ChangeTag(Player $player) {
        $name = strtolower($player->getName());
        $tag = $this->data[$name]->get("tag");
        $name = $player->getName();
        $level = $this->level->getLevel($player);
        if (Server::getInstance()->isOp($player->getName())) {
            $player->setDisplayName("§eStaff§a-§e" . $tag . "§r§a-§f" . $name . "§r§f");
            $player->setNameTag("§eStaff§a-§e" . $tag . "§r§a-§f" . $name . "§r§f");
            if ($this->isOwner($player)) {
                $player->setDisplayName("§eOwner§a-§e" . $tag . "§r§a-§f" . $name . "§r§a");
                $player->setNameTag("§eOwner§a-§e" . $tag . "§r§a-§f" . $name . "§r§a");
            }
        } else {
            $player->setDisplayName("§eLv." . $level . "§a-§e" . $tag . "§r§a-§r§f" . $name);
            $player->setNameTag("§eLv." . $level . "§a-§e" . $tag . "§r§a-§r§f" . $name);
            if ($this->isOwner($player)) {
                $player->setDisplayName("§eOwner§a-§eLv." . $level . "§a-§e" . $tag . "§r§a-§f" . $name . "§r§f");
                $player->setNameTag("§eOwner§a-§eLv." . $level . "§a-§e" . $tag . "§r§a-§f" . $name . "§r§f");
            }
            if ($this->isDebugger($player)) {
                $player->setDisplayName("§eDebugger§a-§eLv." . $level . "§a-§e" . $tag . "§r§a-§f" . $name . "§r§f");
                $player->setNameTag("§eDebugger§a-§eLv." . $level . "§a-§e" . $tag . "§r§a-§f" . $name . "§r§f");
            }
        }
    }

    /**
     * @param Player $p
     * @return bool
     */
    private function isOwner(Player $p) {
        if (in_array($p->getName(), self::owner)) {
            return true;
        } else {
            return false;
        }
    }

    private function isDebugger(Player $p) {
        if (in_array($p->getName(), self::debugger)) {
            return true;
        } else {
            return false;
        }
    }
}

class CPTask extends Task {

    public $instance;
    public $func;
    public $args;

    public function __construct($instance, string $func, array $args = []) {
        $this->instance = $instance;
        $this->func = $func;
        $this->args = $args;
    }

    public function onRun(): void {
        $f = $this->func;
        $this->instance->$f(...$this->args);
    }
}