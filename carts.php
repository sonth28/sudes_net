<?php
include 'config/config.php';
class Carts{
    private $db;
    var $dbConnect;

    function __construct(){}

    function __destruct(){}

    function viewCarts()
    {
        $this->db = new config();
        ob_start();
        session_start();
        if (!empty($_SESSION['token']) && $_SESSION['token'] == $this->db->getBearerToken()) {
            $this->dbConnect = $this->db->connect();
            if ($this->dbConnect == NULL) {
                $this->db->sendResponse(503, '{“error_message”:' . $this->db->getStatusCodeMessage(503) . '}');
            } else {
                $user_id = $_POST['user_id'];
                $sql = "SELECT * FROM `cart_items` inner join `carts` on `carts`.`id`=`cart_items`.`cart_id`
                        WHERE `user_id` = '$user_id'";
                $result = $this->dbConnect->query($sql);
                if ($result != NULL) {
                    $data = [];
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    $this->db->sendResponse(200, '{"status": "success","message": "Get products successfully","data":' . json_encode($data) . '}');
                }  else {
                    $this->db->sendResponse(200, '{"status": "success","message": "Get products successfully","data":' . null . '}');
                }
            }
        } else {
            error_log(date("Y-m-d H:i:s")." Not Authorized\r\n", 3, "../log/error_log.log");
            http_response_code(403);
            $error = array(
                "code" => http_response_code(403),
                "status" => false,
                "message" => "Not Authorized."
            );
            echo json_encode($error);
        }
    }
}

?>