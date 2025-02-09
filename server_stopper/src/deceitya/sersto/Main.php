<?php

declare(strict_types = 0);

namespace deceitya\sersto;

use DateTimeImmutable;
use lazyperson710\core\packet\SendMessage\SendBroadcastMessage;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\World;
use pocketmine\world\WorldCreationOptions;
use function array_diff;
use function date_default_timezone_set;
use function is_dir;
use function rmdir;
use function scandir;
use function unlink;

class Main extends PluginBase {

    /*
     最後の土曜日
     2021年11月13(土曜日) => 2021年11月6日(土曜日)
     2021年11月14(日曜日) => 2021年11月13日(土曜日)

     日曜日 = sunday
    */
    public const FORMAT = 'last saturday';
    private $current;
    /** @var Config $lastdelete */
    private $lastdelete;

    public function onDisable() : void {
        $level = $this->getServer()->getWorldManager()->getWorldByName('MiningWorld');
        if ($level !== null) {
            $lastSunday = (new DateTimeImmutable(self::FORMAT))->format('Y/m/d');
            if ($this->lastdelete->get('lastdelete') === $lastSunday) {
                return;
            }
            $this->lastdelete->set('lastdelete', $lastSunday);
            $this->lastdelete->save();
            $this->deleteLevel($level);
        }
    }

    private function deleteLevel(World $level) {
        $path = $level->getProvider()->getPath();
        $this->getServer()->getWorldManager()->unloadWorld($level, true);
        $this->deleteDir($path);
    }

    private function deleteDir($dir) {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDir("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function onEnable() : void {
        $this->reloadConfig();
        $this->current = $this->getConfig()->get('time', 120) * 60;
        $this->lastdelete = new Config($this->getDataFolder() . 'lastdelete.yml', config::YAML, ['lastdelete' => (new DateTimeImmutable(self::FORMAT))->format('Y/m/d')]);
        $this->getScheduler()->scheduleRepeatingTask(new ShutdownTask($this), 20);
        $options = WorldCreationOptions::create();
        $options->setGeneratorClass(GeneratorManager::getInstance()->getGenerator('default')->getGeneratorClass());
        $this->getServer()->getWorldManager()->generateWorld('MiningWorld', $options);
    }

    public function onLoad() : void {
        date_default_timezone_set('Asia/Tokyo');
    }

    public function getCurrent() : int {
        return $this->current;
    }

    public function setCurrent(int $v) {
        $this->current = $v;
    }
}

class ShutdownTask extends Task {

    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun() : void {
        if ($this->plugin->getCurrent() > 0) {
            $this->plugin->setCurrent($this->plugin->getCurrent() - 1);
            switch ($this->plugin->getCurrent()) {
                case 900:
                    SendBroadcastMessage::Send('サーバー再起動まで残り15分になりました', 'Server');
                    break;
                case 300:
                    SendBroadcastMessage::Send('サーバー再起動まで残り5分になりました', 'Server');
                    break;
                case 15:
                    SendBroadcastMessage::Send('サーバー再起動まで残り15秒になりました', 'Server');
                    break;
                case 3:
                    SendBroadcastMessage::Send('サーバー再起動まで残り3秒になりました', 'Server');
                    break;
                case 2:
                    SendBroadcastMessage::Send('サーバー再起動まで残り2秒になりました', 'Server');
                    break;
                case 1:
                    SendBroadcastMessage::Send('サーバー再起動まで残り1秒になりました', 'Server');
                    break;
            }
        } else {
            foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                if ($player->getWorld()->getFolderName() === 'MiningWorld') {
                    $player->teleport($this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
                }
                $player->kick("サーバーを再起動しています\nもし、サーバーが起動しない等の問題があった場合連絡をお願いします\n§aTwitter/@lazyperson710", null);
            }
            $this->plugin->getServer()->shutdown();
        }
    }
}
