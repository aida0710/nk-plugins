<?php

namespace lazyperson0710\Gacha\Calculation;

use JetBrains\PhpStorm\ArrayShape;
use lazyperson0710\Gacha\Main;
use lazyperson0710\ticket\TicketAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;

class RankCalculation {

    /** @var array<string, float|int> */
    private array $tmp_table;
    private array $rank;
    protected int $num;

    private array $content;
    private array $cost;

    public function __construct() {
        $this->content = Main::getInstance()->getAllData();
        $this->tmp_table = $this->getTmpTable();
        $this->initTable();
    }

    public function initTable(): void {
        $this->rank = array_map(static fn($value) => $value * 1000, $this->tmp_table);
        $this->num = (int)array_sum($this->rank);
    }

    public function run($num, Player $player, $key): string|false {
        $this->cost = $this->content[$key]["cost"];
        $result = null;
        for ($i = 1; $i <= $num; $i++) {
            if ($this->checkMoney($player) !== true) return false;
            if ($this->checkTicket($player) !== true) return false;
            if ($this->checkEventTicket($player) !== true) return false;//無条件でtrue
            EconomyAPI::getInstance()->reduceMoney($player->getName(), $this->cost["money"]);
            TicketAPI::getInstance()->reduceTicket($player, $this->cost["ticket"]);
            $rand = mt_rand(1, $this->num);
            $count = 0;
            foreach ($this->rank as $table => $item) {
                $count += $item;
                if ($rand <= $count) {
                    $result .= $table . ",";
                    continue 2;
                }
            }
        }
        return $result;
    }

    #[ArrayShape(["C" => "int", "UC" => "int", "R" => "int", "SR" => "float", "L" => "float"])] protected function getTmpTable(): array {
        return [
            "C" => 80,
            "UC" => 13,
            "R" => 5,
            "SR" => 1.7,
            "L" => 0.3
        ];
    }

    public function checkMoney(Player $player): bool {
        if ($this->cost["money"] >= 0) {
            if (EconomyAPI::getInstance()->myMoney($player->getName()) >= $this->cost["money"]) {
                return true;
            } else {
                $player->sendMessage("§bGacha §7>> §c所持金が足りない為処理が中断されました");
                return false;
            }
        }
        return true;
    }

    public function checkTicket(Player $player): bool {
        if ($this->cost["ticket"] >= 0) {
            if (TicketAPI::getInstance()->checkData($player) >= $this->cost["ticket"]) {
                return true;
            } else {
                $player->sendMessage("§bGacha §7>> §c所持Ticketが足りない為処理が中断されました");
                return false;
            }
        }
        return true;
    }

    public function checkEventTicket(Player $player): bool {
        if ($this->cost["eventTicket"] >= 0) {
            return true;
        }
        return true;
    }

}