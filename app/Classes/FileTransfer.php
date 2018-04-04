<?php

namespace App\Classes;

/*
 * jQuery File Upload Plugin PHP Class
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

use App\Classes\Zebra_Image;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class FileTransfer {

    private $fileName;
    private $fileDir;
    private $docroot;
    
    
     public function __construct() {
         $this->docroot=$_SERVER['DOCUMENT_ROOT'] . '/files/';
     }
     
     
    public function uploadFile($file, $destination,$fileName,$removeSource=true,$isPublic=true) {
        $this->fileDir=$destination; 
        $this->fileName = $fileName;
        $file->move($this->docroot.$destination, $fileName);
        
        if (config('constants.store_location') == 'aws') {
            $this->transferToAws($removeSource,$isPublic);
        } elseif (config('constants.store_location') == 'google') {
            $this->transferToGoogle($removeSource);
        }
       
    }
    

    public function tranferFile($fileName, $source, $destination,$removeSource=true,$isPublic=true) {
        $this->fileDir=$destination;
        $this->fileName = $fileName;
        
       // if (config('constants.store_location') == 'local') {
            @copy($this->docroot.$source.$fileName, $this->docroot.$destination.$fileName);
       // } else
        if (config('constants.store_location') == 'aws') {
            $this->transferToAws($removeSource,$isPublic);
        } elseif (config('constants.store_location') == 'google') {
            $this->transferToGoogle($removeSource,$isPublic);
        }
        if($removeSource)
            unlink($this->docroot.$source.$fileName);
        return true;
        //echo config('constants.store_location'); exit;
    }

    public function resizeAndTransferFile($fileName, $size, $source, $destination){
        $imaged = new Zebra_Image();
        $imaged->source_path=$this->docroot.$source.$fileName;
        $imaged->target_path=$this->docroot.$destination.$fileName;
        $imaged->preserve_aspect_ratio = false;
        
        $size=  explode('X', $size);
        
        $imaged->resize($size['0'], $size['1'], ZEBRA_IMAGE_BOXED, -1);
        
        $this->fileDir=$destination;
        $this->fileName=$fileName;
        
        if (config('constants.store_location') == 'aws') {
            $this->transferToAws();
        } elseif (config('constants.store_location') == 'google') {
            $this->transferToGoogle();
        }
    }

    private function transferToAws($removeSource=true,$isPublic=true) {
        $s3 = AWS::createClient('s3');
        if($isPublic)
            $acl='public-read';
        else
            $acl='private';
        
        $result = $s3->putObject(array(
            'ACL' => $acl,
            'Bucket' => config('constants.awbucket'),
            'Key' => $this->fileDir . $this->fileName,
            'SourceFile' => $this->docroot.$this->fileDir . $this->fileName,
        ));
        if ($result['@metadata']['statusCode'] == 200) {
            if($removeSource)
                unlink($this->docroot.$this->fileDir . $this->fileName);
        }
    }

    private function transferToGoogle($removeSource=true) {
        // Code to transfer file to google

        /* Code to unlink file from root */
        // unlink($this->source . $this->fileName);
    }
    
    public function deleteFile($source,$file){
        if (config('constants.store_location') == 'local') {
            unlink($this->docroot.$source.$file);
        } else
        if (config('constants.store_location') == 'aws') {
            $s3 = AWS::createClient('s3');
            //$this->transferToAws();
           $s3->deleteObject(array(
                        'Bucket' => config('constants.awbucket'),
                        'Key' => $source . $file,
            ));
        } elseif (config('constants.store_location') == 'google') {
            // Code to delete from google bucket
        }
        
    }

}
