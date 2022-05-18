<?php

declare(strict_types=1);
namespace brokiem\simplelay;

use pocketmine\block\Block;
use pocketmine\block\Opaque;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityLink;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\entity\LongMetadataProperty;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\world\Position;

class SimpleLay extends PluginBase {

    public array $toggleSit = [];

    public array $sittingData = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            return true;
        }
        switch (strtolower($command->getName())) {
            case "sit":
                if ($this->isToggleSit($sender)) {
                    $this->unsetToggleSit($sender);
                } else {
                    $this->setToggleSit($sender);
                }
                break;
            case "skick":
                if (isset($args[0])) {
                    $player = $this->getServer()->getPlayerExact($args[0]);
                    if ($player !== null) {
                        if ($this->isSitting($player)) {
                            $this->unsetSit($player);
                            $sender->sendMessage("§bSit §7>> §a{$player->getName()}の椅座位を解除しました");
                            $player->sendMessage("§bSit §7>> §a椅座位を強制的に解除されました");
                        } else {
                            $sender->sendMessage("§bSit §7>> §c{$player->getName()}は座っていません");
                        }
                    } else {
                        $sender->sendMessage("§bSit §7>> §c{$args[0]}は存在しません");
                    }
                } else {
                    return false;
                }
        }
        return true;
    }

    public function isSitting(Player $player): bool {
        return isset($this->sittingData[strtolower($player->getName())]);
    }

    public function unsetSit(Player $player): void {
        $pk1 = new RemoveActorPacket();
        $pk1->actorUniqueId = $this->sittingData[strtolower($player->getName())]['eid'];
        $pk = new SetActorLinkPacket();
        $pk->link = new EntityLink($this->sittingData[strtolower($player->getName())]['eid'], $player->getId(), EntityLink::TYPE_REMOVE, true, true);
        unset($this->sittingData[strtolower($player->getName())]);
        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::RIDING, false);
        $this->getServer()->broadcastPackets($this->getServer()->getOnlinePlayers(), [$pk1, $pk]);
    }

    public function sit(Player $player, Block $block): void {
        if ($block instanceof Stair or $block instanceof Slab) {
            $pos = $block->getPosition()->add(0.5, 1.5, 0.5);
        } elseif ($block instanceof Opaque) {
            $pos = $block->getPosition()->add(0.5, 2.1, 0.5);
        } else {
            return;
        }
        foreach ($this->sittingData as $playerName => $data) {
            if ($pos->equals($data['pos'])) {
                $player->sendTip("§bSit §7>> §cこのブロックには他プレイヤーが既に存在します");
                return;
            }
        }
        if ($this->isSitting($player)) {
            $player->sendTip("§bSit §7>> §c既に椅坐位状態です");
            return;
        }
        $this->setSit($player, $this->getServer()->getOnlinePlayers(), new Position($pos->x, $pos->y, $pos->z, $this->getServer()->getWorldManager()->getWorldByName($player->getWorld()->getFolderName())));
        $player->sendTip("§bSit §7>> §c現在椅坐位状態です\n状態を解除するにはスニーク状態にしてください");
    }

    public function setSit(Player $player, array $viewers, Position $pos, ?int $eid = null): void {
        if ($eid === null) {
            $eid = Entity::nextRuntimeId();
        }
        $pk = new AddActorPacket();
        $pk->actorRuntimeId = $eid;
        $pk->actorUniqueId = $eid;
        $pk->type = EntityIds::WOLF;
        $pk->position = $pos->asVector3();
        $pk->metadata = [
            EntityMetadataProperties::FLAGS => new LongMetadataProperty(1 << EntityMetadataFlags::IMMOBILE | 1 << EntityMetadataFlags::SILENT | 1 << EntityMetadataFlags::INVISIBLE),
        ];
        $link = new SetActorLinkPacket();
        $link->link = new EntityLink($eid, $player->getId(), EntityLink::TYPE_RIDER, true, true);
        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::RIDING, true);
        $this->getServer()->broadcastPackets($viewers, [$pk, $link]);
        if ($this->isSitting($player)) {
            return;
        }
        $this->sittingData[strtolower($player->getName())] = [
            'eid' => $eid,
            'pos' => $pos
        ];
    }

    public function isToggleSit(Player $player): bool {
        return in_array(strtolower($player->getName()), $this->toggleSit, true);
    }

    public function unsetToggleSit(Player $player): void {
        unset($this->toggleSit[strtolower($player->getName())]);
        $player->sendMessage("§bSit §7>> §aタップで座れるようになりました");
    }

    public function setToggleSit(Player $player): void {
        $this->toggleSit[] = strtolower($player->getName());
        $player->sendMessage("§bSit §7>> §aタップで座らないようになりました");
    }

    public function optimizeRotation(Player $player): void {
        $pk = new MoveActorAbsolutePacket();
        $pk->position = $this->sittingData[strtolower($player->getName())]['pos'];
        $pk->actorRuntimeId = $this->sittingData[strtolower($player->getName())]['eid'];
        $pk->pitch = $player->getLocation()->getPitch();
        $pk->yaw = $player->getLocation()->getYaw();
        $pk->headYaw = $player->getLocation()->getYaw();
        $this->getServer()->broadcastPackets($this->getServer()->getOnlinePlayers(), [$pk]);
    }

}
