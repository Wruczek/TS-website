<?php

use Wruczek\TSWebsite\Assigner;
use Wruczek\TSWebsite\Auth;
use Wruczek\TSWebsite\Utils\TeamSpeakUtils;
use Wruczek\TSWebsite\Utils\TemplateUtils;

require_once __DIR__ . "/private/php/load.php";

// Fetch cooldown period from the database
$assigner_cooldown_seconds = Assigner::getCooldownPeriod();

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

    // Check if the user is on cooldown
    $cooldownStatus = Assigner::isOnCooldown();
    $data["onCooldown"] = $cooldownStatus["onCooldown"];
    $data["cooldownRemaining"] = $cooldownStatus["cooldownRemaining"] ?? 0;

    if (isset($_POST["assigner"]) && $canUseAssigner && !$data["onCooldown"]) {
        $groups = array_keys($_POST["assigner"]); // get all group ids
        $groups = array_filter($groups, "is_int"); // only keep integers

        $changeGroups = Assigner::changeGroups($groups);
        $data["groupChangeStatus"] = $changeGroups;

        if ($changeGroups === 0) {
            // if groups have been successfully updated,
            // invalidate the cache
            Auth::invalidateUserGroupCache();
            // Update the last usage time
            Assigner::updateLastUsageTime();
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
