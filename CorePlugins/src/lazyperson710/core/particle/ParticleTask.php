<?php

namespace lazyperson710\core\particle;

use pocketmine\color\Color;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\particle\DustParticle;

class ParticleTask extends Task {

    public function addParticle(): void {
        for ($t = 0; $t <= 2.0; $t += 0.01) {
            $x = 13 * cos(M_PI * $t);
            $z = 13 * sin(M_PI * $t);
            $x = sprintf("%.5f", $x);
            $z = sprintf("%.5f", $z);
            $x += 0.5;
            $z += 0.5;
            $pos = Server::getInstance()->getWorldManager()->getWorldByName("lobby")->getSpawnLocation()->add($x, 3.5, $z);
            Server::getInstance()->getWorldManager()->getWorldByName("lobby")->addParticle($pos, new DustParticle(new Color(255, 255, 255)));
        }
    }

    public function onRun(): void {
        $this->addParticle();
    }
}