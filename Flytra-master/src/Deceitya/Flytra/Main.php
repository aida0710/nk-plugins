<?php

namespace Deceitya\Flytra;

use Deceitya\Flytra\command\FlyCommand;
use Deceitya\Flytra\task\FlyCheckTask;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\world\particle\RedstoneParticle;
use pocketmine\world\World;

class Main extends PluginBase {

    /** @var string[] */
    static array $worlds = [];
    private static Main $main;

    public function onEnable(): void {
        self::$main = $this;
        $this->reloadConfig();
        self::$worlds = $this->getConfig()->getAll();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEventListener, $this);
        $this->getScheduler()->scheduleRepeatingTask(new FlyCheckTask(), 20);
        $this->getServer()->getCommandMap()->registerAll("fly", [
            new FlyCommand(),
        ]);
    }

    public function checkFly(Player $player, World $world, Item $item) {
        if ($player->isSurvival()) {
            if ($item->getId() === ItemIds::ELYTRA || array_key_exists($player->getName(), FlyCheckTask::$flyTask)) {
                if (Server::getInstance()->isOp($player->getName())) {
                    $player->setAllowFlight(true);
                    $this->addParticle($player);
                } elseif (in_array($world->getFolderName(), self::$worlds)) {
                    $player->setAllowFlight(false);
                    $player->setFlying(false);
                } else {
                    if ($player->getPosition()->getFloorY() > WorldManagementAPI::getInstance()->getFlyLimit($player->getWorld()->getFolderName())) {
                        $player->setAllowFlight(false);
                        $player->setFlying(false);
                        $player->sendTip("§bFlyTask §7>> §c高さ制限に引っ掛かった為飛行が一時的に不可になりました");
                    } else {
                        $player->setAllowFlight(true);
                        $this->addParticle($player);
                    }
                }
            } else {
                $player->setAllowFlight(false);
                $player->setFlying(false);
            }
        }
    }

    public function addParticle(Player $player): void {
        for ($t = 0; $t <= 2.0; $t += 0.1) {
            $x = cos(M_PI * $t);
            $z = sin(M_PI * $t);
            $x = sprintf("%.5f", $x);
            $z = sprintf("%.5f", $z);
            $player->getWorld()->addParticle($player->getPosition()->add($x, -1, $z), new RedstoneParticle(0.1));
        }
    }

    public static function getInstance(): Main {
        return self::$main;
    }

}
