<?php

namespace Deceitya\MiningLevel;

use Deceitya\MiningLevel\Event\EventListener;
use Deceitya\MiningLevel\Form\RankForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

class MiningLevelSystem extends PluginBase {

    public function onEnable(): void {
        $this->saveResource('config.yml');
        MiningLevelAPI::getInstance()->init($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this->getConfig()), $this);
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
            function (): void {
                MiningLevelAPI::getInstance()->writecache();
            }
        ), 20 * 60 * 3);//3分
    }

    public function onDisable(): void {
        $this->getLogger()->info("§bLevel §7>> §aセーブデータ書き込み中...");
        MiningLevelAPI::getInstance()->deinit();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!isset($args[0])) {
            return false;
        }
        switch ($args[0]) {
            case 'status':
                if (!Server::getInstance()->isOp($sender->getName())) {
                    return true;
                }
                $name = null;
                if ($sender instanceof Player) {
                    $name = $args[1] ?? $sender->getName();
                } else {
                    if (!isset($args[1])) {
                        return false;
                    }
                    $name = $args[1];
                }
                $data = MiningLevelAPI::getInstance()->getData($name);
                if (!empty($data)) {
                    $sender->sendMessage(
                        "§bLevel §7>> §a{$name}のステータス\n" .
                        "§aレベル: {$data[1]}\n" .
                        "§a経験値: {$data[2]}\n" .
                        "§aレベルアップに必要な経験値: {$data[3]}"
                    );
                } else {
                    $sender->sendMessage("§bLevel §7>> §c{$name}のデータは存在しません。");
                }
                return true;
            case 'set':
                if (!Server::getInstance()->isOp($sender->getName())) {
                    return true;
                }
                if (isset($args[1]) && isset($args[2])) {
                    $player = $args[1];
                    $level = $args[2];
                    if (Server::getInstance()->getPlayerByPrefix($player)) {
                        if (!is_int($level)) {
                            MiningLevelAPI::getInstance()->setLevel($player, $level);
                            $sender->sendMessage("§bLevel §7>> §c{$player}のレベルを{$level}にしました。");
                            return true;
                        } else {
                            $sender->sendMessage("intじゃないんですけど");
                        }
                    } else {
                        $sender->sendMessage("そんなぷれいやーいねぇよカス");
                    }
                } else {
                    $sender->sendMessage("§bLevel §7>> §c/set player level");
                }
                return true;
            case 'reset':
                $name = $sender->getName();
                MiningLevelAPI::getInstance()->setData($name, 0, 0, 80);
                $sender->sendMessage("§bLevel §7>> §cレベルをリセットしました");
                return true;
            case 'ranking':
                if (!($sender instanceof Player)) {
                    $sender->sendMessage('§bLevel §7>> §cサーバー内で使用してください。');
                    return true;
                }
                SendForm::Send($sender, (new RankForm()));
                return true;
            case 'save':
                if (!Server::getInstance()->isOp($sender->getName())) {
                    return true;
                }
                $sender->sendMessage("§bLevel §7>> §aセーブデータを書き込んでいます...");
                MiningLevelAPI::getInstance()->writecache($sender->getName());
                return true;
            default:
                return false;
        }
    }
}
