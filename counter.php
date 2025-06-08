<?php
class VisitorCounter {
    private $conn;
    private $current_page;
    private $session_timeout; // in minutes

    public function __construct($current_page = '', $session_timeout = 30) {
        $host = 'localhost';
        $dbname = 'planetarium_db';
        $username = 'root';
        $password = '';
        
        $this->current_page = $current_page;
        $this->session_timeout = $session_timeout; // Default: 30 minutes

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function trackVisitor() {
        $session_key = 'visitor_tracked';
        $session_time_key = 'last_visit_time';
        $current_time = time();
        
        // Check if visitor was already tracked recently
        $already_tracked = isset($_SESSION[$session_key]);
        $last_visit_time = $_SESSION[$session_time_key] ?? 0;
        
        // Time-based check: Allow new count after session timeout (in minutes)
        $time_since_last_visit = $current_time - $last_visit_time;
        $session_expired = $time_since_last_visit > ($this->session_timeout * 60);
        
        if (!$already_tracked || $session_expired) {
            // Track this visitor (new session or expired session)
            $ip = $_SERVER['REMOTE_ADDR'];
            $current_date = date('Y-m-d H:i:s');

            try {
                // Insert a new visit record
                $stmt = $this->conn->prepare("
                    INSERT INTO counter (ip, date, views, page)
                    VALUES (:ip, :date, 1, :page)
                ");
                $stmt->execute([
                    'ip' => $ip,
                    'date' => $current_date,
                    'page' => $this->current_page
                ]);
                
                // Mark as tracked and update timestamps
                $_SESSION[$session_key] = true;
                $_SESSION[$session_time_key] = $current_time;
                $_SESSION['session_start_time'] = $current_time;
                
            } catch(PDOException $e) {
                error_log("Visitor tracking error: " . $e->getMessage());
            }
        }
        // If already tracked within timeout period, do nothing
    }
    

    public function getTotalVisits() {
        try {
            // Count all visit records (each record = 1 visit)
            $stmt = $this->conn->query("SELECT COUNT(*) as total_visits FROM counter");
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
            // Count all visits for today (using DATE function to extract date from datetime)
            $stmt = $this->conn->prepare("SELECT COUNT(*) as today_visits FROM counter WHERE DATE(date) = :date");
            $stmt->execute(['date' => $current_date]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['today_visits'] ?? 0;
        } catch(PDOException $e) {
            error_log("Today visits error: " . $e->getMessage());
            return 0;
        }
    }
    
    public function getSessionInfo() {
        $session_start = $_SESSION['session_start_time'] ?? 0;
        $last_visit = $_SESSION['last_visit_time'] ?? 0;
        $current_time = time();
        $session_duration = $session_start ? $current_time - $session_start : 0;
        $time_since_last_visit = $last_visit ? $current_time - $last_visit : 0;
        $time_until_expire = $this->session_timeout * 60 - $time_since_last_visit;
        
        return [
            'session_type' => 'time_based_session',
            'timeout_minutes' => $this->session_timeout,
            'session_duration_minutes' => round($session_duration / 60, 1),
            'time_since_last_visit_minutes' => round($time_since_last_visit / 60, 1),
            'time_until_expire_minutes' => round(max(0, $time_until_expire) / 60, 1),
            'session_active' => isset($_SESSION['visitor_tracked']),
            'session_start_time' => $session_start ? date('Y-m-d H:i:s', $session_start) : null,
            'last_visit_time' => $last_visit ? date('Y-m-d H:i:s', $last_visit) : null
        ];
    }
}
?>