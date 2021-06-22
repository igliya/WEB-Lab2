<?php

namespace App\Controller;

use App\Entity\TicketEntity;
use App\Repository\TicketRepository;
use App\Services\Router;
use App\Services\View;
use App\Validator\FileValidator;
use App\Validator\TicketValidator;
use Exception;
use Ramsey\Uuid\Uuid;

class TicketController
{
    public function create(array $data, array $files)
    {
        $fileData = self::reArrayFiles($files['ticket_file']);
        $ticketValid = TicketValidator::validate($data);
        $filesValid = FileValidator::validate($fileData);
        if (!$ticketValid) {
            $_SESSION['errors'] = TicketValidator::getErrors();
            $_SESSION['user_data'] = $data;
            Router::redirect("/create-ticket");
            die();
        }
        unset($_SESSION['errors']);
        unset($_SESSION['user_data']);
        if (!$filesValid) {
            $_SESSION['errors'] = FileValidator::getErrors();
            $_SESSION['user_data'] = $data;
            Router::redirect("/create-ticket");
            die();
        }
        unset($_SESSION['errors']);
        unset($_SESSION['user_data']);
        try {
            $data['user_id'] = $_SESSION['user']['id'];
            if (count($fileData) != 0) {
                $newFileName = Uuid::uuid4() . "." . $fileData[0]['extension'];
                $filPath = dirname(__DIR__, 2) . '/uploads/' . $newFileName;
                if (!move_uploaded_file($fileData[0]['tmp_name'], $filPath)) {
                    Router::errorPage(500);
                }
                $data['file'] = $newFileName;
            }
            $ticket = new TicketEntity($data);
            $ticket->save();
            $_SESSION['detail_ticket'] = $ticket->ticketId;
            Router::redirect('/detail-ticket');
            die();
        } catch (Exception $e) {
            Router::errorPage(500);
            die();
        }
    }

    /**
     * Метод для конвертации списка файлов в удобный формат
     * @param array $files (исходный список файлов)
     * @return array (полученный список файлов)
     */
    private function reArrayFiles(array $files): array
    {
        if ($files['size'][0] == 0) {
            return [];
        }
        $reFiles = array();
        $fileCount = 1;
        $fileKeys = array_keys($files);
        for ($i = 0; $i < $fileCount; $i++) {
            foreach ($fileKeys as $key) {
                $reFiles[$i][$key] = $files[$key][$i];
            }
            $fileNameSplit = explode('.', $files['name'][$i]);
            if (count($fileNameSplit) == 0) {
                $reFiles[$i]['extension'] = "other";
            } else {
                $reFiles[$i]['extension'] = end($fileNameSplit);
            }
        }
        return $reFiles;
    }

    public function open(string $param)
    {
        if (empty($param)) {
            Router::redirect('/home');
        }
        View::checkIfNotLogin('/login');
        $_SESSION['detail_ticket'] = $param;
        Router::redirect('/detail-ticket');
        die();
    }

    public function edit(string $param)
    {
        if (empty($param) || $_SESSION['user']['role'] != 'EDITOR') {
            Router::redirect('/detail-ticket');
        }
        View::checkIfNotLogin('/login');
        $_SESSION['detail_ticket'] = $param;
        Router::redirect('/edit-ticket');
        die();
    }

    public function update(array $data)
    {
        try {
            if (!isset($data['is_closed'])) {
                $data['is_closed'] = 'false';
            } else {
                $data['is_closed'] = 'true';
            }
            $data['ticket_id'] = $_SESSION['detail_ticket'];
            $ticketRepos = new TicketRepository();
            $ticket = $ticketRepos->findOneBy(['ticket_id' => $data['ticket_id']]);

            $ticket->supportId = $data['support_id'];
            $ticket->isClosed = $data['is_closed'];
            $ticket->status = $data['status'];
            $ticket->save();

            Router::redirect('/detail-ticket');
            die();
        } catch (Exception $e) {
            Router::errorPage(500);
            die();
        }
    }
}
