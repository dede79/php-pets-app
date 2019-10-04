<?php
function get_connection() {
    //create pdo connection using unix socket for mamp
    $config = require 'config.php';

    $pdo = new PDO(
        $config['database_dsn'],
        $config['database_user'],
        $config['database_pass']
        );
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    //query db using query function from the pdo class
}

function get_pets($limit = 0)
//0 will equal false in getpets function so it wont limit pets, can also use limit = null which is also false
{
    $pdo = get_connection();
    
    
    $query = 'SELECT * FROM pet';
    if ($limit) {
        $query = $query .' LIMIT :resultLimit';
    }

     //var_dump($query);die;

    //prepared statement
    $stmt = $pdo->prepare($query);
    $stmt->bindParam('resultLimit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $pets = $stmt->fetchAll();

    return $pets;

    //old json function to retrieve.store in pets.json
    // $petsJson = file_get_contents('data/pets.json');
    // $pets = json_decode($petsJson, true);
}


//function to get one pet only for show page
function get_pet($id){

    $pdo = get_connection();
    //symbol, wathever idval is passed through http
    $query = 'SELECT * FROM pet WHERE id = :idVal';
    //var_dump($query);die;
    $stmt = $pdo->prepare($query);
    $stmt->bindParam('idVal', $id);
    $stmt->execute();
    $pet = $stmt->fetch();

    return $pet;
    
    // fetch() only returns ONE result, fetchALL() return everything
}

function save_pets($petsToSave)
{
    $json = json_encode($petsToSave, JSON_PRETTY_PRINT);
    file_put_contents('data/pets.json', $json);
}

