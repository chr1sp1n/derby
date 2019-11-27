
DERBY - Drupal toolkit
v1.0 - 2019-11-26


Initialization:
- execute ```composer install```
- import ```init/derby.init-db.sql``` in your db engine
- edit ```.env``` file with your database references

To start a new theme:
- change directory to ```development/themes```
- execute: ```init-theme.bat``` with new theme name as parameter. New theme name must be composed by lowercased words separated by underscore.

To start a new module:
- change directory to ```development/themes```
- execute: ```init-module.bat``` with new theme name as parameter. New theme name must be composed by lowercased words separated by underscore.


Modules and themes are equipped with Drussets utility tool. Below enabled commands:
- ```npm run dev``` (build theme in development mode)
- ```npm run dist``` (build theme in distribution mode)
- ```npm run watch``` (build theme every time a file or folder has been changed)


vendor/bin/drush php-eval 'echo \Drupal\Component\Utility\Crypt::randomBytesBase64(55)' > sites/default/salt.txt
vendor/bin/drush config-set system.file path.temporary ../tmp -y
