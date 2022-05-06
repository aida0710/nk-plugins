<?php

namespace deceitya\enremo;

use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\ItemEntity;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

class Main extends PluginBase {

    private $interval;
    private $current;

    public function onEnable(): void {
        $this->reloadConfig();
        $this->interval = $this->getConfig()->get('interval', 15) * 60;
        $this->current = $this->interval;
        $this->getScheduler()->scheduleRepeatingTask(new RemoveTask($this), 20);
    }

    public function getInterval(): int {
        return $this->interval;
    }

    public function getCurrent(): int {
        return $this->current;
    }

    public function setCurrent(int $v) {
        $this->current = $v;
    }
}

class RemoveTask extends Task {

    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun(): void {
        if ($this->plugin->getCurrent() > 0) {
            $this->plugin->setCurrent($this->plugin->getCurrent() - 1);
            if ($this->plugin->getCurrent() === 15) {
                $this->plugin->getServer()->broadcastTip("§bEntityRemover §7>> §e残り15秒で落下しているアイテムが消去されます");
            }
        } else {
            $c = 0;
            foreach ($this->plugin->getServer()->getWorldManager()->getWorlds() as $level) {
                foreach ($level->getEntities() as $entity) {
                    if ($entity instanceof ItemEntity || $entity instanceof ExperienceOrb) {
                        $entity->close();
                        $c++;
                    }
                }
            }
            if ($c >= 1) {
                $this->plugin->getServer()->broadcastTip("§bEntityRemover §7>> §eItem&Exp Entityを{$c}個削除しました");
            }
            $this->plugin->setCurrent($this->plugin->getInterval() - 1);
        }
    }
}
