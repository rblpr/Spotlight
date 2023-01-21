<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title><?php echo $templateParams["title"] ?></title>
</head>

<body theme="light" class="container">
    <style>
        .artist-image {
            width: 4rem;
            border-radius: 50%;
        }
    </style>
    <header class="py-2 d-flex">
        <div class="mx-2">
            <img class="album-image" src=<?php echo $templateParams["albumImage"] ?> alt="">
        </div>
        <div>
        <h1><?php echo $templateParams["albumName"]?></h1>
        </div>
    </header>
</body>