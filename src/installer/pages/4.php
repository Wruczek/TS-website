<?php
if(!defined("__TSWEBSITE_VERSION")) die("Direct access not allowed");

use Wruczek\TSWebsite\Config;
use Wruczek\TSWebsite\ServerIconCache;
use Wruczek\TSWebsite\Utils\ApiUtils;

if (!empty($_POST)) {
    $queryhostname = trim($_POST["queryhostname"]);
    $queryport = trim($_POST["queryport"]);
    $queryserverport = trim($_POST["queryserverport"]);
    $queryusername = trim($_POST["queryusername"]);
    $querypassword = trim($_POST["querypassword"]);
    $querydisplayip = trim($_POST["querydisplayip"]);

    if (!empty($queryhostname) && !empty($queryport)
        && !empty($queryserverport) && !empty($queryusername)
        && !empty($querypassword) && !empty($querydisplayip)
    ) {
        require_once __PRIVATE_DIR . "/vendor/autoload.php";

        try {
            $tsNodeHost = TeamSpeak3::factory("serverquery://$queryhostname:$queryport/");
            $tsNodeHost->login($queryusername, $querypassword);
            $tsServer = $tsNodeHost->serverGetByPort($queryserverport);

            if(is_array($tsServer->getInfo())) {
                $tsVersion = $tsServer->getInfo()["virtualserver_version"];
                $tsBuildNo = $tsVersion->section("[", 1)->filterDigits()->toInt();

                if ($tsBuildNo < 1564054246) {
                    $errormessage =
                        'Your TeamSpeak server version is not supported.<br>' .
                        'Current version: ' . TeamSpeak3_Helper_Convert::versionShort($tsVersion) . ' (build ' . $tsBuildNo . ')' . '<br>' .
                        'Supported versions: 3.10.0 (build 1564054246) and newer';
                } else {
                    $configdata = [
                        "query_hostname" => $queryhostname,
                        "query_port" => $queryport,
                        "tsserver_port" => $queryserverport,
                        "query_username" => $queryusername,
                        "query_password" => $querypassword,
                        "query_displayip" => $querydisplayip,
                    ];

                    foreach ($configdata as $key => $value) {
                        try {
                            Config::i()->setValue($key, $value);
                        } catch (\Exception $e) {
                            die("Error while updating config in database, at " . htmlspecialchars($key) . " => " . htmlspecialchars($value));
                        }
                    }

                    $cacheIcons = true;
                }
            } else {
                $errormessage = 'Cannot retrieve server information';
            }
        } catch (Exception $e) {
            $errormessage = htmlspecialchars("Error " . $e->getCode() . ": " . $e->getMessage());

            if($e->getCode() === 520) {
                $errormessage .= '<br>You have entered wrong username and/or password. Please check it and try again.';
            }

            if($e->getCode() === 2568) {
                $errormessage .= '<br>Query account does not have permissions. ' . 'Click <a href="#" data-toggle="modal" ' .
                    'data-target="#queryperms">here</a> to view required permissions list.';
            }
        }
    }
}

if (isset($_GET["syncicons"])) {
    require_once __PRIVATE_DIR . "/vendor/autoload.php";

    set_time_limit(0); // this might take a while

    try {
        ServerIconCache::syncIcons();
        ApiUtils::jsonSuccess();
    } catch (\Exception $e) {
        ApiUtils::jsonError($e->getMessage(), $e->getCode());
    }

    exit;
}
?>

<!-- Modal -->
<div class="modal fade" id="queryperms" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Query permissions required by TS-website</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul>
                    <?php
                    if(!empty($GLOBALS["__REQUIRED_QUERY_PERMS"])) {
                        foreach ($GLOBALS["__REQUIRED_QUERY_PERMS"] as $perm) {
                            echo "<li><code>" . htmlspecialchars($perm) . "</code></li>";
                        }
                    } else {
                        echo "Error! <code>\$GLOBALS[\"__REQUIRED_QUERY_PERMS\"]</code> is not defined!";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($errormessage)) { ?>
<div class="text-center">
    <div class="alert alert-danger" style="display: inline-block">
        <?= $errormessage ?>
    </div>
</div>
<?php } ?>

<?php if(isset($cacheIcons)) { ?>

    <div class="text-center">
        <div class="alert alert-primary" style="display: inline-block">
            <div class="d-flex align-items-center">
                <div class="spinner-border spinner-border-sm mr-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                Caching icons from the TS3 server, please wait...
            </div>
        </div>
    </div>

<?php } else { ?>
    <div class="card">

        <div class="card-body">
            <h4 class="card-title text-center">Query details</h4>

            <div class="row justify-content-md-center">
                <form id="tsform" class="col-md-4" method="post" action="<?= "?step=$stepNumber" ?>">

                    <div class="alert alert-info">
                        If the TS3 server is not hosted locally, and you have access to the TS3
                        server files, then make sure to edit the <code>query_ip_allowlist.txt</code> file and
                        add the IP of the machine/VPS hosting TS&#8209;website to it, then restart the TS3 server.
                        Otherwise, TS&#8209;website might get rate-limited by the TS3 server and periodically stop working.
                    </div>

                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-link fa-fw"></i></span>
                        </div>
                        <input class="form-control" name="queryhostname" placeholder="Hostname" required autofocus autocomplete="off">
                        <div class="input-group-append">
                            <span class="input-group-text" data-toggle="tooltip" title="Your TeamSpeak IP address (without port).<br>Use '127.0.0.1' for localhost">
                                <i class="fa fa-question-circle fa-fw"></i>
                            </span>
                        </div>
                    </div>

                    <p class="text-muted text-center" style="font-size: 100%">
                        Use <code>127.0.0.1</code> as the hostname if TS-website and the
                        TS3 server are on the same machine/VPS.
                    </p>

                    <div class="row">
                        <div class="col input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-signal fa-fw"></i></span>
                            </div>
                            <input type="number" class="form-control" name="queryport" placeholder="Query port" required autocomplete="off">
                        </div>

                        <div class="col input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-signal fa-fw"></i></span>
                            </div>
                            <input type="number" class="form-control" name="queryserverport" placeholder="Server port" required autocomplete="off">
                        </div>
                    </div>

                    <p class="text-muted text-center" style="font-size: 100%">
                        Default query port: 10011, default server port: 9987.
                    </p>

                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user fa-fw"></i></span>
                        </div>
                        <input class="form-control" name="queryusername" placeholder="Query username" required autocomplete="off">
                        <div class="input-group-append">
                            <span class="input-group-text" data-toggle="tooltip" title="Its recommended to create special user account instead of serveradmin">
                                <i class="fa fa-exclamation-triangle color-danger fa-fw"></i>
                            </span>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock fa-fw"></i></span>
                        </div>
                        <input type="password" class="form-control" name="querypassword" placeholder="Query password" required autocomplete="off">
                    </div>

                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-font fa-fw"></i></span>
                        </div>
                        <input class="form-control" name="querydisplayip" placeholder="Displayed address" required autocomplete="off">
                        <div class="input-group-append">
                            <span class="input-group-text" data-toggle="tooltip"
                                  title="Friendly server address displayed to end users.<br>For example 'myserver.com' or 'ts.myclan.net'">
                                <i class="fa fa-question-circle fa-fw"></i>
                            </span>
                        </div>
                    </div>

                    <a href="#" data-toggle="modal" data-target="#queryperms" class="text-center">
                        <p>Query permissions required by TS-website</p>
                    </a>

                    <button id="submitform" type="submit" style="display: none"></button>
                </form>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="#" id="submitformalt" class="btn btn-primary float-right">
                Submit <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

<?php } ?>

<script>
    $("#submitformalt").click(function () {
        $("#submitform").click();
    });
</script>

<?php if(isset($cacheIcons)) { ?>
    <script>
        $.ajax({
            data: { syncicons: 1 },
            success: function (res) {
                if (!res.success) {
                    alert("An error occurred while trying to sync TS3 icons: " + JSON.stringify(res))
                }

                location = "?step=5"
            },
            error: function () {
                alert("An error occurred while trying to sync TS3 icons")
                location = "?step=5"
            }
        })
    </script>
<?php } ?>
