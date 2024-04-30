<?php
require_once(__DIR__."/vendor/autoload.php");
require_once("rss_item.php");
/// 更新日時降順で並び替えるための関数
function sort_by_lastmod($a, $b) {return filemtime($b) - filemtime($a);}

class Channel
{
    public $config_path;
    public $channel_info;
    public $items = [];

    public function __construct($config) {
        $this->config_path = $config;
        $this->_load_config();
        $this->_load_items();
    }
    private function _load_config(){
        $this->channel_info = \Symfony\Component\Yaml\Yaml::parsefile($this->config_path);
    }

    private function _load_items(){
        $max = $this->channel_info['item_max'];
        if (!($max > 0 ))
            $max = 1;
        $media_dir = dir($this->channel_info['media_path']); # "./media"
        $media_path = $this->channel_info['media_path'];
        $files = glob( $media_path.'/*' );
        #$files = $this->_get_media_file_list($media_path);
        usort( $files, "sort_by_lastmod" );
        $url_base = $this->channel_info['url_base'];
        $author = $this->channel_info['email'];
        foreach( $files as $file ) {
            $item= new Item($file,$url_base,$author);
            $this->items[] = $item;
            if (count($this->items) >= $max){
                break;
            }
        }
        return;
    }
    private function _get_media_file_list($media_path){
        $exts=["*mp3","*m4a"];
        $glob_list = implode(',', $exts);
        $target = $media_path."/{{$glob_list}}";
        return glob($target, GLOB_BRACE);
    }

    public function disp() {
        #var_dump($this->xtems);
        $date_time =date("r");
        echo <<<EOL
        <channel>
         <title>{$this->channel_info['title']}</title>
         <link>{$this->channel_info['link']}</link>
         <description>{$this->channel_info['description']}</description>
         <category>{$this->channel_info['category']}</category>
         <pubDate{$date_time}</pubDate>
         <lastBuildDate>{$date_time}</lastBuildDate>
         <language>{$this->channel_info['language']}</language>
         <copyright>{$this->channel_info['copyright']}</copyright>
         <generator>{$this->channel_info['generator']}</generator>
         <managingEditor>{$this->channel_info['email']}</managingEditor>
         <webMaster>{$this->channel_info['email']}</webMaster>
         <ttl>{$this->channel_info['ttl']}</ttl>
         <itunes:author>{$this->channel_info['itunes_author']}</itunes:author>
         <itunes:subtitle>{$this->channel_info['itunes_subtitle']}</itunes:subtitle>
         <itunes:category text="{$this->channel_info['itunes_category']}">
           <itunes:category text="{$this->channel_info['itunes_subcategory']}"/>
         </itunes:category>
         <itunes:summary>{$this->channel_info['itunes_summary']}</itunes:summary>
         <itunes:keywords>{$this->channel_info['itunes_keywords']}</itunes:keywords>
         <itunes:owner>
           <itunes:name>{$this->channel_info['itunes_author']}</itunes:name>
           <itunes:email>{$this->channel_info['email']}</itunes:email>
         </itunes:owner>
         <itunes:explicit>{$this->channel_info['itunes_explicit']}</itunes:explicit>
         <itunes:image href="{$this->channel_info['image_url']}" />
         <image>
           <url>{$this->channel_info['image_url']}</url>
           <title>{$this->channel_info['title']}</title>
           <link>{$this->channel_info['link']}</link>
         </image>\n
        EOL;
        #var_dump($this->items);
        foreach($this->items as $item){
            $item->disp();
        } 
        echo "</channel>\n";
    }
}


?>