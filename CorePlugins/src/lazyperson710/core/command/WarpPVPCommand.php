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
            $sender->sendMessage("サーバー内で実行してください");
            return;
        }
        $pos = match (mt_rand(1, 25)) {
            1 => new Vector3(318, 90, 174),
            2 => new Vector3(298, 90, 173),
            3 => new Vector3(270, 90, 173),
            4 => new Vector3(240, 90, 178),
            5 => new Vector3(244, 90, 198),
            6 => new Vector3(234, 90, 224),
            7 => new Vector3(234, 90, 235),
            8 => new Vector3(240, 90, 255),
            9 => new Vector3(259, 90, 247),
            10 => new Vector3(277, 90, 258),
            11 => new Vector3(297, 90, 260),
            12 => new Vector3(304, 90, 242),
            13 => new Vector3(322, 90, 233),
            14 => new Vector3(318, 90, 212),
            15 => new Vector3(305, 90, 198),
            16 => new Vector3(322, 90, 181),
            17 => new Vector3(317, 90, 255),
            18 => new Vector3(268, 90, 230),
            19 => new Vector3(264, 90, 214),
            20 => new Vector3(274, 90, 203),
            21 => new Vector3(292, 90, 213),
            22 => new Vector3(268, 90, 192),
            23 => new Vector3(319, 90, 171),
            24 => new Vector3(318, 90, 198),
            25 => new Vector3(318, 90, 244),
            default => throw new \Error("不正な値が代入されました"),
        };
        $this->teleportPlayer($sender, $pos);
    }

    public function teleportPlayer(Player $player, Vector3 $pos) {
        $world = Server::getInstance()->getWorldManager()->getWorldByName("pvp");
        $player->teleport(new Position($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $world));
        $player->sendTip("§bPVP §7>> §aTeleportしました！");
    }

}