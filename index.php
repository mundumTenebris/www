<?php
/**
 * Created by JetBrains PhpStorm.
 * User: korobitsin.va
 * Date: 24.10.13
 * Time: 15:14
 * To change this template use File | Settings | File Templates.
 */
require_once 'WindowsAzure/WindowsAzure.php';
require_once 'PHPDebug.php';
require_once 'AzureAPI.php';


$debug = new PHPDebug();

$serverName = $_SERVER['SERVER_NAME'];
$protocol   = 'http';
$acountName = 'test4load';
$acountKey  = 'gc/b05ny/OAEl1I+6sWHFT2MB9oZ2K0H+AacDVQCKxgRPXfw0wDWfMkHAFef19/XjB9EUJiiovrMA19JWo5kXg==';

$azure_API = new AzureAPI($protocol, $acountName, $acountKey);

if ($_FILES['filename'][size] != 0) {
    $azure_API->addBlob();
    echo '<meta http-equiv="refresh" content="0; url=http://'.$serverName.'/">';
}
if(isset($_GET['DELET'])) {
    $azure_API->deletBlob();
    echo '<meta http-equiv="refresh" content="0; url=http://'.$serverName.'/">';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Просмотр Блобов</title>
    <link rel="stylesheet" type="text/css" href="site.css" media="screen">
    <link rel="stylesheet" type="text/css" href="Fluid960/grid.css" media="screen">
    <!--<link rel="stylesheet" type="text/css" href="Fluid960/reset.css" media="screen">-->
</head>
<body>
    <div id="clouds">
        <div class="cloud x1"></div>
        <!-- Time for multiple clouds to dance around -->
        <div class="cloud x2"></div>
        <div class="cloud x3"></div>
        <div class="cloud x4"></div>
        <div class="cloud x5"></div>
    </div>
    <div class="container_16">
        <div class="grid_16 top"></div>
        <div class="grid_16">
            <div class="form">
                <form enctype="multipart/form-data" action="" method="post">
                    <fieldset>
                        <legend>Загрузка файлов на Azure</legend>
                        <p>
                            <!--<input type="hidden" name="MAX_FILE_SIZE" value="300000">-->
                            <div class="center">
                                <input name="filename" type="file">
                            </div>
                        </p>
                        <input class="butt" type="submit" value="Отправить">
                    </fieldset>
                    <!--<input type="hidden" name="MAX_FILE_SIZE" value="30000">
                    Send this file: <input name="filename" type="file">
                    <input type="submit" value="Send File">-->
                </form>
            </div>
        </div>

        <div class="clear"></div>
        <div class="grid_16">
            <div class="box">

                <h2>Список Блобов</h2>
                <?php
                $azure_API->getBlobsList();
                ?>
            </div>
        </div>
    </div>

</body>
</html>