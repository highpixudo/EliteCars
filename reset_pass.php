<?php
class DB
{
    private $dbHost = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName = "elitecars";

    public function __construct()
    {
        if (!isset($this->db)) {
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if ($conn->connect_error) {
                die("Failed to connect with MySQL: " . $conn->connect_error);
            } else {
                $this->db = $conn;
            }
        }
    }

    public function is_table_empty()
    {
        $result = $this->db->query("SELECT id FROM gmail_oauth WHERE provider = 'google'");
        if ($result->num_rows) {
            return false;
        }

        return true;
    }

    public function get_access_token()
    {
        $sql = $this->db->query("SELECT provider_value FROM oauth_gmail WHERE provider='google'");
        $result = $sql->fetch_assoc();
        return json_decode($result['provider_value']);
    }

    public function get_refresh_token()
    {
        $result = $this->get_access_token();
        return $result->refresh_token;
    }

    public function update_access_token($token)
    {
        if ($this->is_table_empty()) {
            $sql = sprintf("INSERT INTO oauth_gmail(provider, provider_value) VALUES('%s', '%s')", 'google', $this->db->real_escape_string($token));
            $this->db->query($sql);
        } else {
            $sql = sprintf("UPDATE oauth_gmail SET provider_value = '%s' WHERE provider = '%s'", $this->db->real_escape_string($token), 'google');
            $this->db->query($sql);
        }
    }
}