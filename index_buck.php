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
/*require_once 'AzureAPI.php';*/

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Blob\Models\CreateContainerOptions;
use WindowsAzure\Blob\Models\PublicAccessType;
use WindowsAzure\Common\ServiceException;

$debug = new PHPDebug();

$serverName = $_SERVER['SERVER_NAME'];
$protocol   = 'http';
$acountName = 'test4load';
$acountKey  = 'gc/b05ny/OAEl1I+6sWHFT2MB9oZ2K0H+AacDVQCKxgRPXfw0wDWfMkHAFef19/XjB9EUJiiovrMA19JWo5kXg==';

$connectionString = "DefaultEndpointsProtocol=$protocol;AccountName=$acountName;AccountKey=$acountKey";

// Create blob REST proxy.
$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);
/*$createContainerOptions = new CreateContainerOptions();

$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

// Set container metadata
$createContainerOptions->addMetaData("key1", "value1");
$createContainerOptions->addMetaData("key2", "value2");*/

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Просмотр Блобов</title>
    <link rel="stylesheet" type="text/css" href="site.css" media="screen">
    <link rel="stylesheet" type="text/css" href="Fluid960/grid.css" media="screen">
    <link rel="stylesheet" type="text/css" href="Fluid960/reset.css" media="screen">
</head>
<body>
    <div class="container_12">
        <?php
        echo $_SERVER['SERVER_NAME'];
        ?>
        <form enctype="multipart/form-data" action="" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="30000">
            Send this file: <input name="filename" type="file">
            <input type="submit" value="Send File">
        </form>
    </div>
</body>
</html>
<?php
// List blobs.
$blob_list = $blobRestProxy->listBlobs("mycontainer");
$blobs = $blob_list->getBlobs();

echo "<div class='list'>";
foreach($blobs as $blob)
{
    $blobs_name = $blob->getName();
    echo "<div class='list_id'><a href='?DELET=true&blob_name=$blobs_name'>".$blobs_name."</a>: ".$blob->getUrl()."</div>";
}
echo "</div>";

if(isset($_GET['DELET'])) {
    try {
        // Delete container.
        $blobRestProxy->deleteBlob("mycontainer", $_GET['blob_name']);
        echo '<meta http-equiv="refresh" content="0; url=http://'.$serverName.'/">';
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/en-us/library/windowsazure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
}

if ($_FILES['filename'][size] != 0) {
    $fileName = $fileName.basename($_FILES['filename'][name]);
    /*echo "$fileName <br>";
    print_r($_FILES['filename']);*/
    //$content = fopen('c:\kk.txt', 'r');
    $blob_name = "$fileName";

    try {
        /*// Create container.
        $blobRestProxy->createContainer("mycontainer", $createContainerOptions);*/
        //Upload blob
        $blobRestProxy->createBlockBlob("mycontainer", $blob_name, $fileName);
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/en-us/library/windowsazure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
    echo '<meta http-equiv="refresh" content="0; url=http://'.$serverName.'/">';
} else {
    echo "Файл не выбран";
}
?>