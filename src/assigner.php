<?php

use Wruczek\TSWebsite\Assigner;
use Wruczek\TSWebsite\Auth;
use Wruczek\TSWebsite\Utils\TeamSpeakUtils;
use Wruczek\TSWebsite\Utils\TemplateUtils;

require_once __DIR__ . "/private/php/load.php";

if (!TeamSpeakUtils::i()->checkTSConnection()) {
    TemplateUtils::i()->renderTemplate("assigner");
    exit;
}

$data = [
    "isLoggedIn" => Auth::isLoggedIn()
];

if (Auth::isLoggedIn()) {
    $data["canUseAssigner"] = Assigner::canUseAssigner();
    $data["cooldownRemaining"] = Assigner::getCooldownSecondsRemaining();

    if (isset($_POST["assigner"]) && $data["canUseAssigner"] && $data["cooldownRemaining"] <= 0) {
        $groups = array_keys($_POST["assigner"]); // get all group ids
        $groups = array_filter($groups, "is_int"); // only keep integers
        $data["groupChangeStatus"] = Assigner::changeGroups($groups);

        if ($data["groupChangeStatus"] === 0) {
            // if groups have been successfully updated,
            // invalidate the cache and update last use time
            Auth::invalidateUserGroupCache();
            Assigner::updateLastUseTime();
            // refresh cooldown time after group change
            $data["cooldownRemaining"] = Assigner::getCooldownSecondsRemaining();
        }
    }

    try {
        $assignerConfig = Assigner::getAssignerArray();
        $assignerConfig = array_chunk($assignerConfig, 2);
    } catch (\Exception $e) {
        $assignerConfig = null;
    }

    $data["assignerConfig"] = $assignerConfig;
}

TemplateUtils::i()->renderTemplate("assigner", $data);
