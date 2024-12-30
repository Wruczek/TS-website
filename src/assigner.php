<?php

use Wruczek\TSWebsite\Assigner;
use Wruczek\TSWebsite\Auth;
use Wruczek\TSWebsite\Utils\TeamSpeakUtils;
use Wruczek\TSWebsite\Utils\TemplateUtils;

require_once __DIR__ . "/private/php/load.php";

$cooldownSeconds = Assigner::getCooldownPeriod();

if (!TeamSpeakUtils::i()->checkTSConnection()) {
    TemplateUtils::i()->renderTemplate("assigner");
    exit;
}

$data = [
    "isLoggedIn" => Auth::isLoggedIn()
];

if (Auth::isLoggedIn()) {
    $canUseAssigner = Assigner::canUseAssigner();
    $data["canUseAssigner"] = $canUseAssigner;
    $data["cooldownRemaining"] = Assigner::getCooldownSecondsRemaining();

    if (isset($_POST["assigner"]) && $canUseAssigner && $data["cooldownRemaining"] <= 0) {
        $groups = array_keys($_POST["assigner"]); // get all group ids
        $groups = array_filter($groups, "is_int"); // only keep integers

        $changeGroups = Assigner::changeGroups($groups);
        $data["groupChangeStatus"] = $changeGroups;

        if ($changeGroups === 0) {
            // if groups have been successfully updated,
            // invalidate the cache and update last use time
            Auth::invalidateUserGroupCache();
            Assigner::updateLastUseTime();
        }
    }

    try {
        $assignerConfig = Assigner::getAssignerArray();
        $assignerConfig = array_chunk($assignerConfig, 2);
    } catch (\Exception $e) {}

    // suppress warnings - might be null on exception
    $data["assignerConfig"] = @$assignerConfig;
}

TemplateUtils::i()->renderTemplate("assigner", $data);
