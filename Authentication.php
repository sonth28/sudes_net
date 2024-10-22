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
        if (!empty($_REQUEST['name']) && !empty($_REQUEST['password']) && !empty($_REQUEST['email'])) {
            $name = $_REQUEST['name'];
            $password = $_REQUEST['password'];
            $email = $_REQUEST['email'];
            $duplicate = $this->dbConnect->query("SELECT * FROM users WHERE email = '$email'");
            if ($duplicate->num_rows > 0) {
                error_log(date("Y-m-d H:i:s")." Registered fail\r\n", 3, "../log/error_log.log");
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
                        "status" => "Success",
                        "message" => "Successfully registered."
                    );
                    echo json_encode($success);
                } else {
                    error_log(date("Y-m-d H:i:s")." Registered fail\r\n", 3, "../log/error_log.log");
                    http_response_code(404);
                    $error = array(
                        "code" => http_response_code(404),
                        "status" => false,
                        "message" => "Error registered."
                    );
                    echo json_encode($error);
                }
            }
        } else {
            error_log(date("Y-m-d H:i:s")." Not Authorized\r\n", 3, "../log/error_log.log");
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
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $email = $_REQUEST['email'];
                $password = $_REQUEST['password'];
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $user = $this->dbConnect->query($sql);
                if ($user->num_rows > 0) {
                    if (password_verify($password, $user->fetch_assoc()['password'])) {
                        ob_start();
                        session_start();
                        $token = bin2hex(random_bytes(32));
                        $_SESSION['token'] = $token;
                        $this->db->sendResponse(200, '{"status": "success","message": "Login successfully","token":' . json_encode($token) . '}');
                    } else {
                        error_log(date("Y-m-d H:i:s")." Login fail\r\n", 3, "../log/error_log.log");
                        http_response_code(404);
                        $error = array(
                            "code" => http_response_code(404),
                            "status" => false,
                            "message" => "User or password is not correct."
                        );
                        echo json_encode($error);
                    }
                } else {
                    error_log(date("Y-m-d H:i:s")." Login fail\r\n", 3, "../log/error_log.log");
                    http_response_code(404);
                    $error = array(
                        "code" => http_response_code(404),
                        "status" => false,
                        "message" => "User or password is not correct."
                    );
                    echo json_encode($error);
                }
            }
        }
    }
}

?>
