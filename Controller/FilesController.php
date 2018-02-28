<?php

require_once('Cool/BaseController.php');
require_once('Model/FilesManager.php');

class FilesController extends BaseController
{
    public function uploadAction()
    {
        session_start();
        if(!isset($_SESSION))
        {
            header('Location: ?action=login');
        }
        $data = [
            'user'    => $_SESSION,
        ];
        $manager = new FilesManager();
        if (isset($_FILES["file"]["name"]))
        {
            if (isset($_POST['name']) && $_POST['name'] !== '')
            {
                $filename = $_POST['name'];
            } else {
                $filename = $_FILES["file"]["name"];
                $filename = str_replace('/', '', $filename);
            }
            $logs = $manager->upload($_FILES['file'], $filename);
            $data = [
                'error'   => $logs['error'],
                'success' => $logs['success'],
                'user'    => $_SESSION,
            ];
        }
        
        return $this->render('upload.html.twig', $data);
    }

    public function filesAction()
    {
        session_start();
        if(!isset($_SESSION))
        {
            header('Location: ?action=login');
            return false;
        }
        $manager = new FilesManager();
        $folder = $_GET['dir'];
        $lowerLevel = $manager->parentFolder($folder);
        $data = $manager->scandir($_SESSION['username'].$folder);
        $data = [
            'error'      => $data[1]['error'],
            'directory'  => $data[0],
            'user'       => $_SESSION,
            'currentdir' => $folder,
            'lowerlevel' => $lowerLevel,
        ];
        return $this->render('files.html.twig', $data);
    }

    public function downloadAction()
    {
        $file = $_GET['file'];
        $manager = new FilesManager();
        $manager->download($file);
    }

    public function deleteAction()
    {
        $file = $_GET['file'];
        $dir = $_GET['dir'];
        $manager = new FilesManager();
        $manager->delete($file, $dir);
    }
}