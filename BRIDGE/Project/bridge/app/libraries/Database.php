<?php
# DESCRIPTION
/**
 * Connect with database
 */

class Database
{
    private $host = HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private PDO $db_handler;
    private PDOStatement $statement;
    private $error;

    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::FETCH_ASSOC => PDO::FETCH_OBJ
        );

        // Create PDO instance
        try {
            $this->db_handler = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die($this->error);
        }
    }

    // Prepare statement with query
    public function query($sql)
    {
        $this->statement = $this->db_handler->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                case empty($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        return $this->statement->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute()
    {
        return $this->statement->execute();
    }

    // Get result set as array of objects
    public function allResults()
    {
        $this->execute();
        return $this->statement->fetchAll();
    }

    // Get single record as object
    public function singleResult()
    {
        $this->execute();
        return $this->statement->fetch();
    }

    // Get row count
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
}
