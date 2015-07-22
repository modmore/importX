<?php
class WordpressImport
{
    /**
     * @param (Array) $config
     */
    protected $config = array();

    /**
     * @var array $assets_map ~ array(WP-path => MODX-Path)
     */
    protected $assets_map = array();
    /**
     * @var array $allowed_downloads
     */
    protected $allowed_downloads = array('pdf', 'doc', 'docx', 'txt', 'csv', 'xlsx',
                'png', 'jpg', 'jpeg', 'gif');

    public function __construct()
    {
        $this->config = array(
            'asset_path' => __DIR__.DIRECTORY_SEPARATOR.'import'.DIRECTORY_SEPARATOR,
            'asset_url' => 'import/',
            'parent' => 0,
            'template' => 1
        );
    }

    /**
     * @param $xml_file
     * @return array
     */
    public function fileToArray($xml_file)
    {
        /** @var SimpleXMLElement $data */
        $xml = $this->loadXMLFile($xml_file);
        if (  $xml === false ) {
            return false;
        }

        return $this->toArray($xml);
    }

    /**
     * @param string $xml
     * @return array $data ~ array(MODX-Column => value);
     */
    public function toArray($xml)
    {
        /** @var SimpleXMLElement $data */
        $xml = $this->loadXML($xml);
        if (  $xml === false ) {
            return false;
        }
        $this->config['link'] = $xml->channel->link;
        /**
         * links to assets/content/powergear/
         *                                  pdfs
         * URLs need to be rewritten to local
         *
         */
        $data = array();
        $imported = false;
        foreach ($xml->channel->item as $post) {
            $p = $this->postToArray($post);
            if ( $p ) {
                $data[] = $p;
            }
        }
        return $data;
    }
    /**
     *
     * @param (String) $xml ~ the file name & path
     * @return bool|string(XML)
     */
    public function loadXMLFile($file)
    {
        if ( !file_exists($file) ) {
            return false;
        }
        $contents = file_get_contents($file);
        return $contents;
    }
    /**
     * Get the parsed XML
     * @param (String) $xml ~ the xml as a string
     * @return bool|SimpleXMLElement
     */
    public function loadXML( $xml )
    {
        /** Remove meta tags: */
        $contents = '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL.
                substr($xml, mb_strpos($xml, '<rss version="2.0"'));
        /** Get rid of WP comments */
        $contents = str_replace('<!--more-->','',$contents);
        /** Fix improper <pre> placements */
        $contents = str_replace('</pre>]]>','</pre> ]]>',$contents);
        /** Fix WordPress bug with ]] tags and ~ inside content. Escape your stuff next time, WordPress. GG kthxbai. */
        $contents = preg_replace_callback('#\[\[\+(.*?)\]\]#si',array('WordpressImport','parseMODXPlaceholders'),$contents);
        $contents = preg_replace_callback('#\[\[\~(.*?)\]\]#si',array('WordpressImport','parseMODXLinks'),$contents);
        $contents = preg_replace_callback("#<!\[CDATA\[(.*?)\]\]>#si",array('WordpressImport','parseCData'),$contents);
        /* get rid of all the WP-specific special characters */
        $contents = str_replace(array(
                "\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6",
                '',
            ),array(
                "'", "'", '"', '"', '-', '--', '&#189;',
                '',
            ), $contents);

        $xml = simplexml_load_string($contents);
        return $xml;
    }

    /**
     * @param $matches
     * @return string
     */
    public static function parseMODXPlaceholders($matches) {
        return '&91;&91;+'.$matches[1].'&93;&93;';
    }

    /**
     * @param $matches
     * @return string
     */
    public static function parseMODXLinks($matches) {
        return '&91;&91;~'.$matches[1].'&93;&93;';
    }

    /**
     * @param $matches
     * @return mixed|string
     */
    public static function parseCData($matches) {
        $contents = $matches[0];
        if (!empty($matches[1])) {
            $contents = str_replace(array(
                '[',
                ']',
                '~',
                '>',
                '<',
            ),array(
                '&#91;',
                '&#93;',
                '&#182;',
                '&gt;',
                '&lt;',
            ),$matches[1]);
            $contents = '<![CDATA['.$contents.']]>';
        }
        return $contents;
    }



    /**
     * Turn the XML into an associated array ~ MODX field => value
     * @param SimpleXMLElement $post ~ represents a WP Post
     * @return (Array) $data
     */
    public function postToArray(SimpleXMLElement $post) {
        $postType = (string)$this->getXPath($post, 'wp:post_type');
        switch ($postType) {
            case 'post':
                /** no break */
            case 'page':
                break;

            case 'link':
                /** @TODO do something */
                /** no break */
            default:
                return false;
        }

        $data = array();

        $creator = $this->matchCreator((string)$post->children('dc',true)->creator);// $post->xpath('dc:creator'.'/text()'),1);
        /** @var SimpleXMLElement $wp */
        $wp = $post->children('wp',true);
        $pub_date =  strtotime((string)$post->pubDate);
        if (empty($pub_date)) {
            $pub_date = strtotime((string)$wp->post_date);
        }

        $data = array(
            'parent' => $this->config['parent'],//$this->container->get('id'),
            'pagetitle' => $this->parseContent((string)$post->title),
            'description' => $this->parseContent((string)$post->description),
            'alias' => $this->parseContent((string)$wp->post_name),
            'template' => $this->config['template'],
            'published' => $this->parsePublished($post),
            'publishedon' => $pub_date,
            'publishedby' => $creator,
            'createdby' => $creator,
            'createdon' => strtotime((string)$wp->post_date),
            'content' => $this->getAssets($this->parseContent((string)$post->children('content',true)->encoded)),
            'introtext' => $this->parseContent((string)$post->children('excerpt',true)->encoded),
            'show_in_tree' => true,
            //'class_key' => 'Article',
            //'context_key' => $this->container->get('context_key'),
        );

        //$this->importTags($article,$post);
        //$this->importComments($article,$post);
        return $data;
    }

    /**
     * @param $string
     * @return mixed|string
     */
    public function parseContent($string) {
        $string = (string)$string;
        $string = html_entity_decode((string)$string,ENT_COMPAT);
        $string = str_replace(array(
            '?',
            '?',
            '?',
            '[[',
            ']]',
        ),array(
            '&#147;',
            '&#148;',
            '&#189;',
            '&#91;&#91;',
            '&#93;&#93;',
        ),$string);
        return $string;
    }

    /**
     * Get the XPath string for the XML element
     * @param SimpleXMLElement $item
     * @param string $path
     * @return SimpleXMLElement|SimpleXMLElement[]
     */
    public function getXPath(SimpleXMLElement $item,$path) {
        $data = $item->xpath($path.'/text()');
        return array_key_exists(0,$data) ? $data[0] : $data;
    }

    /**
     * Parse the WP publish status
     * @param SimpleXMLElement $item
     * @return boolean
     */
    public function parsePublished(SimpleXMLElement $item) {
        $published = false;
        $status = (string)$this->getXPath($item,'wp:status');
        switch ($status) {
            case 'publish':
                $published = true;
                break;
        }
        return $published;
    }

    /**
     * See if we can find a matching user for the comment/post
     * @param string $username
     * @param int $default
     * @return int|mixed
     */
    public function matchCreator($username,$default = 0) {
        return $username;
        /** @var modUser $user */
        $user = $this->modx->getObject('modUser',array('username' => $username));
        if ($user) {
            return $user->get('id');
        }
        return $default;
    }

    public function getAsset(SimpleXMLElement $asset)
    {
        $data = array();
        return $data;
    }

    /**
     * Get all assets in HTML content and fix to local path
     * @param string $content
     * @return string $content
     */
    public function getAssets($content)
    {
        libxml_use_internal_errors (true);
        libxml_clear_errors();

        $dom = new DOMDocument();
        $dom->strictErrorChecking = false;
        $dom->loadHTML('<div>'.$content.'</div>');

        foreach ($dom->getElementsByTagName('img') as $img) {
            $new_src = $src = $img->getAttribute('src');
            if ( isset($this->assets_map[$src])) {
                $new_src = $this->assets_map[$src];

            } else if (strpos($src, 'data:image') !== 0 &&
                    in_array( mb_strtolower(pathinfo($src, PATHINFO_EXTENSION)), $this->allowed_downloads)
            ) { // no SVG
                // is this a valid image/document:
                $new_src = $this->createAsset($this->cleanFilename($src), $src);
                if ( !$new_src ) {
                    // @TODO Log error
                    $new_src = $src;
                }
                $this->assets_map[$src] = $new_src;
            }
            $img->setAttribute( 'src', $new_src );
        }

        foreach ($dom->getElementsByTagName('a') as $a) {
            // put your replacement code here
            $new_href = $href = $a->getAttribute('href');
            if ( isset($this->assets_map[$href])) {
                $new_href = $this->assets_map[$href];

            } else if (strpos($href, 'data:image') !== 0 &&
                in_array( mb_strtolower(pathinfo($href, PATHINFO_EXTENSION)), $this->allowed_downloads)
            ) { // no SVG
                // is this a valid image:
                $new_href = $this->createAsset($this->cleanFilename($href), $href);
                if ( !$new_href ) {
                    // @TODO Log error
                    $new_href = $href;
                }
                $this->assets_map[$href] = $new_href;
            }
            $a->setAttribute( 'href', $new_href );
        }

        //$content = $dom->saveHTML();
        $content = substr($dom->saveXML($dom->getElementsByTagName('div')->item(0)), 5, -6);
        return $content;
    }
    /**
     * @param $new_name
     * @param $file
     * @return string $url_name
     */
    protected function createAsset($new_name, $file, $remote=true)
    {
        $org = $file;
        if ( $remote && strpos($file, 'http') !== 0 ) {
            $file = rtrim($this->config['link'], '/').'/'.ltrim($file, '/');
        }
        if ( $remote && strpos($file, $this->config['link']) === false) {
            // different domain
            //return $org;
        }

        file_put_contents($this->config['asset_path'].$new_name, ( $remote ? $this->getRemoteData($file) : file_get_contents($file) ));
        $this->config['asset_path'];
        return $this->config['asset_url'].$new_name;
    }
    /**
     * gets the data from a URL
     * @param $url
     * @return mixed
     */
    protected function getRemoteData($url)
    {
        $ch = curl_init();
        $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0';
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    /**
     * @param string $filepath
     * @return string
     */
    protected function cleanFilename($filepath)
    {
        $filename = pathinfo($filepath, PATHINFO_FILENAME);
        $filename = preg_replace('/[^a-z0-9-]/', '', str_replace(' ', '-', $filename)).'.'.pathinfo($filepath, PATHINFO_EXTENSION);
        return $filename;
    }
}