<?php
require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nordea YNAB convert</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<style>
    #upzone {
        border: 4px solid lightgray;
        border-radius: 10px;
        height: 30vh;
        display: flex;
        align-items: center;
        padding-left: 45%;
    }

    #upzone.highlight {
        border: 6px dashed lightgray;
    }

    /* (B) UPLOAD FORM */
    #upform {
        display: none;
    }
</style>
<body>
<section class="section">
    <div class="container">
        <h1 class="title">
            Nordea YNAB convert
        </h1>
    </div>
</section>
<section class="section">
    <div class="container">
        <div id="upzone" class="has-text-centered">
            Drop file here
        </div>
        <!-- (C) UPLOAD STATUS -->
        <div id="upstat"></div>
    </div>
    <!-- (D) FALLBACK -->
    <form id="upform" action="dd-upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="upfile" accept="text/*" required>
        <input type="submit" value="Upload File">
    </form>
</section>
<script rel="script" src="dd-upload.js"></script>
</body>
</html>
