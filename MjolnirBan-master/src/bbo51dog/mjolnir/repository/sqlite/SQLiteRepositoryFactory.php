<?php

declare(strict_types = 1);
namespace bbo51dog\mjolnir\repository\sqlite;

use bbo51dog\mjolnir\repository\AccountRepository;
use bbo51dog\mjolnir\repository\BanRepository;
use bbo51dog\mjolnir\repository\RepositoryFactory;
use SQLite3;

class SQLiteRepositoryFactory extends RepositoryFactory {

	private SQLite3 $db;

	public function __construct(string $file) {
		$this->db = new SQLite3($file);
		$this->register(AccountRepository::class, new SQLiteAccountRepository($this->db));
		$this->register(BanRepository::class, new SQLiteBanRepository($this->db));
	}

	public function close() : void {
		parent::close();
		$this->db->close();
	}

}
