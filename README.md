# PHP Server for widget-o

## 1. Installation
Add dependency to composer.json:
```json
"widget-o/widget-o-php": "dev-master"
```

After installing dependencies in the project prepare index.php like:
```php
<?php
require 'Widgeto/Widgeto.php';
$widgeto = new \Widgeto\Widgeto();
$widgeto->run();
```

Create a database configuration file under config/database.json
```json
{
    "driver": "mysql",
    "host": "localhost",
    "username": "username",
    "password": "*******",
    "database": "database"
}
```
