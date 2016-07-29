<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bower for Codeigniter 3
 * @author Yoann VANITOU
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link https://github.com/maltyxx/bower
 */
class Bower
{
    /**
     * Instance de Codeigniter
     * @var stdClass 
     */
    private $CI;
    
    /**
     * Configuration
     * @var array 
     */
    private $config = array(
        'css' => array(),
        'js' => array()
    );
    
    /**
     * Les fichiers
     * @var array 
     */
    private $files = array();
    
    /**
     * Les paramètres
     * @var array 
     */
    private $params = array(
        'src' => NULL,
        'embed' => FALSE,
        'content' => NULL,
        'exist' => FALSE,
        'filemtime' => NULL
    );
    
    /**
     * Constructeur
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        // Instance de Codeigniter
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        
        // Initialise la configuration, si elle existe
        $this->initialize($config);
    }

    /**
     * Configuration
     * @param array $config
     * @return boolean
     */
    public function initialize(array $config = array())
    {
        // Si il y a pas de fichier de configuration
        if (empty($config)) {
            return FALSE;
        }

        // Merge les fichiers de configuration
        $this->config = array_merge($this->config, (isset($config['bower'])) ? $config['bower'] : $config);
        
        
        // Si il y a des fichiers JS
        if (!empty($config['css']) && is_array($config['css'])) {
            foreach ($config['css'] as $index => $line) {
                foreach ($line as $params) {
                    $this->files['css'][$index][] = $this->_add($params);
                }
            }
        }
        
        // Si il y a des fichiers JS
        if (!empty($config['js']) && is_array($config['js'])) {
            foreach ($config['js'] as $index => $line) {
                foreach ($line as $params) {
                    $this->files['js'][$index][] = $this->_add($params);
                }
            }
        }
        
        // Si il y a des fichiers MAP
        if (!empty($config['map']) && is_array($config['map'])) {
            foreach ($config['map'] as $index => $line) {
                foreach ($line as $params) {
                    $this->files['map'][$index][] = $this->_add($params);
                }
            }
        }
    }
    
    /**
     * Ajoute un fichier
     * @param type $src
     * @param array $options
     * @return array
     */
    public function add($src, array $options = array())
    {
        $params = array_merge($this->params, $options);
        $params['src'] = $src;
        return $this->_add($params);
    }

    /**
     * Retourne les fichiers CSS
     * @param string $index
     * @return mixed
     */
    public function css($index)
    {
        if (isset($this->files['css'][$index])) {
            return $this->files['css'][$index];
        } else {
            return FALSE;
        }
    }

    /**
     * Retourne les fichiers JS
     * @param string $index
     * @return mixed
     */
    public function js($index)
    {
        if (isset($this->files['js'][$index])) {
            return $this->files['js'][$index];
        } else {
            return FALSE;
        }
    }
    
    /**
     * Retourne les fichiers MAP
     * @param string $index
     * @return mixed
     */
    public function map($index)
    {
        if (isset($this->files['map'][$index])) {
            return $this->files['map'][$index];
        } else {
            return FALSE;
        }
    }
    
    /**
     * Ajout de fichier
     * @param array $options
     * @return array
     */
    private function _add(array $options)
    {
        // Merge les paramètres
        $params = array_merge($this->params, $options);
        
        // Retire le l'url de base
        $path_file = (strstr($params['src'], base_url())) ? strtr($params['src'], array(base_url() => '')) : '';
        
        // Si le fichier se trouve en local
        if (is_file($path_file) && is_readable($path_file)) {
            $params['filemtime'] = filemtime($path_file);
            $params['src'] .= "?v={$params['filemtime']}";
            $params['exist'] = TRUE;
            
            // Si le contenu du fichier doit être enregistré
            if  (isset($params['embed']) && $params['embed'] === TRUE) {
                $params['content'] = file_get_contents($path_file);
            }
        }

        return $params;
    }
    
}
