<?php

require_once('Cool/BaseController.php');

class FilesController extends BaseController
{
    public function uploadAction()
    {
        session_start();
        if(!isset($_SESSION))
        {
            header('Location: ?action=login');
        }
        $data = [];
        require_once('Model/FilesManager.php');
        $manager = new FilesManager();
        if (isset($_FILES["file"]["name"]))
        {
            if (isset($_POST['name']) && $_POST['name'] !== '')
            {
                $filename = $_POST['name'];
            } else {
                $filename = $_FILES["file"]["name"];
            }
            $logs = $manager->upload($_FILES['file'], $filename);
            $data = [
                'error' => $logs['error'],
                'success'=> $logs['success']
            ];
        }
        
        return $this->render('upload.html.twig', $data);
    }
}