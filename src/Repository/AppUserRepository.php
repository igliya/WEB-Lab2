<?php

namespace App\Repository;

use App\Entity\AppUserEntity;
use App\Helper\DbConnection;
use App\Services\Router;
use Exception;

class AppUserRepository extends BaseRepository
{
    protected string $tableName;
    protected DbConnection $db;

    public function __construct()
    {
        $this->tableName = 'app_user';
        try {
            $this->db = new DbConnection();
        } catch (Exception $e) {
            Router::errorPage(500);
        }
    }

    public function findOneBy(array $params = [], array $order = [], bool $andOr = true): AppUserEntity
    {
        $delimiter = $andOr ? "AND" : "OR";
        $sql = "SELECT * FROM $this->tableName";
        if (count($params) != 0) {
            $sql .= " WHERE ";
            foreach ($params as $paramKey => $paramValue) {
                if (array_key_last($params) == $paramKey) {
                    $sql .= " $paramKey=:$paramKey";
                } else {
                    $sql .= " $paramKey=:$paramKey $delimiter";
                }
            }
        }
        if (count($order) != 0) {
            $sql .= " ORDER BY ";
            foreach ($order as $paramKey => $paramValue) {
                if (array_key_last($params) == $paramKey) {
                    $sql .= " $paramKey=:$paramKey";
                } else {
                    $sql .= " $paramKey=:$paramKey ";
                }
            }
        }
        $data = (array)$this->db->execGetDataArray($sql, $params)[0];
        if (count($data) != 0) {
            return new AppUserEntity($data);
        }
        throw new Exception("Ничего не найдено!");
    }

    public function findBy(array $params = [], array $order = [], bool $andOr = true): array
    {
        $delimiter = $andOr ? "AND" : "OR";
        $sql = "SELECT * FROM $this->tableName";
        if (count($params) != 0) {
            $sql .= " WHERE ";
            foreach ($params as $paramKey => $paramValue) {
                if (array_key_last($params) == $paramKey) {
                    $sql .= " $paramKey=:$paramKey";
                } else {
                    $sql .= " $paramKey=:$paramKey $delimiter";
                }
            }
        }
        if (count($order) != 0) {
            $sql .= " ORDER BY ";
            foreach ($order as $paramKey => $paramValue) {
                if (array_key_last($params) == $paramKey) {
                    $sql .= " $paramKey=:$paramKey";
                } else {
                    $sql .= " $paramKey=:$paramKey ";
                }
            }
        }
        $data = (array)$this->db->execGetDataArray($sql, $params);
        if (count($data) != 0) {
            $result = array();
            foreach ($data as $userRaw) {
                $result[] = new AppUserEntity($userRaw);
            }
            return $result;
        }
        throw new Exception("Ничего не найдено!");
    }

    public function getUser(string $email, string $password): AppUserEntity
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE email=:email";
        $data = $this->db->execGetDataArray($sql, ["email" => $email])[0];
        if (password_verify($password, $data['password'])) {
            return new AppUserEntity($data);
        }
        throw new Exception("Пользователь не найден!");
    }
}
