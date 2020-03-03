<?php

class gamesessions {
  
    public function getgamesessions(string $postsdirectory): array {

        $postsdirwebroot = "posts";

        $filenames = scandir($postsdirectory);

        $posts = [];
        $postsorder = [];
        $postsbyuuid = [];

        foreach ($filenames as $filename) {
            if ($filename == "." || $filename == "..") continue;
            $parts = explode ("_", $filename);
            $uuid = $parts [1];
            $postdate = $parts [0];
            $filekind = explode (".", $parts [2]) [0];


            if ( ! array_key_exists ($uuid, $postsbyuuid) ) {
                array_push ($postsorder, $uuid);
                $postsbyuuid [$uuid] = [
                    "uuid" => $uuid,
                    "notes" => "",
                    "image" => ""
                ];
            }


            if ( $filekind ==  "notes") {
                $postsbyuuid [$uuid] ["notes"] = file_get_contents("$postsdirectory/$filename");
            } elseif ( $filekind == "image") {

                $postsbyuuid [$uuid] ["image"] = "$postsdirwebroot/$filename";

            }
        }

        foreach ($postsorder as $uuid) {
            array_push($posts, $postsbyuuid [$uuid]);
        }

        return $posts;

    }

  
  
  
  	public function addgamesession(): string {

        $postsPath = "posts";
        $uuid = uniqid ();
        $postdate = date ( "Ymd" );
        $uploadPrefix = "$postdate"."_"."$uuid";

        $error = "";


        if ( $_SERVER['REQUEST_METHOD'] != "POST" ) {

            $error = "Error: not a form post.";

        } else {

            if ( isset ( $_FILES ) && array_key_exists ( "file", $_FILES ) ) {

                $extension = pathinfo ( $_FILES["file"]["name"], PATHINFO_EXTENSION );

                $storedImage = "$postsPath/$uploadPrefix"."_image.$extension";
                
                $tmpName = $_FILES["file"]["tmp_name"];

                if ( ! move_uploaded_file( $tmpName, $storedImage ) ) {
print_r($_FILES);
                   // $error = "Whoops! There was an error uploading your file.";

                }

            }


            if ( array_key_exists ( "text", $_POST ) && $_POST["text"] != "" ) {

                $storedText = "$postsPath/$uploadPrefix"."_notes.txt";

                file_put_contents ( $storedText, $_POST["text"] );

            }

        }

        if ( $error != "" ) {

        ?>

            <script>

                alert ( "<?=$error?>" );

                location.back ();

            </script>

        <?php

            die ();

        } else {

            header ( "Location: ./", true, 303 );

            return $error;

            die ();

        }
    
    }

}  
  
 ?>