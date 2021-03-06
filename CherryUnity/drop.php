<?php 
session_start();
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Drag n drop </title>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        
    <?php
    $root = './';
    include 'includes.php'; 
    ?>
    </head>
    <body>
        
      <?php
      $email = $_SESSION['email'];
      $childDao = new ChildDAO(DynamoDbClientBuilder::get());
      $children = $childDao->getChildren($email);
      ?>
        
      <?php include 'nav.php' ?>
        
        <a href="./adultShowContents.php">Liste des fichiers</a>
        <!-- Bouton choisir un fichier
        <input type="file"   name="file" /> <br />
        -->
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    Faites glisser les documents à ajouter
                    <form enctype="multipart/form-data" id="yourregularuploadformId" method="post" action="handlers/fileHandler.php">
                        <input type="file" name="files[]" multiple="multiple" class="hidden">
                        
                        
                    </form>
                    
                    <div id="drag">
                        <ul> </ul>
                    </div>
                    <div>
                        <br/>
                        <button id="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
                
                <div class="col-md-offset-3 col-md-3">
                    Enfants <br/>
        <?php
        foreach($children as $child){
        echo ' <p> <input type="checkbox" name="child" value="' . $child->getEmail() .  '">'. 
                $child->getFirstname()  . ' Date: <input type="text" class="datepicker"> </p>' ;
        }
        ?>
                </div>
                
            </div>
            
        </div>
        
        
        
        <footer class="footer">
            <div class="container-fluid">
                <img  height="60px" src="img/logo.jpg"/> 
            </div>
        </footer>
        
        
        
        
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>  
        <script src="js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script>
            $( document ).ready(function() {
                $(".datepicker" ).datepicker();
            });    
            var uploadFormData = new FormData($("#yourregularuploadformId")[0]); 
            var dropper = document.querySelector('#drag');

            $("#drag").on('dragover',function(e){
                e.preventDefault();
            })
                    .on('drop',function(e){
                        e.preventDefault();
                var files = e.originalEvent.dataTransfer.files,
                filesLen = files.length,
                filenames = "";
                var file = files[0];
                for(var i = 0 ; i < filesLen ; i++) {
                    filenames += '\n' + files[i].name;
                }

			  
                // alert(uploadFormData);
                //uploadFormData.append("text","hello");  
                for(var f = 0; f < files.length; f++) { 		 
                    uploadFormData.append("files[]",files[f]);
                    $("#drag ul").append("<li>"+files[f].name+"</li>"); 		     
                }

            }); 
            $("#submit").click(function(){
                $("input:checked").each(function() {
                    uploadFormData.append("children[]",[$(this).val(),$(this).next(".datepicker").val()]); 
                    $.ajax({
                        type: 'POST',
                        url: 'handlers/fileHandler.php',
                        processData: false,
                        contentType: false,
                        data: uploadFormData,
                        success: function (exception) {
                            //alert("Success : " + JSON.stringify(exception));
                            location.href ="adultShowContents.php";
                        },
                        error: function (exception) {
                            alert("Exception : " + JSON.stringify(exception));
                        }
                    });

                });			 





            });

            function filesUpdated(){
                $("#drag ul").empty();
                $("#drag ul").after("<p>Les fichiers ont correctement été ajoutés</p>");
            }
					     
					     
        </script>
        
    </body>
</html>





