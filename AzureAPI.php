<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TankisT
 * Date: 26.10.13
 * Time: 16:52
 * To change this template use File | Settings | File Templates.
 */
require_once 'WindowsAzure/WindowsAzure.php';

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Blob\Models\CreateContainerOptions;
use WindowsAzure\Blob\Models\PublicAccessType;
use WindowsAzure\Common\ServiceException;

class AzureAPI {
    private $CONTAINER = "mycontainer";
    private $protocol;
    private $acountName;
    private $acountKey;
    private $connectionString;
    private $blobRestProxy;

    public function __construct($protocol, $acountName, $acountKey) {
        $this->protocol   = $protocol;
        $this->acountName = $acountName;
        $this->acountKey  = $acountKey;

        $this->connectionString = "DefaultEndpointsProtocol=$protocol;AccountName=$acountName;AccountKey=$acountKey";
        $this->blobRestProxy = ServicesBuilder::getInstance()->createBlobService($this->connectionString);
    }

    public function addContainer() {
        //
    }
    //Upload a Blob into a container
    public function addBlob() {
        $fileName = $fileName.basename($_FILES['filename'][name]);
        $blob_name = "$fileName";

        try {
            //Upload blob
            $this->blobRestProxy->createBlockBlob($this->CONTAINER, $blob_name, $fileName);
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
    // List blobs.
    public function getBlobsList() {
        $blob_list = $this->blobRestProxy->listBlobs("mycontainer");
        $blobs = $blob_list->getBlobs();

        echo "<div class='list'>";
        foreach($blobs as $blob)
        {
            $blobs_name = $blob->getName();
            echo "<div class='list_id'>
                      <div class='blob_name'>
                          <a href='?DELET=true&blob_name=$blobs_name'>".$blobs_name."</a>".
                      "</div>".
                      "<div class='blob_url'>".
                          $blob->getUrl().
                       "</div>".
                  "</div>";
        }
        echo "</div>";
    }
    public function deletBlob() {
        try {
            // Delete container.
            $this->blobRestProxy->deleteBlob($this->CONTAINER, $_GET['blob_name']);
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
}