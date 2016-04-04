<?php


class S3Access {
    private static $BUCKET = 'cherry-shared-content2';
    private $client;
    
    public function __construct($s3Client) {
        $this->client = $s3Client;
    }
    
    public function createFile($name, $path) {
        try {
            $result = $this->client->putObject(array(
                'Bucket'     => S3Access::$BUCKET,
                'Key'        => $name,
                'SourceFile' => $path,
                'ACL'        => 'public-read'
            ));
            $urlExplode= explode("/", $result['ObjectURL']);
            $urlE_lenght = count($urlExplode);
            $url = $urlExplode[0]."//s3-eu-west-1.amazonaws.com/cherry-shared-content2/".$urlExplode[$urlE_lenght - 1];
          //  echo '<p>-------------result-------------'.$url;
            /*echo*/ $result['Expiration']/* . "\n"*/;
           /* echo*/ $result['ServerSideEncryption']/* . "\n"*/;
            /*echo*/ $result['ETag'] /*. "\n"*/;
            /*echo*/ $result['VersionId']/* . "\n"*/;
            /*echo*/ $result['RequestId'] /*. "\n"*/;
            //return $result['ObjectURL'];
            return $url;
        } catch (Exception $e) {
            echo '<p>Exception reçue S3 : ',  $e->getMessage(), "\n</p>";
            echo " path :".$path;
        }
    }
    
    public function getFile($name) {
        try {
            $result = $this->client->getObject(array(
                'Bucket'     => S3Access::$BUCKET,
                'Key'        => $name
            ));
            return $result;
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
    
    public function deleteFile($name) {
        try {
            $this->client->deleteObject(array(
                'Bucket'     => S3Access::$BUCKET,
                'Key'        => $name
            ));
        } catch (Exception $e) {
            echo '<p>Exception reçue : ',  $e->getMessage(), "\n</p>";
        }
    }
}
