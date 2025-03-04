<?php
class VisitorCounter {
    private $conn;
    private $current_page;

    public function __construct($current_page = '') {
        $host = 'localhost';
        $dbname = 'planetarium_db';
        $username = 'root';
        $password = '';
        
        $this->current_page = $current_page;

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function trackVisitor() {
        if ($this->current_page === 'home') {
            $ip = $_SERVER['REMOTE_ADDR'];
            $current_date = date('Y-m-d');
    
            try {
                // Check if the visitor already exists for today
                $stmt = $this->conn->prepare("SELECT id FROM counter WHERE ip = :ip AND date = :date AND page = :page");
                $stmt->execute([
                    'ip' => $ip,
                    'date' => $current_date,
                    'page' => $this->current_page
                ]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($existing) {
                    // Update view count if the visitor already exists
                    $stmt = $this->conn->prepare("UPDATE counter SET views = views + 1 WHERE id = :id");
                    $stmt->execute(['id' => $existing['id']]);
                } else {
                    // Insert a new record if no previous visit today
                    $stmt = $this->conn->prepare("
                        INSERT INTO counter (ip, date, views, page)
                        VALUES (:ip, :date, 1, :page)
                    ");
                    $stmt->execute([
                        'ip' => $ip,
                        'date' => $current_date,
                        'page' => $this->current_page
                    ]);
                }
            } catch(PDOException $e) {
                error_log("Visitor tracking error: " . $e->getMessage());
            }
        }
    }
    

    public function getTotalVisits() {
        try {
            $stmt = $this->conn->query("SELECT SUM(views) as total_visits FROM counter");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_visits'] ?? 0;
        } catch(PDOException $e) {
            error_log("Total visits error: " . $e->getMessage());
            return 0;
        }
    }

    public function getTodayVisits() {
        $current_date = date('Y-m-d');
        try {
            $stmt = $this->conn->prepare("SELECT SUM(views) as today_visits FROM counter WHERE date = :date");
            $stmt->execute(['date' => $current_date]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['today_visits'] ?? 0;
        } catch(PDOException $e) {
            error_log("Today visits error: " . $e->getMessage());
            return 0;
        }
    }
}
?>