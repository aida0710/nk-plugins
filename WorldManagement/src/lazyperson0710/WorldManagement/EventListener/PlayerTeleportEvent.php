<?php

namespace lazyperson0710\WorldManagement\EventListener;

use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson0710\WorldManagement\form\WarpForm;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class PlayerTeleportEvent implements Listener {

    /**
     * @param EntityTeleportEvent $event
     * @return void
     * @priority HIGH
     */
    public function PlayerTeleportEvent(EntityTeleportEvent $event) {
        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        if ($event->getTo()->getWorld()->getDisplayName() === $event->getFrom()->getWorld()->getDisplayName()) {
            return;
        }
        $worldApi = WorldManagementAPI::getInstance();
        if (MiningLevelAPI::getInstance()->getLevel($player) >= $worldApi->getMiningLevelLimit($event->getTo()->getWorld()->getFolderName())) {
            if (in_array($event->getTo()->getWorld()->getDisplayName(), WorldCategory::Nature)) {
                $player->sendMessage("§bWorld §7>> §a天然資源ワールド\n§7>> §a自由に採掘が可能です\n§7>> §aたくさんの資源を集めてショップで売却してみよう！");
                $player->sendMessage("§bWorldBorder §7>> §a移動可能範囲は{$worldApi->getWorldLimitX_1($event->getTo()->getWorld()->getFolderName())} x {$worldApi->getWorldLimitZ_1($event->getTo()->getWorld()->getFolderName())}になります");
                return;
            }
            if (in_array($event->getTo()->getWorld()->getDisplayName(), WorldCategory::NatureJava)) {
                $player->sendMessage("§bWorld §7>> §aJava\n§7>> §aこのワールドはマイニングレベルが取得できない等の制限がありますが洞窟などが生成されます");
                $player->sendMessage("§bWorldBorder §7>> §a移動可能範囲は{$worldApi->getWorldLimitX_1($event->getTo()->getWorld()->getFolderName())} x {$worldApi->getWorldLimitZ_1($event->getTo()->getWorld()->getFolderName())}になります");
                return;
            }
            if (in_array($event->getTo()->getWorld()->getDisplayName(), WorldCategory::MiningWorld)) {
                $player->sendMessage("§bWorld §7>> §aMiningWorld\n§7>> §aこのワールドは一週間(日曜日の最初の再起動)ごとにリセットされます");
                $player->sendMessage("§bWorldBorder §7>> §a移動可能範囲は{$worldApi->getWorldLimitX_1($event->getTo()->getWorld()->getFolderName())} x {$worldApi->getWorldLimitZ_1($event->getTo()->getWorld()->getFolderName())}になります");
                return;
            }
            if (in_array($event->getTo()->getWorld()->getDisplayName(), WorldCategory::ResourceWorld)) {
                $player->sendMessage("§bWorld §7>> §aResource\n§7>> §aこのワールドは中級者から上級者向けに作成されています\n§7>> §aその為お金を稼ぎたい方は天然資源をおすすめさせていただいております");
                return;
            }
            if (in_array($event->getTo()->getWorld()->getDisplayName(), WorldCategory::LifeWorld)) {
                $player->sendMessage("§bWorld §7>> §a建築ワールド\n§7>> §aこのワールドは建築が可能なワールドになります\n§7>> §a/landを実行して土地を購入してください");
                if ($event->getTo()->getWorld()->getDisplayName() === "船橋市-c") {
                    $player->sendMessage("§7>> §a船橋市は大きく景観を損なう行為を禁止させていただいております。例:露天掘りや大規模な整地など\n§7>> §aまた、購入範囲が明確にわかるようにしてください");
                }
                return;
            }
            if (in_array($event->getTo()->getWorld()->getDisplayName(), WorldCategory::AgricultureWorld)) {
                $player->sendMessage("§bWorld §7>> §a農業ワールド\n§7>> §aこのワールドは農業が可能なワールドになります\n§7>> §a/landを実行して土地を購入してください");
                if (in_array($event->getTo()->getWorld()->getDisplayName(), WorldCategory::UniqueAgricultureWorld)) {
                    $player->sendMessage("§7>> §a八街市はブロックの設置や破壊などが制限されたワールドになります");
                }
                return;
            }
            if ($event->getTo()->getWorld()->getDisplayName() === "lobby") {
                $player->sendForm(new WarpForm($player));
                return;
            }
        }
    }

}