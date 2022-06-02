<?php

namespace lazyperson710\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class WarpPVPCommand extends Command {

    public function __construct() {
        parent::__construct("pvp", "pvpワールドにTeleportします");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        switch (mt_rand(1, 25)) {
            case 1:
                $pos = new Vector3(318, 90, 174);
                $this->teleportPlayer($sender, $pos);
                break;
            case 2:
                $pos = new Vector3(298, 90, 173);
                $this->teleportPlayer($sender, $pos);
                break;
            case 3:
                $pos = new Vector3(270, 90, 173);
                $this->teleportPlayer($sender, $pos);
                break;
            case 4:
                $pos = new Vector3(240, 90, 178);
                $this->teleportPlayer($sender, $pos);
                break;
            case 5:
                $pos = new Vector3(244, 90, 198);
                $this->teleportPlayer($sender, $pos);
                break;
            case 6:
                $pos = new Vector3(234, 90, 224);
                $this->teleportPlayer($sender, $pos);
                break;
            case 7:
                $pos = new Vector3(234, 90, 235);
                $this->teleportPlayer($sender, $pos);
                break;
            case 8:
                $pos = new Vector3(240, 90, 255);
                $this->teleportPlayer($sender, $pos);
                break;
            case 9:
                $pos = new Vector3(259, 90, 247);
                $this->teleportPlayer($sender, $pos);
                break;
            case 10:
                $pos = new Vector3(277, 90, 258);
                $this->teleportPlayer($sender, $pos);
                break;
            case 11:
                $pos = new Vector3(297, 90, 260);
                $this->teleportPlayer($sender, $pos);
                break;
            case 12:
                $pos = new Vector3(304, 90, 242);
                $this->teleportPlayer($sender, $pos);
                break;
            case 13:
                $pos = new Vector3(322, 90, 233);
                $this->teleportPlayer($sender, $pos);
                break;
            case 14:
                $pos = new Vector3(318, 90, 212);
                $this->teleportPlayer($sender, $pos);
                break;
            case 15:
                $pos = new Vector3(305, 90, 198);
                $this->teleportPlayer($sender, $pos);
                break;
            case 16:
                $pos = new Vector3(322, 90, 181);
                $this->teleportPlayer($sender, $pos);
                break;
            case 17:
                $pos = new Vector3(317, 90, 255);
                $this->teleportPlayer($sender, $pos);
                break;
            case 18:
                $pos = new Vector3(268, 90, 230);
                $this->teleportPlayer($sender, $pos);
                break;
            case 19:
                $pos = new Vector3(264, 90, 214);
                $this->teleportPlayer($sender, $pos);
                break;
            case 20:
                $pos = new Vector3(274, 90, 203);
                $this->teleportPlayer($sender, $pos);
                break;
            case 21:
                $pos = new Vector3(292, 90, 213);
                $this->teleportPlayer($sender, $pos);
                break;
            case 22:
                $pos = new Vector3(268, 90, 192);
                $this->teleportPlayer($sender, $pos);
                break;
            case 23:
                $pos = new Vector3(319, 90, 171);
                $this->teleportPlayer($sender, $pos);
                break;
            case 24:
                $pos = new Vector3(318, 90, 198);
                $this->teleportPlayer($sender, $pos);
                break;
            case 25:
                $pos = new Vector3(318, 90, 244);
                $this->teleportPlayer($sender, $pos);
                break;
            default:
                $sender->sendMessage("§bPVP §7>> §cエラーが発生しました");
        }
    }

    public function teleportPlayer(Player $player, Vector3 $pos) {
        $world = Server::getInstance()->getWorldManager()->getWorldByName("pvp");
        $player->teleport(new Position($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $world));
        $player->sendTip("§bPVP §7>> §aTeleportしました！");
    }

}