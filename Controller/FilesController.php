<?php

require_once('Cool/BaseController.php');
require_once('Model/FilesManager.php');

class FilesController extends BaseController
{
    public function uploadAction()
    {
        session_start();
        if(!isset($_SESSION['username']))
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
                $filename = str_replace('/', '', $filename);
                $filename = str_replace(' ', '_', $filename);             
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
            header('Location: ?action=login&dir=/');
            return false;
        }
        $manager = new FilesManager();
        $folder = $_GET['dir'];
        $lowerLevel = $manager->parentFolder($folder);
        $data = $manager->scandir($_SESSION['username'].$folder);
        $folders = $manager->foldersOnly($data[0]);
        $data = [
            'error'      => isset($data[1]['error']) ? $data[1]['error'] : '',
            'directory'  => $data[0],
            'user'       => $_SESSION,
            'currentdir' => $folder,
            'lowerlevel' => $lowerLevel,
            'folders'    => $folders,
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

    public function deleteDirAction()
    {
        $manager = new FilesManager();
        session_start();
        $pattern = '/(\.\..)/';
        $dir = "uploads/" . $_SESSION['username'] . preg_replace($pattern, '', $_GET['dir']);
        $lowerLevel = $manager->parentFolder($_GET['dir']);
        $manager->delTree($dir);
        header("Location: ?action=files&dir=$lowerLevel");
    }

    public function moveItemAction()
    {
        $manager = new FilesManager();
        session_start();
        $basepath = "uploads/" . $_SESSION['username'];
        $from = $_GET['from'];
        $file = $_GET['file'];
        $currentDir = $manager->parentFolder($from);
        $source = $basepath . $from;
        $to = $basepath . $currentDir . '/' . $_GET['to'] . '/' . $file;
        $manager->move($source, $to);
        header('Location: ?action=files&dir=' . $manager->parentFolder($_GET['from']));
    }

    public function renameItemAction() {
        session_start();
        $dir = 'uploads/' . $_SESSION['username'] . $_GET['dir'] . '/';
        $from = str_replace('/', '', $_GET['from']);
        $to = str_replace('/', '', $_GET['to']);
        echo $dir . '<br>' . $from . '<br>' . $to . '<br>' ;
        $manager = new FilesManager();
        $manager->move($dir.$from, $dir.$to);
        header('Location: &dir=' . $_GET['dir']);
    }

    public function makeDirAction() {
        session_start();
        $currentDir = $_GET['dir'];
        mkdir('uploads/' . $_SESSION['username'] . $_GET['dir'] . '/NewFolder');
        header('Location: ?action=files&dir=' . $_GET['dir']);
    }
}