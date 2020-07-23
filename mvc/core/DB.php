<?
    class DB {
        protected $conn = '';

        function __construct() {
            $this->conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
            if($this->conn->connect_error) {
                die("Connection fail " . $this->conn->connect_error);
            }
        }

        function insert($table, $array) {
            $str_key = '';
            $str_value = '';
            foreach($array as $key => $value) {
                $str_key .= "$key".",";
                (is_string($value)) ? $string_value = "'$value'" . "," : $string_value = $value . ',';
                $str_value .= $string_value;
            }
            
            $str_key = trim($str_key, ','); 
            $str_value = trim($str_value, ',');

            $sql = "INSERT INTO $table($str_key) VALUES ($str_value)";
            if($this->conn->query($sql)) {
                echo 'Insert Successfully';
            } else {
                echo 'Error: ' . $sql . '<br>' . $this->conn->error;
            }
        }
    }
?>