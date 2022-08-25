<?php

namespace lazyperson0710\Gacha\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\Gacha\Calculation\ItemRegister;
use lazyperson0710\Gacha\Calculation\RankCalculation;
use lazyperson0710\Gacha\database\GachaItemAPI;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class GachaForm extends CustomForm {

    private Input $quantity;
    private string $categoryName;
    private array $probability;
    private array $cost;

    public function __construct(Player $player, string $categoryName) {
        $this->categoryName = $categoryName;
        $this->probability = GachaItemAPI::getInstance()->rankProbability[$categoryName][0];
        $this->cost = GachaItemAPI::getInstance()->categoryCost[$categoryName][0];
        $moneyApi = EconomyAPI::getInstance();
        $ticket = TicketAPI::getInstance();
        $this->quantity = new Input("ガチャを回したい回数を入力してください", "0");
        $this
            ->setTitle("Gacha System / {$categoryName}")
            ->addElements(
                new Label("ガチャコスト / 所持数量\nMoney -> {$this->cost["moneyCost"]} / {$moneyApi->myMoney($player->getName())}円\nMiningTicket -> {$this->cost["ticketCost"]}枚 / {$ticket->checkData($player)}枚"),
                new Label("ガチャの排出確率\nCommon -> {$this->probability["C"]}％\nUnCommon -> {$this->probability["UC"]}％\nRare -> {$this->probability["R"]}％\nSuperRare -> {$this->probability["SR"]}％\nLegendary -> {$this->probability["L"]}％"),
                new Label("inventoryが満タンの場合アイテムはドロップする為アイテム削除には十分にお気を付けください"),
                $this->quantity,
            );
    }

    public function handleSubmit(Player $player): void {
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
        $result = (new RankCalculation($this->categoryName))->run($this->quantity->getValue(), $player);
        if (is_null($result)) return;
        $result = substr($result, 0, -1);
        $result = explode(",", $result);
        $formMessage = null;
        $onDrop = false;
        foreach ($result as $rank) {
            $item = (new ItemRegister($this->categoryName, $rank))->Items($rank);
            switch ($rank) {
                case "SR":
                    SoundPacket::init($player, "mob.wither.spawn");
                    $player->sendMessage("§bGacha §7>> §aSuperRare > {$item->getCustomName()}§r§eを{$this->probability[$rank]}％で当てました");
                    break;
                case "L":
                    SoundPacket::init($player, "mob.enderdragon.death");
                    Server::getInstance()->broadcastMessage("§bGacha §7>> §eLegendary > {$item->getCustomName()}§r§eを{$player->getName()}が{$this->probability[$rank]}％で当てました");
                    break;
            }
            $formDisplayRank = match ($rank) {
                "C" => "§7Common§r",
                "UC" => "§aUnCommon§r",
                "R" => "§bRare§r",
                "SR" => "§dSuperRare§r",
                "L" => "§cLegendary§r",
            };
            $formMessage .= "{$formDisplayRank} > {$item->getCustomName()}§rを{$this->probability[$rank]}％で当てました\n";
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
        SendForm::Send($player, (new ResultForm($formMessage, $onDrop)));
    }

}