<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\database;

class Query {

    public const INIT = "auctionhouse.init";
    public const INSERT = "auctionhouse.insert";
    public const DELETE = "auctionhouse.delete";
    public const SET_EXPIRED = "auctionhouse.expired";

    public const COUNT_ALL = "auctionhouse.count.all";
    public const COUNT_ACTIVE = "auctionhouse.count.active.all";
    public const COUNT_ACTIVE_UUID = "auctionhouse.count.active.uuid";
    public const COUNT_ACTIVE_USERNAME = "auctionhouse.count.active.username";
    public const COUNT_EXPIRED = "auctionhouse.count.expired.all";
    public const COUNT_EXPIRED_UUID = "auctionhouse.count.expired.uuid";

    public const FETCH_ID = "auctionhouse.fetch.id";
    public const FETCH_ALL = "auctionhouse.fetch.all";
    public const FETCH_ACTIVE_NEXT = "auctionhouse.fetch.active.next";
    public const FETCH_ACTIVE_UUID = "auctionhouse.fetch.active.uuid";
    public const FETCH_ACTIVE_USERNAME = "auctionhouse.fetch.active.username";

    public const FETCH_EXPIRED_UUID = "auctionhouse.fetch.expired.uuid";
}