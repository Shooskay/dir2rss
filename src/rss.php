<?php
require_once("rss_channel.php");
class Rss
{
    public $channel;

    public function __construct(string $config_path) {
        $this->channel = new Channel($config_path);
    }
    public function disp() {
        print "<rss xmlns:itunes=\"http://www.itunes.com/dtds/podcast-1.0.dtd\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" version=\"2.0\">\n";
        $this->channel->disp();
        print "</rss>\n";
    }
}

?>