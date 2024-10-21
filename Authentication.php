<?php
include 'config/config.php';
class authentication
{
    private $db;
    var $dbConnect;
    function __construct(){}
    function _destruct(){}

    function register()
    {
        $this->db = new config();
        $this->dbConnect = $this->db->connect();
        var_dump($_REQUEST);
        if (!empty($_REQUEST['name']) && !empty($_REQUEST['password']) && !empty($_REQUEST['email'])) {
            $name = $_REQUEST['name'];
            $password = $_REQUEST['password'];
            $email = $_REQUEST['email'];
            $duplicate = $this->dbConnect->query("SELECT * FROM users WHERE email = '$email'");
            if ($duplicate->num_rows > 0) {
                http_response_code(404);
                $error = array(
                    "code" => http_response_code(404),
                    "status" => false,
                    "message" => "This email is already registered."
                );
                echo json_encode($error);
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $SQL = "INSERT INTO `users`(`name`,`email`,`password`) VALUES('$name','$email','$password_hash')";
                $insert = $this->dbConnect->query($SQL);
                if ($insert) {
                    http_response_code(200);
                    $success = array(
                        "code" => http_response_code(200),
                        "status" => false,
                        "message" => "Successfully registered."
                    );
                    echo json_encode($success);
                } else {
                    http_response_code(404);
                    $error = array(
                        "code" => http_response_code(404),
                        "status" => false,
                        "message" => "Error registering."
                    );
                    echo json_encode($error);
                }
            }
        } else {
            http_response_code(404);
            $error = array(
                "code" => http_response_code(404),
                "status" => false,
                "message" => "Errors."
            );
            echo json_encode($error);
        }

    }

    function login()
    {
        $this->db = new config();
        $this->dbConnect = $this->db->connect();
        if ($this->dbConnect == NULL) {
            $this->db->sendResponse(503,'{“error_message”:'.$this->db->getStatusCodeMessage(503).'}');
        } else {
            var_dump($_POST, 576);
            if (!empty($_REQUEST['email']) && !empty($_REQUEST['password'])) {
                $email = $_REQUEST['email'];
                $password = $_REQUEST['password'];
                $password_hash = md5($password);
                $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password_hash'";
                $user = $this->dbConnect->query($sql);
                if ($user->num_rows > 0) {


                } else {
                    http_response_code(404);
                    $error = array(
                        "code" => http_response_code(404),
                        "status" => false,
                        "message" => "User not found."
                    );
                    echo json_encode($error);
                }
            }
        }
    }
}

?>
