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
                file_put_contents('logs/access.log', '[' . date("Y-m-d H:i:s") . '] : '. $_SESSION['username'] . ' uploaded the file ' . $filename. "\n", FILE_APPEND);
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
            $files[$i]['extension'] = pathinfo($folderContent[$i], PATHINFO_EXTENSION);
            $files[$i]['modified_last'] = date("d/n/Y - H:i:s", filemtime("uploads/" . $path . "/" . $folderContent[$i]));
        }

        return [$files, $logs];
    }

    public function parentFolder($folder)
    {
        $lowerLevel = explode('/',$folder);
        $lowerLevel = array_filter($lowerLevel);
        array_pop($lowerLevel);
        $lowerLevel = '/'.implode('/',$lowerLevel).'/';
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
        file_put_contents('logs/access.log', '[' . date("Y-m-d H:i:s") . '] : '. $_SESSION['username'] . ' downloaded the file ' . $file. "\n", FILE_APPEND);
        exit;
    }

    public function delete($file, $dir)
    {
        session_start();
        $file = "uploads/" . $_SESSION['username'] . $file;
        $file = str_replace('..', '', $file, $count);
        if ($count > 0)
        {
            file_put_contents('logs/security.log', '[' . date("Y-m-d H:i:s") . '] : '. $_SESSION['username'] . ' tried to delete a file from a parent folder' . "\n", FILE_APPEND);
        }
        unlink($file);
        file_put_contents('logs/access.log', '[' . date("Y-m-d H:i:s") . '] : '. $_SESSION['username'] . ' deleted the file ' . $file . "\n", FILE_APPEND);
        header('Location: ?action=files&dir=' . $dir);
    }

    public static function delTree($dir)
    {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        file_put_contents('logs/access.log', '[' . date("Y-m-d H:i:s") . '] : '. $_SESSION['username'] . ' deleted ' . $dir . ' directory'. "\n", FILE_APPEND);
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
        $source = str_replace(' ', '_', $source);
        $to = str_replace(' ', '_', $to);
        if (rename($source, $to))
        {
            file_put_contents('logs/access.log', '[' . date("Y-m-d H:i:s") . '] : '. $_SESSION['username'] . ' moved or renamed the file ' . $source . ' to ' . $to. "\n", FILE_APPEND);
        }  
    }

    public function readFile($filepath)
    {
        return file_get_contents($filepath);
    }

    public function editFile($filepath, $content)
    {
        file_put_contents('logs/access.log', '[' . date("Y-m-d H:i:s") . '] : '. $_SESSION['username'] . ' edited the file ' . $filepath. "\n", FILE_APPEND);
        return file_put_contents($filepath, $content);
    }
}
