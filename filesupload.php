<?php



// function validates that submited file is in allowed file-format
function validateFile($file){
    $allowed_formats = array("jpg", "png", "doc", "docx", "pdf");
    $file_parts = pathinfo($file);
    $file_extension = $file_parts['extension'];
    foreach($allowed_formats as $allowed_extension){
        if($file_extension == $allowed_extension) {
            return true;
        }
    }
    return false;
}


// function inserts entries "about" the files into database
// it's inserting only names, hashnames and id of related shipping
function insertFiles($db, $name, $hash, $id) {
    
        $statement = "INSERT INTO files "
                    . "(IDFile, IDShipping, FileName, FilenameHash)"
                    . " VALUES "
                    . "(:IDFile, :IDShipping, :FileName, :FilenameHash);";
        try {
            $statement = $db->prepareAndExec($statement,array(
                'IDFile'        => null,
                'IDShipping'    => $id,
                'FileName'      => $name,
                'FilenameHash'  => $hash,
            ));            
            return $statement->rowCount();
        } catch (\PDOException $e) {                        
            exit($e->getMessage);
        }
    }

    
// function uploads validatet files on server to "attachments/" directory
// directory shoud be specified in some external file but... lack of time... sorry
// filenames of stored files are hashed -> their original names are stored in database
function uploadFiles($id, $db){           
        
        $i = 0 ;
        while(!empty($_FILES['documents']['name'][$i])){
            if(validateFile($_FILES['documents']['name'][$i]))
            {
                $file_parts = pathinfo($_FILES['documents']['name'][$i]);
                $file_extension = $file_parts['extension'];
                if(move_uploaded_file($_FILES['documents']['tmp_name'][$i], 'attachments/'.(md5($_FILES['documents']['tmp_name'][$i]).".".$file_extension))){
                    insertFiles($db,basename($_FILES['documents']['name'][$i]),md5($_FILES['documents']['tmp_name'][$i]),$id);
                }
            } 
            $i++;
        }
}
 
