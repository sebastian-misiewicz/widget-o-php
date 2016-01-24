<?php

namespace Widgeto\Service;

use Aws\S3\S3Client;

class AwsS3PageSourceService implements IPageSourceService {
    
    private $s3client;
    
    private $bucket;
    
    function __construct() {
        $this->s3client = S3Client::factory(array(
            'signature' => 'v4',
            'region' => 'eu-central-1'
        ));
        
        $this->bucket = getenv("AWS_BUCKET");
    }
    
    public function putRendered($page, $content) {
        return $this->s3client->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => $page,
                'Body' => $content
            ));
    }
    
    public function getRendered($page) {
        return $this->s3client->getObject(array(
                'Bucket' => $this->bucket,
                'Key' => $page
            ))['Body'];
    }

    public function getTemplate($page) {
        return file_get_contents("templates/" . $page);
    }

}

