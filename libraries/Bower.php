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
     * Configuration
     * @var array 
     */
    private $config = [
        'file' => 'bower.json',
        'usebuild' => FALSE
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
                    $this->setCss($json['css']);
                }

                // Si il y a des fichiers JS
                if (isset($json['js']) && is_array($json['js'])) {
                    $this->setJs($json['js']);
                }
            }
        }
    }

    /**
     * Retourne les fichiers CSS
     * @param string|NULL $index
     * @return mixed
     */
    public function getCss($index = NULL) {
        if ($index === NULL) {
            return $this->css;
        } else if (isset($this->css[$index])) {
            return $this->css[$index];
        } else {
            return FALSE;
        }
    }
    
    /**
     * Ajoute des fichiers CSS
     * @param array $files
     */
    public function setCss(array $files = []) {
        $this->setFiles($files, 'css');
    }
    
    /**
     * Retourne les fichiers JS
     * @param string|NULL $index
     * @return mixed
     */
    public function getJs($index = NULL) {
        if ($index === NULL) {
            return $this->js;
        } else if (isset($this->js[$index])) {
            return $this->js[$index];
        } else {
            return FALSE;
        }
    }

    /**
     * Ajoute des fichiers JS
     * @param array $files
     */
    public function setJs(array $files = []) {
        $this->setFiles($files, 'js');
    }
    
    /**
     * Ajoute des fichiers
     * @param array $files
     * @param string $format
     */
    private function setFiles(array $files = [], $format = 'js') {
        foreach ($files as $build => $content) {
            $group = basename($build, ".min.$format");
            
            if ($this->config['usebuild']) {
                if (is_file($build) && is_readable($build)) {
                    $version = filemtime($build);
                    $this->{$format}[$group][] = "$build?v=$version";
                }
            } else {
                foreach ($content as $file) {
                    if (is_file($file) && is_readable($file)) {
                        $version = filemtime($file);
                        $this->{$format}[$group][] = "$file?v=$version";
                    }
                }
            }
        }
    }
}
