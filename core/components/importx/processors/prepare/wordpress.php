<?php
require_once 'wordpressimport.php';
/**
 * Class Wordpress
 */
class Wordpress extends WordpressImport
{
    /**
     * @var string $xml_string ~ uploaded data
     */
    public $xml_string;
    /* @var modX $modx */
    public $modx;
    /**
     * @var importX $importx
     */
    public $importx;

    /**
     * @var array $users ~ modx users
     */
    protected $users;

    /**
     * @var modMediaSource
     */
    protected $mediaSource = null;

    /**
     * @param modX $modx
     * @param importX $importx
     * @param $raw
     */
    public function __construct(modX &$modx, importX &$importx, $raw)
    {
        $this->modx =& $modx;
        $this->importx =& $importx;
        $this->xml_string = $raw;

        if ( isset($_POST['mediasource']) && is_numeric($_POST['mediasource']) ) {
            $source = $this->modx->getObject('sources.modMediaSource', $_POST['mediasource']);

            if (!empty($source)) {
                if ($source->initialize()) {
                    $this->mediaSource = & $source;
                    //$content = $source->getContent($path);
                }
            }
        }
        $this->config = array(
            // From Media Source:
            'asset_path' => '',
            'asset_url' => '',
            'parent' => $_POST['parent'],
            'template' => $_POST['template']
        );
    }

    /**
     * @return array|bool
     */
    public function process()
    {
        $data = $this->toArray($this->xml_string);
        if ( $data === false || count($data) <= 1) {
            $this->importx->errors[] = $this->modx->lexicon('importx.err.notenoughdata');
            return false;
        }
        return $data;
    }

    /**
     * See if we can find a matching user for the comment/post
     * @param string $username
     * @param int $default
     * @return int
     */
    public function matchCreator($username, $default = 0) {
        if ( isset($this->users[$username]) ) {
            return $this->users[$username];
        }
        /** @var modUser $user */
        $user = $this->modx->getObject('modUser',array('username' => $username));
        if ($user) {
            $this->users[$username] = $user->get('id');
            return $user->get('id');
        }
        return $default;
    }

    /**
     * @param $new_name
     * @param $file
     * @return string $url_name
     */
    protected function createAsset($new_name, $file, $remote=true)
    {
        //$this->modx->importx->log('error', 'Create Asset ');
        if ( is_object($this->mediaSource) ) {
            //$this->modx->importx->log('error', 'Yes: '.$file);
            if ($remote && strpos($file, 'http') !== 0) {
                $file = rtrim($this->config['link'], '/') . '/' . ltrim($file, '/');
            }
            // directory:
            $object_path = '';
            if ( in_array(strtolower(pathinfo($new_name, PATHINFO_EXTENSION)), array('doc', 'pdf', 'docx', 'csv', 'xlsx')) ) {
                $object_path = 'docs'.DIRECTORY_SEPARATOR;
            }
            $content = ($remote ? $this->getRemoteData($file) : file_get_contents($file));
            $file = rawurldecode($this->mediaSource->createObject($object_path, $new_name, $content));

            $basePath = $this->mediaSource->getBasePath($file);
            $baseUrl = $this->mediaSource->getBaseUrl($file);
            $file = str_replace($basePath,$baseUrl,$file);
            if ( $file !== false ) {
                $this->modx->importx->log('info', 'File transferred to: ' . $file);
            }
            return $file;
        }
        return false;
    }
}
return 'Wordpress';