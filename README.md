# Bower
Bower for Codeigniter 3

## Requirements

- PHP 5.4.x (Composer requirement)
- CodeIgniter 3.0.x

## Installation
### Step 1 Installation by Composer
#### Run composer
```shell
composer require maltyxx/bower
```
### Step 2 Configuration
Duplicate configuration file `./application/third_party/origami/config/bower.php` in `./application/config/bower.php`.

### Step 3 Examples
Grunt file is located in `/Gruntfile.js`.
```js
module.exports = function(grunt) {
    grunt.initConfig({
        cssmin: {
            css: {
                files: grunt.config("bower").css
            }
        },
        uglify: {
            js: {
                files: grunt.config("bower").js
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('build', ['cssmin:css', 'uglify:js']);
};
```

Bower file is located in `/bower.json`.
```json
{
    "css": {
         "assets/build/default.min.css": [
            "bower_components/angular/angular-csp.css"
        ]
    },
    "js": {
        "assets/build/default.min.js": [
            "bower_components/jquery/dist/jquery.js",
            "bower_components/angular/angular.js"
        ]
    },
    "dependencies": {
        "jquery": "2.1.*",
        "angular": "1.4.*"
    }
}
```

Controller file is located in `/application/controllers/Exemple.php`.
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exemple extends CI_Controller {

	public function index()
	{
		$this->load->add_package_path(APPPATH.'third_party/bower');
        $this->load->library('bower');
        $this->load->remove_package_path(APPPATH.'third_party/bower');
        
        $this->load->view('exemple_index', [
            'css' => $this->bower->css('default'),
            'js' => $this->bower->js('default')
        ]);
	}

}
```

View file is located in `/application/views/exemple_index.php`.
```php
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
    <?php foreach ($css as $file) { ?>
    <link href="<?php echo $file; ?>" media="all" rel="stylesheet" />
    <?php } ?>
</head>
<body>
    <?php foreach ($js as $file) { ?>
     <script src="<?php echo $file; ?>"></script>
    <?php } ?>
</head>
</body>
</html>
```