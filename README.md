
### DERBY - Drupal toolkit
v1.0.1 - 2019-11-28

Initialization:
- clone repository ```git clone https://github.com/chr1sp1n/derby.git```
- execute ```composer install```
- import ```init/derby.init-db.sql``` in your db engine
- edit ```.env``` file with your database references

___

To create a new theme:
- execute: ```composer derby-generate-theme {theme-name}``` with theme name as parameter. New theme will be generated in ```development/themes/``` folder.
- change directory to: ```development/themes/{theme-machine-name}```
- execute: ```npm install```

To create a new module:
- execute: ```composer derby-generate-module {theme-name}``` with module name as parameter. New module will be generated in ```development/themes/``` folder.
- change directory to: ```development/modules/{theme-machine-name}```
- execute: ```npm install``

To push new theme or module on Drupal webroot use Drussets utility with following commands:
- ```npm run dev``` (build theme or module in development mode)
- ```npm run dist``` (build theme or module in distribution mode)
- ```npm run watch``` (build theme or module every changes to files or folders)



TO-TO:
- Set files and folder permission in unix based os;
