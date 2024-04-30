<?php
require_once(__DIR__."/vendor/autoload.php");

class MediaInfo{
    public $filename;
    public $title;
    public $description;
    public $pubDate;
    public $url;
    public $itunes_author;
    public $itunes_summary;
    public $itunes_subtitle;
    public $itunes_keywords;
    public $itunes_duration;
    public $length;
    public $type;
    public $fileinfo;

    public function __construct($file_path){
        $this->filename = basename($file_path);
        $this->length = filesize($file_path);
        $this->pubDate = date("r",filemtime($file_path));
        $getid3 = new getID3();
        $this->fileinfo = $getid3->analyze($file_path);
		if (isset($this->fileinfo['error'])) {
            return;
        }

        getid3_lib::CopyTagsToComments($this->fileinfo);
        $this->title = $this->fileinfo['comments']['title'][0] ;#"notitle";
        $this->description = $this->fileinfo['comments']['album'][0]; #"nodescrption";
        $this->itunes_author = $this->fileinfo['comments']['artist'][0]; #"nobody";
        $this->itunes_summary = "";
        $this->itunes_subtitle = "";
        $this->itunes_keywords = "";
        $this->itunes_duration = round($this->fileinfo['playtime_seconds']);
        $this->type = $this->fileinfo['mime_type']; #"audio/mp3";
    }
}


#class mp3 extends MediaInfo{
#    public function __construct($file_path){
#        parent::__construct($file_path);

?>