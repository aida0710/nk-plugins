<?php

class Human {

	private int $年齢;
	private string $名前;

	public function __construct(int $年齢, string $名前) {
		$this->年齢 = $年齢;
		$this->名前 = $名前;
	}

	public function get年齢() : int {
		return $this->年齢;
	}

	public function get名前() : string {
		return $this->名前;
	}
}

var_dump(new Human(18, 'lazyperson0710'));

