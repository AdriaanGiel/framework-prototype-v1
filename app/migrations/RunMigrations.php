<?php

require_once "migrations.php";

try{
    $db = $GLOBALS["BASE_DB_CONNECTION"];

    $runner = new \Homework\migrations\MigrationRunner();

    $runner->run($db,$migrations->all());


    // Get Album Data
    $albumRawData = file_get_contents(__DIR__. "/albums.json");



    // Create new Collection
    $albumCollection = new \Homework\core\helpers\Collection(json_decode($albumRawData, true));

    // Create Album objects
    $albumsObjects = $albumCollection->map(function ($album) {

        $artist = \Homework\models\Artist::create([
           "name" => $album['artist']
        ]);

        $genre = \Homework\models\Genre::create([
            "name" => $album['genre']
        ]);

        $album['artist_id'] = $artist->id;
        $album['genre_id'] = $genre->id;


        return \Homework\models\Album::create($album);
    });



}catch(PDOException $e)
{
    echo "Error: " . $e->getMessage() . "<br>";
}


