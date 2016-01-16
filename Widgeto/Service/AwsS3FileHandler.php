<?php

namespace Widgeto\Service;

use Aws\S3\S3Client;

class AwsS3FileHandler {
    
    private $s3client;
    private $bucket;
    function __construct() {
        $this->s3client = S3Client::factory(array(
            'signature' => 'v4',
            'region' => 'eu-central-1'
        ));
        
        $this->bucket = getenv("AWS_BUCKET");
    }
    
    public function upload() {
        $upload = $this->get_upload_data("files");
        // Parse the Content-Disposition header, if available:
        $content_disposition_header = $this->get_server_var('HTTP_CONTENT_DISPOSITION');
        $file_name = $content_disposition_header ?
            rawurldecode(preg_replace(
                '/(^[^"]+")|("$)/',
                '',
                $content_disposition_header
            )) : null;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range_header = $this->get_server_var('HTTP_CONTENT_RANGE');
        $content_range = $content_range_header ?
            preg_split('/[^0-9]+/', $content_range_header) : null;
        $size =  $content_range ? $content_range[3] : null;
        $files = array();
        if (is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $upload is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                $files[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    $file_name ? $file_name : $upload['name'][$index],
                    $size ? $size : $upload['size'][$index],
                    $upload['type'][$index],
                    $upload['error'][$index],
                    $index,
                    $content_range
                );
            }
        } else {
            // param_name is a single object identifier like "file",
            // $upload is a one-dimensional array:
            $files[] = $this->handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                $file_name ? $file_name : (isset($upload['name']) ?
                        $upload['name'] : null),
                $size ? $size : (isset($upload['size']) ?
                        $upload['size'] : $this->get_server_var('CONTENT_LENGTH')),
                isset($upload['type']) ?
                        $upload['type'] : $this->get_server_var('CONTENT_TYPE'),
                isset($upload['error']) ? $upload['error'] : null,
                null,
                $content_range
            );
        }
        $response = array("files" => $files);
        echo json_encode($response);
    }
    
    protected function handle_file_upload(
            $uploaded_file, $name, $size, $type, $error,
            $index = null, $content_range = null) {
        
        $file = new \stdClass();
        $file->name = $name;
        $this->s3client->upload($this->bucket, $name, fopen($uploaded_file, 'rb'), 'public-read');
        
        return $file;
    }
    
    public function getAllFiles($type = "") {
        $iterator = $this->s3client->getIterator('ListObjects', array(
            'Bucket' => $this->bucket
        ));

        $files = array(
            "files" => []
        );

        foreach ($iterator as $object) {
            $name = $object['Key'];
            switch ($type) {
                case "image":
                    if (!preg_match('/\.(gif|jpe?g|png)$/i', $name)) {
                        continue;
                    }
                    break;
                default: 
                    continue;
            }
            
            
            $url = $this->s3client->getObjectUrl($this->bucket, $name);
            $files["files"][] = array(
                "name" => $name,
                "url" => $url,
                // "thumbnailUrl" => ?
            );
        }
        
        return $files;
    }
    
    public function delete() {
        $file_names = $this->get_file_names_params();
        if (empty($file_names)) {
            $file_names = array($this->get_file_name_param());
        }
        $response = array();
        foreach($file_names as $file_name) {
            $this->s3client->deleteObject(array(
                'Bucket' => $this->bucket,
                'Key' => $file_name,
            ));
            
            $response[$file_name] = true;
        }
        
        echo json_encode($response);
    }
    
    protected function get_singular_param_name() {
        return substr($this->options['param_name'], 0, -1);
    }

    protected function get_file_name_param() {
        $name = $this->get_singular_param_name();
        return basename(stripslashes($this->get_query_param($name)));
    }
    
    protected function get_file_names_params() {
        $params = $this->get_query_param("files");
        if (!$params) {
            return null;
        }
        foreach ($params as $key => $value) {
            $params[$key] = basename(stripslashes($value));
        }
        return $params;
    }
    
    protected function get_query_param($id) {
        return @$_GET[$id];
    }
    
    protected function get_upload_data($id) {
        return @$_FILES[$id];
    }
    
    protected function get_server_var($id) {
        return @$_SERVER[$id];
    }
    
    
}

