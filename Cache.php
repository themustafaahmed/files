<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

class Cache
{
    protected $filename;

    public function __construct()
    {
        $siteSettings = Loader::loadConfig('siteSettings');
        $this->filename = $siteSettings['cache'];
    }

    // a function to receive an write some data into a file
    function get_and_write($url, $cache_file)
    {
        $json = json_encode($url);
        $file = fopen($cache_file, 'w');
        fwrite($file, $json);
        fclose($file);
        return json_decode($json, true);
    }

    // a function that opens and and puts the data into a single var
    function read_content($path)
    {
        $file = fopen($path, 'r');
        $json = fread($file, filesize($path));
        fclose($file);
        return json_decode($json, true);
    }

    public function get($name, $time = 1)
    {
        $cache_file = $this->filename . '/cached/' . $name . '.json';

        if (file_exists($cache_file)) {

            // is there a cache file?
            $timed = (time() - filemtime($cache_file));

            // how old is the file?
            if ($timed < $time) {//60 *

                // get a new file 5 min
                $html = $this->read_content($cache_file);

                // read the content from cache
            } else {

                // create a new cache file
                $html = $this->get_and_write('', $cache_file);
            }
        } else {

            // no f ile? create a cache file
            $html = null;
        }
        return $html;
    }

    public function set($name, $data)
    {
        $cache_file = $this->filename . '/cached/' . $name . '.json';
        $this->get_and_write($data, $cache_file);
    }

}

?>


