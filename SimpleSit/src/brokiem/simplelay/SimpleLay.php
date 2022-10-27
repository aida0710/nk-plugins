<?php

declare(strict_types = 1);
namespace brokiem\simplelay;

use lazyperson710\core\packet\SendMessage;
use lazyperson710\core\packet\SendTip;
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
                if ($this->isSitting($sender)) {
                    $this->unsetSit($sender);
                } else {
                    $this->sit($sender, $sender->getWorld()->getBlock($sender->getPosition()->add(0, -0.5, 0)));
                }
                break;
            case "skick":
                if (isset($args[0])) {
                    $player = $this->getServer()->getPlayerExact($args[0]);
                    if ($player !== null) {
                        if ($this->isSitting($player)) {
                            $this->unsetSit($player);
                            SendMessage::Send($sender, "{$player->getName()}の待機状態を解除しました", "Sit", true);
                            SendMessage::Send($player, "待機状態を強制的に解除されました", "Sit", false,);
                        } else {
                            SendMessage::Send($sender, "{$player->getName()}は座っていません", "Sit", false);
                        }
                    } else {
                        SendMessage::Send($sender, "§c{$args[0]}は存在しません", "Sit", false);
                    }
                } else {
                    return false;
                }
        }
        return true;
    }

    public function isToggleSit(Player $player): bool {
        return in_array(strtolower($player->getName()), $this->toggleSit, true);
    }

    public function unsetToggleSit(Player $player): void {
        unset($this->toggleSit[strtolower($player->getName())]);
        SendMessage::Send($player, "タップで座れるようになりました", "Sit", true);
    }

    public function setToggleSit(Player $player): void {
        $this->toggleSit[] = strtolower($player->getName());
        SendMessage::Send($player, "タップで座らないようになりました", "Sit", true);
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
            SendTip::Send($player, "このブロックには座ることはできません", "Sit", false);
            return;
        }
        foreach ($this->sittingData as $playerName => $data) {
            if ($pos->equals($data['pos'])) {
                if ($player->getName() === $playerName) return;
                SendTip::Send($player, "このブロックには他プレイヤーが既に存在します", "Sit", false);
                return;
            }
        }
        if ($this->isSitting($player)) {
            SendTip::Send($player, "既に待機状態です", "Sit", false);
            return;
        }
        $this->setSit($player, $this->getServer()->getOnlinePlayers(), new Position($pos->x, $pos->y, $pos->z, $this->getServer()->getWorldManager()->getWorldByName($player->getWorld()->getFolderName())));
        SendTip::Send($player, "現在待機状態です\n状態を解除するにはスニーク状態にしてください", "Sit", false);
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
            'pos' => $pos,
        ];
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
