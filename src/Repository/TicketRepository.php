<?php

namespace App\Repository;

use App\Entity\TicketEntity;
use App\Helper\DbConnection;
use App\Services\Router;
use Exception;

class TicketRepository extends BaseRepository
{
    protected string $tableName;

    protected DbConnection $db;

    public function __construct()
    {
        $this->tableName = 'ticket';
        try {
            $this->db = new DbConnection();
        } catch (Exception $e) {
            Router::errorPage(500);
        }
    }

    public function findOneBy(array $params = [], array $order = [], bool $andOr = true): TicketEntity
    {
        $delimiter = $andOr ? "AND" : "OR";
        $sql = "SELECT * FROM $this->tableName";
        if (count($params) != 0) {
            $sql .= " WHERE ";
            foreach ($params as $paramKey => $paramValue) {
                if (array_key_last($params) == $paramKey) {
                    $sql .= " $paramKey=:$paramKey";
                } else {
                    $sql .= " $paramKey=:$paramKey $delimiter ";
                }
            }
        }
        if (count($order) != 0) {
            $sql .= " ORDER BY ";
            foreach ($order as $paramValue) {
                if (end($order) == $paramValue) {
                    $sql .= " $paramValue";
                } else {
                    $sql .= " $paramValue, ";
                }
            }
        }
        $data = (array)$this->db->execGetDataArray($sql, $params)[0];
        if (count($data) != 0) {
            return new TicketEntity($data);
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
                    $sql .= " $paramKey=:$paramKey AND ";
                } else {
                    $sql .= " $paramKey=:$paramKey $delimiter ";
                }
            }
            $sql .= " is_closed=false";
        } else {
            $sql .= " WHERE is_closed=false";
        }
        if (count($order) != 0) {
            $sql .= " ORDER BY ";
            foreach ($order as $paramValue) {
                if (end($order) == $paramValue) {
                    $sql .= " $paramValue";
                } else {
                    $sql .= " $paramValue DESC, ";
                }
            }
        }
        $sql .= ' DESC';
        $data = $this->db->execGetDataArray($sql, $params);

        if (count($data) != 0) {
            $result = array();
            foreach ($data as $ticketRaw) {
                $result[] = new TicketEntity($ticketRaw);
            }
            return $result;
        }
        throw new Exception("Ничего не найдено!");
    }

    public function getDetailInformation(): array
    {
        $sql = "select (select count(*) from ticket) as total_ticket_cnt,
                (select count(*) from ticket where is_closed = false) as open_ticket_cnt,
                (select count(*) from ticket where is_closed = true) closed_ticket_cnt
                from ticket;";
        return $this->db->execGetDataArray($sql)[0];
    }
}
