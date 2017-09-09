<?php


class ImageProcessor {
    
    private $original_image; //original image
    private $image_data; //getimagesize data
    private $file_name; //original filename
    public $image; //the image we are working with
    
    
    function __construct($path) {
        $this->image_data = getimagesize($path);
        $this->file_name = basename($path);
        $this->checkMime($path);
    }
    
    //check the mime type of the image (no files allowed!!)
    private function checkMime($path) {
        
        $mimetype = $this->image_data["mime"];
        
        switch ($mimetype) {
            case "image/jpeg":
                $this->original_image = imagecreatefromjpeg($path);
                break;
            case "image/png":
                $this->original_image = imagecreatefrompng($path);
                break;
            case "image/gif":
                $this->original_image = imagecreatefromgif($path);
                break;
            default:
                throw new Exception("ImageProcessor mime type value not recognised: " . $mimetype);
        }
    }
    
    //reset the image back to the original
    public function resetImage() {
        $this->image = $this->original_image;
    }
    
    private function initImage($width, $height) {
        $this->image = imagecreatetruecolor($width, $height);
        imagealphablending( $this->image, false );
        imagesavealpha( $this->image, true );
    }
    
    //resize image with height, always keeps aspect ratio
    public function resizeImage($height = 200) {
        $width = ($this->image_data[0] / $this->image_data[1]) * $height;
        $this->initImage($width, $height);
        imagecopyresampled($this->image, $this->original_image, 0, 0, 0, 0, $width, $height, $this->image_data[0], $this->image_data[1]);
    }
    
    
    //returns base64 url string of the image
    public function getBase64Image() {
        ob_start();
        
        $mimetype = $this->image_data["mime"];
        
        switch ($mimetype) {
            case "image/jpeg":
                imagejpeg($this->image);
                break;
            case "image/png":
                imagepng($this->image);
                break;
            case "image/gif":
                imagegif($this->image);
                break;
            default:
                throw new Exception("ImageProcessor mime type value not recognised: " . $mimetype);
        }
        
        $data = ob_get_contents();
        ob_end_clean();
        
        return "data:" . $mimetype . ";base64," . base64_encode($data);
    }
    
    //TO DO
    public function saveImage($location, $name) {
        if (empty($name)) {
            $name = $this->file_name;
        }
    }
    
}


?>