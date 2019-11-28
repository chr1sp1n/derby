
# DERBY - Drupal toolkit
v1.0.2 - 2019-11-28

## Initialization:
- clone repository ```git clone -b 1.0.1 --single-branch https://github.com/chr1sp1n/derby.git```
- import ```init/derby.init-db.sql``` in your db engine
- edit ```.env``` file with your database references
- execute ```composer install```

___

## Create a new theme:
- execute: ```composer derby-generate-theme {theme-name}```. New theme will be generated in ```development/themes/``` folder.
- change directory to: ```development/themes/{theme-machine-name}```
- execute: ```npm install```

## Create a new module:
- execute: ```composer derby-generate-module {module-name}```. New module will be generated in ```development/modules/``` folder.
- change directory to: ```development/modules/{module-machine-name}```
- execute: ```npm install``

### To push new theme or module on Drupal webroot use Drussets utility with following commands:
- ```npm run dev``` (build theme or module in development mode)
- ```npm run dist``` (build theme or module in distribution mode)
- ```npm run watch``` (build theme or module every changes to files or folders)


___

### TO-TO:
- Set files and folder permission in unix based os;
- Let specify theme or module human readable name
