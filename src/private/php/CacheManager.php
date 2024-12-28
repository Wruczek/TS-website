<?php

namespace Wruczek\TSWebsite;

use Wruczek\PhpFileCache\PhpFileCache;
use Wruczek\TSWebsite\Utils\SingletonTait;
use Wruczek\TSWebsite\Utils\TeamSpeakUtils;

class CacheManager {

    use SingletonTait;

    private $cache;

    private $serverInfo;
    private $banList;
    private $clientList;
    private $channelList;
    private $serverGroupList;
    private $channelGroupList;

    private function __construct() {
        $this->cache = new PhpFileCache(__CACHE_DIR, "cachemanager");
    }

    private function tsNodeObjectToArray(array $object, bool $extendInfo = false): array {
        $data = [];

        foreach ($object as $obj) {
            $data[$obj->getId()] = $obj->getInfo($extendInfo);
        }

        return $data;
    }

    public function getServerInfo(bool $meta = false) {
        if ($this->serverInfo) {
            return $this->serverInfo;
        }

        return $this->serverInfo = $this->cache->refreshIfExpired("serverinfo", function () {
            if(TeamSpeakUtils::i()->checkTSConnection()) {
                try {
                    return TeamSpeakUtils::i()->getTSNodeServer()->getInfo();
                } catch (\TeamSpeak3_Exception $e) {
                    TeamSpeakUtils::i()->addExceptionToExceptionsList($e);
                }
            }

            return null;
        }, Config::get("cache_serverinfo"), $meta);
    }

    public function clearServerInfo(): void {
        $this->cache->eraseKey("serverinfo");
    }

    public function getBanList(bool $meta = false) {
        if ($this->banList) {
            return $this->banList;
        }

        return $this->banList = $this->cache->refreshIfExpired("banlist", function () {
            if(TeamSpeakUtils::i()->checkTSConnection()) {
                try {
                    return TeamSpeakUtils::i()->getTSNodeServer()->banList();
                } catch (\TeamSpeak3_Exception $e) {
                    if ($e->getCode() === 1281) { // database empty result set
                        return [];
                    }

                    TeamSpeakUtils::i()->addExceptionToExceptionsList($e);
                }
            }

            return null;
        }, Config::get("cache_banlist"), $meta);
    }

    public function clearBanList(): void {
        $this->cache->eraseKey("banlist");
    }

    public function getClientList(bool $meta = false) {
        if ($this->clientList) {
            return $this->clientList;
        }

        return $this->clientList = $this->cache->refreshIfExpired("clientlist", function () {
            if(TeamSpeakUtils::i()->checkTSConnection()) {
                try {
                    return $this->tsNodeObjectToArray(TeamSpeakUtils::i()->getTSNodeServer()->clientList());
                } catch (\TeamSpeak3_Exception $e) {
                    TeamSpeakUtils::i()->addExceptionToExceptionsList($e);
                }
            }

            return null;
        }, Config::get("cache_clientlist"), $meta); // Lower cache time because of login system
    }

    public function clearClientList(): void {
        $this->cache->eraseKey("clientlist");
    }

    public function getClient(int $cldbid) {
        $clients = $this->getClientList();

        if ($clients === null) {
            return null;
        }

        foreach ($clients as $client) {
            if ($client["client_database_id"] === $cldbid) {
                return $client;
            }
        }

        return null;
    }

    public function getChannelList(bool $meta = false) {
        if ($this->channelList) {
            return $this->channelList;
        }

        return $this->channelList = $this->cache->refreshIfExpired("channellist", function () {
            if(TeamSpeakUtils::i()->checkTSConnection()) {
                try {
                    return $this->tsNodeObjectToArray(TeamSpeakUtils::i()->getTSNodeServer()->channelList());
                } catch (\TeamSpeak3_Exception $e) {
                    TeamSpeakUtils::i()->addExceptionToExceptionsList($e);
                }
            }

            return null;
        }, Config::get("cache_channelist"), $meta);
    }

    public function clearChannelList(): void {
        $this->cache->eraseKey("channellist");
    }

    public function getServerGroupList(bool $meta = false) {
        if ($this->serverGroupList) {
            return $this->serverGroupList;
        }

        return $this->serverGroupList = $this->cache->refreshIfExpired("servergrouplist", function () {
            if(TeamSpeakUtils::i()->checkTSConnection()) {
                try {
                    return $this->tsNodeObjectToArray(TeamSpeakUtils::i()->getTSNodeServer()->serverGroupList());
                } catch (\TeamSpeak3_Exception $e) {
                    TeamSpeakUtils::i()->addExceptionToExceptionsList($e);
                }
            }

            return null;
        }, Config::get("cache_servergroups"), $meta);
    }

    public function clearServerGroupList(): void {
        $this->cache->eraseKey("servergrouplist");
    }

    public function getChannelGroupList(bool $meta = false) {
        if ($this->channelGroupList) {
            return $this->channelGroupList;
        }

        return $this->channelGroupList = $this->cache->refreshIfExpired("channelgrouplist", function () {
            if(TeamSpeakUtils::i()->checkTSConnection()) {
                try {
                    return $this->tsNodeObjectToArray(TeamSpeakUtils::i()->getTSNodeServer()->channelGroupList());
                } catch (\TeamSpeak3_Exception $e) {
                    TeamSpeakUtils::i()->addExceptionToExceptionsList($e);
                }
            }

            return null;
        }, Config::get("cache_channelgroups"), $meta);
    }

    public function clearChannelGroupList(): void {
        $this->cache->eraseKey("channelgrouplist");
    }
}
