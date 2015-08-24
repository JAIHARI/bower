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
    private $config = [
        'file' => 'bower.json',
        'build' => FALSE
    ];

    /**
     * Liste des fichier CSS
     * @var array 
     */
    private $css = [];

    /**
     * Liste des fichier JS
     * @var array 
     */
    private $js = [];
    
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
     */
    public function initialize(array $config = array())
    {
        // Si il y a pas de fichier de configuration
        if (empty($config)) {
            return;
        }

        // Merge les fichiers de configuration
        $this->config = array_merge($this->config, (isset($config['bower'])) ? $config['bower'] : $config);

        // Ajoute le path au fichier
        $this->config['file'] =  FCPATH.$this->config['file'];

        // Si le fichier existe
        if (is_file($this->config['file']) && is_readable($this->config['file'])) {
            $content = file_get_contents($this->config['file']);

            // Si il y a un contenu
            if (!empty($content)) {
                $json = json_decode($content, TRUE);

                // Suprimme le contenu
                unset($content);

                // Si il y a des fichiers CSS
                if (isset($json['css']) && is_array($json['css'])) {
                    $this->_files($json['css'], 'css');
                }

                // Si il y a des fichiers JS
                if (isset($json['js']) && is_array($json['js'])) {
                    $this->_files($json['js'], 'js');
                }
            }
        }
    }
    
    /**
     * Ajoute un fichier
     * @param string
     * @return array
     */
    public function add($file) {
        $output = ['src' => $file, 'build' => FALSE, 'exist' => FALSE];
        
        if (strstr($file, base_url())) {
            $path_file = strtr($file, [base_url() => '']);
        }
        
        if (is_file($path_file) && is_readable($path_file)) {
            $output['src'] .= '?v='.filemtime($path_file);
            $output['exist'] = TRUE;
        }

        return $output;
    }

    /**
     * Retourne les fichiers CSS
     * @param string|NULL $index
     * @return mixed
     */
    public function css($index = NULL) {
        if ($index === NULL) {
            return $this->css;
        } else if (isset($this->css[$index])) {
            return $this->css[$index];
        } else {
            return FALSE;
        }
    }

    /**
     * Retourne les fichiers JS
     * @param string|NULL $index
     * @return mixed
     */
    public function js($index = NULL) {
        if ($index === NULL) {
            return $this->js;
        } else if (isset($this->js[$index])) {
            return $this->js[$index];
        } else {
            return FALSE;
        }
    }
    
    /**
     * Ajoute des fichiers
     * @param array $files
     * @param string $format
     */
    private function _files(array $files = [], $format = 'js') {
        foreach ($files as $build => $content) {
            $group = basename($build, ".min.$format");

            if ($this->config['build']) {
                $output = $this->add(base_url($build));
                $output['build'] = TRUE;
                $this->{$format}[$group][] = $output;
            } else {
                foreach ($content as $file) {
                    $this->{$format}[$group][] = $this->add(base_url($file));
                }
            }
        }
    }
}
