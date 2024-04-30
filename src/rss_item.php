<?php
require_once("mediainfo.php");
class Item{
    public $url_base;
    public $author;
    public $media_info;
    public $url;
    public $filename;
    public function __construct($file_path,$url_base,$author){
        $this->url_base = $url_base;
        $this->author = $author;
        $this->filename = basename($file_path);
        $this->media_info = New MediaInfo($file_path) ;
        $this->url = $this->url_base.'/'.urlencode(basename($file_path));
    }
    public function disp(){
        if (isset($this->media_info->fileinfo['error'])){
            $this->_disp_file();
        }
        else{
            $this->_disp_media();
        }
    }
    private function _disp_file(){
        echo <<<EndOfItem
            <item>
                <link>{$this->url}</link>
                <title>{$this->filename}</title>
                <author>{$this->author}</author>
                <guid isPermaLink="false">{$this->url}</guid>
            </item>\n
        EndOfItem;
    }

    private function _disp_media(){
        echo <<<EndOfItem
            <item>
              <link>{$this->url}</link>
              <title>{$this->media_info->title}</title>
              <author>{$this->author}</author>
              <description>{$this->media_info->description}</description>
              <pubDate>{$this->media_info->pubDate}</pubDate>
              <enclosure url="{$this->url}" length="{$this->media_info->length}" type="{$this->media_info->type}" />
              <itunes:author>{$this->media_info->itunes_author}</itunes:author>
              <itunes:summary>{$this->media_info->itunes_summary}</itunes:summary>
              <itunes:subtitle>{$this->media_info->itunes_subtitle}</itunes:subtitle>
              <itunes:keywords>{$this->media_info->itunes_keywords}</itunes:keywords>
              <itunes:duration>{$this->media_info->itunes_duration}</itunes:duration>
              <guid isPermaLink="false">{$this->url}</guid>
            </item>\n
        EndOfItem;
        
    }
}
?>