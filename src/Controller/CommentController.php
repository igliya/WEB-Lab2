<?php

namespace App\Controller;

use App\Entity\CommentEntity;
use App\Services\Router;
use App\Validator\CommentValidator;
use Exception;

class CommentController
{
    public function create(array $data)
    {
        $commentValid = CommentValidator::validate($data);
        if (!$commentValid) {
            Router::redirect("/detail-ticket");
            die();
        }
        try {
            $data['user_id'] = $_SESSION['user']['id'];
            $data['ticket_id'] = $_SESSION['detail_ticket'];
            $comment = new CommentEntity($data);
            $comment->save();
            Router::redirect('/detail-ticket');
            die();
        } catch (Exception $e) {
            Router::errorPage(500);
            die();
        }
    }
}
