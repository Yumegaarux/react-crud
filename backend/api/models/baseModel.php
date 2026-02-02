<?php
    namespace myProject\backend\baseModel;

    use myProject\backend\database\Database;
    use PDO;
    
    abstract class baseModel{
        protected $db;
        protected $table;
        protected $primaryKey = 'id';

        public function __construct()
        {
            $this->db = Database::getInstance();
        }

        public function find($id) {
            $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
            return $this->db->fetch($query, ['id' => $id]);
        }

        public function findAll(array $conditions = [], string $orderBy = '', int $limit = 0, int $offset = 0): array
        {
            $query = "SELECT * FROM {$this->table}";
            $params = [];

            if (!empty($conditions)) {
                $whereClause = [];
                foreach ($conditions as $field => $value) {
                    $whereClause[] = "{$field} = :{$field}";
                    $params[$field] = $value;
                }
                $query .= " WHERE " . implode(' AND ', $whereClause);
            }

            if (!empty($orderBy)) {
                $query .= " ORDER BY {$orderBy}";
            }

            if ($limit > 0) {
                $query .= " LIMIT {$limit}";
                if ($offset > 0) {
                    $query .= " OFFSET {$offset}";
                }
            }

            return $this->db->fetchAll($query, $params);
        }

        public function create(array $data): string
        {
            // array_keys ignores data value, instead focuses on KEYS
            $fields = array_keys($data);

            // array_map loops an entire array and returns a whole new array. (In this case, it's '$placeholders').
            // array_map uses 2 params, 1 is the function, 2 is the array to loop through.
            // this function just adds a ':' before the field. 
            // $field represents each element of $fields and is transformed into a new value.
            $placeholders = array_map(function ($field) { return ":{$field}";}, $fields);

            // implode transforms all items in a array into a single string.
            // while also using the ', ' as a separator between items.
            $query = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";

            return $this->db->insert($query, $data);
        }          
    }
?>