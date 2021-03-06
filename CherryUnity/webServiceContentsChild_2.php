<?php
session_start();

$root = "./";
include 'includes.php'; 


//print_r($_SESSION);
$childDao = new ChildDAO(DynamoDbClientBuilder::get());
$email = $_SESSION['email'];
$child = $childDao->get($email);
//print_r($child);
$reponse = getContentsChild($child);
header('Content-Type: application/json');
$response = json_encode($reponse);

echo $reponse;

function getContentsChild($child)
{
    $contentsFamilial = $child->getContentByType("family");
    $contentsPedagogique = $child->getContentByType("teacher");
    $contentsMedical = $child->getContentByType("doctor");

    $reponse = array();
    if(count($contentsMedical)>0)
    {
        //$reponse[]= 'medical_';
        foreach ($contentsMedical as $contentM)
        {
            //print_r($contentM);
            $owner = $contentM['M']['owner']['S'];
            $name = $contentM['M']['name']['S'];
            $contentDao = new ContentDAO(DynamoDbClientBuilder::get());
            $contents = $contentDao->getContentsOfUser($owner);
            foreach ($contents as $c)
            {
                $name2 = $c->getName();
               //echo $name2.' =? '.$name;
                if($name2 == $name)
                {
                    $reponse[] = $name;
                    $reponse[]=$c->getUrl();
                }
                //echo $c->getUrl();
                //echo 'temp  '.$reponse;
            }
            //print_r($contents);
        }
    }
    if(count($contentsFamilial)>0)
    {
        //$reponse[]= 'family_';
        foreach ($contentsFamilial as $contentF)
        {
            $owner = $contentF['M']['owner']['S'];
            $name = $contentF['M']['name']['S'];
            $contentDao = new ContentDAO(DynamoDbClientBuilder::get());
            $contents = $contentDao->getContentsOfUser($owner);
            foreach ($contents as $c)
            {
                $name2 = $c->getName();
               //echo $name2.' =? '.$name;
                if($name2 == $name)
                {
                    $reponse[] = $name;
                    $reponse[]=$c->getUrl();
                }
                //echo $c->getUrl();
                //echo 'temp  '.$reponse;
            }
        }
    }
    if(count($contentsPedagogique)>0)
    {
        //$reponse[]= 'teaching_';
        foreach ($contentsPedagogique as $contentP)
       {
            $owner = $contentP['M']['owner']['S'];
            $name = $contentP['M']['name']['S'];
            $contentDao = new ContentDAO(DynamoDbClientBuilder::get());
            $contents = $contentDao->getContentsOfUser($owner);
            foreach ($contents as $c)
            {
                $name2 = $c->getName();
               //echo $name2.' =? '.$name;
                if($name2 == $name)
                {
                    $reponse[] = $name;
                    $reponse[]=$c->getUrl();
                }
                //echo $c->getUrl();
                //echo 'temp  '.$reponse;
            }
        }
    }
    
    print_r($reponse);
}

/*
function getNewContentAvailable($child){
    $newContents = array();
    $teaching = $child->getTeachingContent();
    $medical = $child->getMedicalContent();
    $family = $child->getfamilyContent();

    if (isNewContent($teaching))
        {
            $newContents[]= "teaching";
            foreach($child->getTeachingContent() as $element)
            {
                foreach ($element as $elem2)
                {
                    $newContents[]= $elem2["name"];
                }
            }
        } 
        //$newContents[]= "teaching";

    if (isNewContent($medical))
        {
            $newContents[]= "medical";
            foreach($child->getMedicalContent() as $element)
            {
                foreach ($element as $elem2)
                {
                    $newContents[]= $elem2["name"];
                }
            }
        }   
        //$newContents[]= "medical";


    if (isNewContent($family))
        {
            $newContents[]= "family";
            foreach($child->getfamilyContent() as $element)
            {
                foreach ($element as $elem2)
                {
                    $newContents[]= $elem2["name"];
                }
            }
        } 
        //$newContents[]= "family";
    
    return $newContents;
}

function isNewContent($contents){
    foreach($contents as $content ){
        if($content['M']['notified']['N'] == 0)
            return true;         
    }
    return false;
}

echo 'bou';
/*
$childDao = new ChildDAO(LocalDBClientBuilder::get());
if(!empty($_GET['email']))
$email = $_GET['email'];
$child = $childDao->get($email);
$contents = getNewContentAvailable($child);
header('Content-Type: application/json');
$response = json_encode($contents);
echo $response;




/*session_start();

$root = "./";
include 'includes.php'; 


function getNewContentAvailable($child){
    $newContents = array();
    $teaching = $child->getTeachingContent();
    $medical = $child->getMedicalContent();
    $family = $child->getfamilyContent();

    if (isNewContent($teaching))
    {
        $type= "teaching_";
        //$contents = $child->getTeachingContent();
    }
    if (isNewContent($medical))
    {
        $type= "medical_";
        //$contents = $child->getMedicalContent();
    }
    if (isNewContent($family))
    {
        $type= "family_";
        //$contents = $child->getFamilyContent();   
    }
    
    $length = count($contents);
    
    for ($i = 0; $i < $length; $i++) {
        $contentInfo = $contents[$i];
        
        $name = $contentInfo['M']['name']['S'];
        $allName += $name + "&"; 
    }
    
    //$newContents[] = $type + $allName;
    
    return $newContents;
}

function isNewContent($contents){
    foreach($contents as $content ){
        if($content['M']['notified']['N'] == 0)
            return true;         
    }
    return false;
}
 
$childDao = new ChildDAO(LocalDBClientBuilder::get());//DynamoDbClientBuilder::get());
if(!empty($_GET['email']))
$email = $_GET['email'];
$child = $childDao->get($email);
$contents = getNewContentAvailable($child);
header('Content-Type: application/json');
$response = json_encode($contents);
echo $response;
*/
?>


