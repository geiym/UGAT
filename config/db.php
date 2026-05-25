<?php
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: 3306;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $_pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database connection error.']));
}

// Compatibility wrapper so all your $conn->query() calls still work
class MysqliCompat {
    private $pdo;
    public $insert_id;
    public $affected_rows;
    
    public function __construct($pdo) { $this->pdo = $pdo; }
    
    public function query($sql) {
        try {
            $stmt = $this->pdo->query($sql);
            $this->affected_rows = $stmt->rowCount();
            return new ResultCompat($stmt);
        } catch (PDOException $e) {
            error_log('Query error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function prepare($sql) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return new StmtCompat($stmt, $this);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function real_escape_string($str) {
        return addslashes($str);
    }
    
    public function set_charset($charset) { return true; }
    
    public function begin_transaction() { $this->pdo->beginTransaction(); }
    public function commit() { $this->pdo->commit(); }
    public function rollback() { $this->pdo->rollBack(); }
}

class StmtCompat {
    private $stmt;
    private $conn;
    public $affected_rows = 0;
    
    public function __construct($stmt, $conn) {
        $this->stmt = $stmt;
        $this->conn = $conn;
    }
    
    public function bind_param($types, &...$vars) {
        $i = 1;
        foreach ($vars as &$var) {
            $this->stmt->bindParam($i++, $var);
        }
        return true;
    }
    
    public function execute() {
        try {
            $result = $this->stmt->execute();
            $this->affected_rows = $this->stmt->rowCount();
            $this->conn->insert_id = null;
            return $result;
        } catch (PDOException $e) {
            error_log('Execute error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function get_result() {
        return new ResultCompat($this->stmt);
    }
    
    public function close() { return true; }
}

class ResultCompat {
    private $stmt;
    private $rows;
    private $index = 0;
    public $num_rows;
    
    public function __construct($stmt) {
        $this->stmt = $stmt;
        $this->rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->num_rows = count($this->rows);
    }
    
    public function fetch_assoc() {
        return $this->rows[$this->index++] ?? null;
    }
    
    public function fetch_all($mode = MYSQLI_ASSOC) {
        return $this->rows;
    }
}

$conn = new MysqliCompat($_pdo);
?>