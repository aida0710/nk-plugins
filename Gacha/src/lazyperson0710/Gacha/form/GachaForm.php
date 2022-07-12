<?php

namespace lazyperson0710\Gacha\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\Gacha\Calculation\ItemRegister;
use lazyperson0710\Gacha\Calculation\RankCalculation;
use lazyperson0710\Gacha\Main;
use lazyperson0710\ticket\TicketAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\Server;

class GachaForm extends CustomForm {

    private Input $quantity;
    private array $content;
    private array $cost;
    private int $key;

    public function __construct(Player $player, string $gachaName, int $key) {
        $this->key = $key;
        $this->content = Main::getInstance()->getAllData();
        $moneyApi = EconomyAPI::getInstance();
        $ticket = TicketAPI::getInstance();
        $this->cost = $this->content[$key]["cost"];
        $rank = $this->content[$key]["rank"];
        $this->quantity = new Input("ガチャを回したい回数を入力してください", "0");
        $this
            ->setTitle("Gacha System / {$gachaName}")
            ->addElements(
                new Label("ガチャコスト / 所持数量\nMoney -> {$this->cost["money"]} / {$moneyApi->myMoney($player->getName())}円\nMiningTicket -> {$this->cost["ticket"]}枚 / {$ticket->checkData($player)}枚\nEventTicket -> {$this->cost["eventTicket"]}枚"),
                new Label("ガチャの排出確率\nCommon -> {$rank["C"]}％\nUnCommon -> {$rank["UC"]}％\nRare -> {$rank["R"]}％\nSuperRare -> {$rank["SR"]}％\nLegendary -> {$rank["L"]}％"),
                new Label("inventoryが満タンの場合アイテムはドロップする為アイテム削除には十分にお気を付けください"),
                $this->quantity,
            );
    }

    public function handleSubmit(Player $player): void {
        $rankProbability = Main::getInstance()->getAllData()[$this->key]["rank"];
        if ($this->quantity->getValue() == "") {
            $player->sendMessage("§bGacha §7>> §c回数を入力してください");
            return;
        }
        if (!preg_match('/^[0-9]+$/', $this->quantity->getValue())) {
            $player->sendMessage("§bGacha §7>> §c整数のみで入力してください");
            return;
        }
        if (!Server::getInstance()->isOp($player->getName())) {
            if ($this->quantity->getValue() >= 301) {
                $player->sendMessage("§bGacha §7>> §c301回以上は連続で回すことはできません");
                return;
            }
        }
        $result = (new RankCalculation)->run($this->quantity->getValue(), $player, $this->key);
        if (empty($result)) {
            Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
            return;
        }
        $result = substr($result, 0, -1);
        $result = explode(",", $result);
        $formMessage = null;
        $onDrop = false;
        foreach ($result as $rank) {
            $item = (new ItemRegister($this->key, $rank))->Items($this->key, $rank);
            switch ($rank) {
                case "SR":
                    $this->addSound($player, "mob.wither.spawn");
                    $player->sendMessage("§bGacha §7>> §aSuperRare > {$item->getCustomName()}§r§eを{$rankProbability[$rank]}％で当てました");
                    break;
                case "L":
                    $this->addSound($player, "mob.enderdragon.death");
                    Server::getInstance()->broadcastMessage("§bGacha §7>> §eLegendary > {$item->getCustomName()}§r§eを{$player->getName()}が{$rankProbability[$rank]}％で当てました");
                    break;
            }
            $formDisplayRank = match ($rank) {
                "C" => "§7Common§r",
                "UC" => "§aUnCommon§r",
                "R" => "§bRare§r",
                "SR" => "§dSuperRare§r",
                "L" => "§cLegendary§r",
            };
            $formMessage .= "{$formDisplayRank} > {$item->getCustomName()}§rを{$rankProbability[$rank]}％で当てました\n";
            if ($player->getInventory()->canAddItem($item)) {
                $player->getInventory()->addItem($item);
            } else {
                $player->dropItem($item);
                $onDrop = true;
            }
            if (mt_rand(1, 1000000) === 15000) {
                Server::getInstance()->broadcastMessage("§bGacha §7>> §e{$player->getName()}が0.001％の確率を当てました！Ticketを500枚プレゼントされました！");
                TicketAPI::getInstance()->addTicket($player, 500);
            }
        }
        $player->sendForm(new ResultForm($formMessage, $onDrop));
    }

    public function addSound(Player $player, string $soundName) {
        $pk = new PlaySoundPacket();
        $pk->x = $player->getPosition()->getX();
        $pk->y = $player->getPosition()->getY();
        $pk->z = $player->getPosition()->getZ();
        $volume = mt_rand(1, 2);
        $pitch = mt_rand(5, 10);
        $pk->soundName = $soundName;
        $pk->volume = $volume / 10;
        $pk->pitch = $pitch / 10;
        $player->getNetworkSession()->sendDataPacket($pk);
    }

}