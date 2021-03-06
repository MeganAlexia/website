<?php session_start() ?>
<!doctype html>
<html>
    
    <head>
        <?php include 'head.php' ?>
        <title>Children </title>
    </head>
        
    <body>
        
        <?php 
        include 'nav.php';
        echo "<a href=\"./adultShowContents.php\">Gestion de contenu</a>";
        ?>
        
        <h1>Enfants</h1>
            
        <?php    
        $root = './';
        include "includes.php";

        $contentDao = new ContentDAO(DynamoDbClientBuilder::get());

        $email = $_SESSION['email'];
        $childDao = new ChildDAO(DynamoDbClientBuilder::get());
        $children = $childDao->getChildren($email);

        foreach ($children as $child) {
            echo $child->getFirstname(). ' ' .$child->getLastName(). ' ' ."<a href = /calendar/calendar.php?email=".$child->getEmail().">calendrier</a></br>";
        }
        ?>
        
        <?php include 'footer.php' ?>
        
    </body>
</html>