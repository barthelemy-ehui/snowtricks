<?php
namespace App\Service;


use App\Entity\Resource;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    
    public function __construct($targetDirectory)
    {
    
        $this->targetDirectory = $targetDirectory;
    }
    
    public function upload(UploadedFile $file){
        $fileName = md5(uniqid('file_upload')) . '.'.$file->guessExtension();
        $file->move($this->getTargetDirectory(), $fileName);
        
        return $fileName;
    }
    
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
    
    public function getFileType(string $guessExtension)
    {
        $arrayOfTypeImgs = [
            "png" => Resource::IMAGE,
            "jpeg/jfif" => Resource::IMAGE,
            "jpeg 2000" => Resource::IMAGE,
            "exif" => Resource::IMAGE,
            "tiff" => Resource::IMAGE,
            "gif" => Resource::IMAGE,
            "bmp" => Resource::IMAGE,
            "jpg" => Resource::IMAGE,
            "jpeg" => Resource::IMAGE,
            "webm" => Resource::VIDEO,
            "mkv" => Resource::VIDEO,
            "flv" => Resource::VIDEO,
            "vob" => Resource::VIDEO,
            "ogv" => Resource::VIDEO,
            "ogg" => Resource::VIDEO,
            "drc" => Resource::VIDEO,
            "gif" => Resource::VIDEO,
            "gifv" => Resource::VIDEO,
            "mng" => Resource::VIDEO,
            "avi" => Resource::VIDEO,
            "mts" => Resource::VIDEO,
            "m2ts" => Resource::VIDEO,
            "mov" => Resource::VIDEO,
            "qt" => Resource::VIDEO,
            "wmv" => Resource::VIDEO,
            "yuv" => Resource::VIDEO,
            "rmvb" => Resource::VIDEO,
            "asf" => Resource::VIDEO,
            "mp4" => Resource::VIDEO,
            "m4p" => Resource::VIDEO,
            "m4v" => Resource::VIDEO,
            "mpg" => Resource::VIDEO,
            "mp2" => Resource::VIDEO,
            "mpeg" => Resource::VIDEO,
            "mpe" => Resource::VIDEO,
            "mpv" => Resource::VIDEO,
            "mpg" => Resource::VIDEO,
            "mpeg" => Resource::VIDEO,
            "m2v" => Resource::VIDEO,
            "m4v" => Resource::VIDEO,
            "svi" => Resource::VIDEO,
            "3gp" => Resource::VIDEO,
            "3g2" => Resource::VIDEO,
            "mxf" => Resource::VIDEO,
            "roq" => Resource::VIDEO,
            "nsv" => Resource::VIDEO,
        ];
        
        if(isset($arrayOfTypeImgs[$guessExtension])) {
            return $arrayOfTypeImgs[$guessExtension];
        }
        return null;
    }
    
    public function deleteFile($filename) {
        $path = $this->targetDirectory . '/' . $filename;
        if(file_exists($path)){
            unlink($path);
        }
    }
}