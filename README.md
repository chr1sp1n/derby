Before start developing drupal:
- execute ```composer install```
- import ```init/derby.init-db.sql``` in your db engine
- edit ```.env``` file with your database references






vendor/bin/drush php-eval 'echo \Drupal\Component\Utility\Crypt::randomBytesBase64(55)' > sites/default/salt.txt
vendor/bin/drush config-set system.file path.temporary ../tmp -y
