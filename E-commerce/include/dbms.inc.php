 <?php

    // database connection

    // development architecture
    $config['localhost']['host'] = "localhost";
    $config['localhost']['user'] = "root";
    $config['localhost']['password'] = "";
    $config['localhost']['db'] = "ecommerce";

    // deployment architecture 
    $config['sql.example.com']['host'] = "localhost";
    $config['sql.example.com']['user'] = "root";
    $config['sql.example.com']['password'] = "";
    $config['sql.example.com']['db'] = "ecommerce";

    // connection
    $connessione = new mysqli(
        $config[$_SERVER["SERVER_NAME"]]['host'],
        $config[$_SERVER["SERVER_NAME"]]['user'],
        $config[$_SERVER["SERVER_NAME"]]['password'],
        $config[$_SERVER["SERVER_NAME"]]['db']
    );

    // check connection
    if (!$connessione) {
        die('conection error');
    } else {
        echo "<script> console.log('Connessione riuscita') </script>";
    }

    ?>