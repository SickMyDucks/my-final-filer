<?php
require_once 'Cool/DBManager.php';

class FilesManager
{
    public function upload($file, $filename)
    {
        if (isset($_SESSION['username'])) {
            $targetDir = "uploads/" . $_SESSION['username'] . "/";
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
                $logs['success'] = "The file " . basename($filename) . " has been uploaded.";

            } else {
                $logs['error'] = "Sorry, there was an error uploading your file.";
            }
        }
        return $logs;
    }

    public function scandir($path)
    {
        $logs = [];
        $folderContent = array_diff(scandir("uploads/" . $path), array('.'));
        if (count($folderContent) == 0) {
            $logs['error'] = 'This folder does not exist.';
        }
        if (count($folderContent) == 1) {
            $logs['error'] = 'No file in this folder.';
        }
        for ($i = 1; $i <= count($folderContent); $i++) {
            $files[$i]['type'] = is_dir("uploads/" . $path . "/" . $folderContent[$i]) ? 'folder' : 'file';
            $files[$i]['name'] = $folderContent[$i];
            $files[$i]['modified_last'] = date("d/n/Y - H:i:s", filemtime("uploads/" . $path . "/" . $folderContent[$i]));
        }

        return [$files, $logs];
    }

    public function parentFolder($folder)
    {
        $lastDirRegexp = "/\/([a-zA-Z]+(\.)?[a-zA-Z]+(\/)?)$/";
        $lowerLevel = preg_replace($lastDirRegexp, '', $folder);
        return $lowerLevel;
    }

    public function download($file)
    {
        session_start();
        $file = "uploads/" . $_SESSION['username'] . $file;
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    public function delete($file, $dir)
    {
        session_start();
        $file = "uploads/" . $_SESSION['username'] . $file;
        $file = str_replace('..', '', $file);
        unlink($file);
        header('Location: ?action=files&dir=' . $dir);
    }

    public static function delTree($dir)
    {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        rmdir($dir);
    }

    public function foldersOnly($scannedDir)
    {
        $folders = [];
        for ($i = 1; $i <= count($scannedDir); $i++)
        {
            if ($scannedDir[$i]['type'] == 'folder')
            {
                $folders[] = $scannedDir[$i]['name'];
            }
        }
        return $folders;
    }

    public function move($source, $to)
    {
        rename($source, $to);  
    }
}
