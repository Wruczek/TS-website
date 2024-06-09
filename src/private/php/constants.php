<?php
define("__TSWEBSITE_VERSION",   "dev-2.1.1");
define("__TSWEBSITE_COMMIT",    "no-commit");
define("__BASE_DIR",            __DIR__ . "/../..");
define("__PRIVATE_DIR",         __BASE_DIR . "/private");
define("__CACHE_DIR",           __PRIVATE_DIR . "/cache");
define("__TEMPLATES_DIR",       __PRIVATE_DIR . "/templates");
define("__CONFIG_FILE",         __PRIVATE_DIR . "/dbconfig.php");
define("__LOCALDB_FILE",        __PRIVATE_DIR . "/.sqlite.db.php");
define("__INSTALLER_LOCK_FILE", __PRIVATE_DIR . "/INSTALLER_LOCK");
define("__DEV_MODE",            defined("DEV_MODE") || getenv("DEV_MODE") || file_exists(__PRIVATE_DIR . "/dev_mode"));
