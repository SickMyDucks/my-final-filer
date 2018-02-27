<?php
require_once 'Cool/DBManager.php';

class FilesManager
{
    public function upload($file, $filename)
    {
        session_start();

        if (isset($_SESSION['username'])) {
            $targetDir = "uploads/".$_SESSION['username']."/";
            $targetFile = $targetDir . $filename;
        }
        $uploadOk = 1;
        if (isset($targetFile)) {
            if (file_exists($targetFile) && substr($targetFile, -1) != "/") {
            $logs['error'] = "Sorry, file already exists.";
            $uploadOk = 0;
            } 
        }
        if ($uploadOk === 1) {
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            $logs['success'] = "The file ". basename( $file["name"]). " has been uploaded.";
            
            } else {
                $logs['error'] = "Sorry, there was an error uploading your file.";
            }
        }
        return $logs;
    }

    public function scandir($username)
    {
        $folderContent = array_diff(scandir("uploads/".$_SESSION['username']), array('.'));
        if (count($folderContent) == 1)
        {
            $logs['error'] = 'No file in this folder.';
        }
        for ($i = 1; $i <= count($folderContent); $i++)
        {
            $files[$i]['type'] = is_dir("uploads/" .$_SESSION['username'] . "/" . $folderContent[$i]) ? 'folder' : 'file' ;
            $files[$i]['name'] = $folderContent[$i];
            $files[$i]['modified_last'] =  date("d/n/Y - H:i:s", filemtime("uploads/" .$_SESSION['username'] . "/" . $folderContent[$i]));
        }

        return [$files, $logs];
    }
}