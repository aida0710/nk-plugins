<?php

declare(strict_types = 1);

class Main {

	private string $name;

	public function __construct(string $name) {
		if (mt_rand(0, 1) === 0) {
			$this->name = $name;
		}
	}

	public function getName() : string {
		if (!isset($this->name)) {
			return "メンバ変数が定義されてないよ！";
		}
		return $this->name;
	}
}

echo (new Main("0が選ばれました"))->getName();
