<?php

declare(strict_types = 1);
namespace lazyperson0710\Gacha\Calculation;

use lazyperson0710\Gacha\database\GachaItemAPI;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use function array_map;
use function array_sum;
use function mt_rand;

class RankCalculation {

	/** @var array<string, float|int> */
	private array $tmp_table;
	private array $rank;
	protected int $num;

	private array $probability;
	private array $cost;

	public function __construct(string $categoryName) {
		$this->probability = GachaItemAPI::getInstance()->rankProbability[$categoryName][0];
		$this->cost = GachaItemAPI::getInstance()->categoryCost[$categoryName][0];
		$this->tmp_table = $this->getTmpTable();
		$this->initTable();
	}

	public function initTable() : void {
		$this->rank = array_map(static fn ($value) => $value * 1000, $this->tmp_table);
		$this->num = (int) array_sum($this->rank);
	}

	public function run($num, Player $player) : string|null {
		$result = null;
		for ($i = 1; $i <= $num; $i++) {
			if ($this->checkMoney($player) !== true) return $result;
			if ($this->checkTicket($player) !== true) return $result;
			EconomyAPI::getInstance()->reduceMoney($player->getName(), $this->cost['moneyCost']);
			TicketAPI::getInstance()->reduceTicket($player, $this->cost['ticketCost']);
			$rand = mt_rand(1, $this->num);
			$count = 0;
			foreach ($this->rank as $table => $item) {
				$count += $item;
				if ($rand <= $count) {
					$result .= $table . ',';
					continue 2;
				}
			}
		}
		return $result;
	}

	protected function getTmpTable() : array {
		return [
			'C' => $this->probability['C'],
			'UC' => $this->probability['UC'],
			'R' => $this->probability['R'],
			'SR' => $this->probability['SR'],
			'SSR' => $this->probability['SSR'],
			'L' => $this->probability['L'],
		];
	}

	public function checkMoney(Player $player) : bool {
		if ($this->cost['moneyCost'] >= 0) {
			if (EconomyAPI::getInstance()->myMoney($player->getName()) >= $this->cost['moneyCost']) {
				return true;
			} else {
				SendMessage::Send($player, '所持金が足りない為処理が中断されました', 'Gacha', false);
				return false;
			}
		}
		return true;
	}

	public function checkTicket(Player $player) : bool {
		if ($this->cost['ticketCost'] >= 0) {
			if (TicketAPI::getInstance()->checkData($player) >= $this->cost['ticketCost']) {
				return true;
			} else {
				SendMessage::Send($player, '所持Ticketが足りない為処理が中断されました', 'Gacha', false);
				return false;
			}
		}
		return true;
	}

}
