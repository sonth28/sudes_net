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
                    $this->db->sendResponse(200, '{"status": "success","message": "Get carts successfully","data":' . json_encode($data) . '}');
                }  else {
                    $this->db->sendResponse(200, '{"status": "success","message": "Get carts successfully","data":' . null . '}');
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

    function addToCart()
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
                $sql_get_cart = "SELECT * FROM `carts` WHERE `user_id` = '$user_id'";
                $cart = $this->dbConnect->query($sql_get_cart);
                if ($cart->num_rows == 0) {
                    $sql_add_cart = "INSERT INTO `carts` (`order_id`, `user_id`) VALUES ('1', '$user_id')";
                    $this->dbConnect->query($sql_add_cart);
                    $cart = $this->dbConnect->query($sql_get_cart);
                }
                $cart_id = $cart->fetch_assoc()['id'];
                $product_id = $_POST['product_id'];
                $quantity = $_POST['quantity'];
                $sql_add_cart_items = "INSERT INTO `cart_items` (`product_id`, `quantity`, `cart_id`) VALUES ('$product_id', '$quantity', '$cart_id')";
                $cart_items = $this->dbConnect->query($sql_add_cart_items);
                if ($cart_items != NULL) {
                    http_response_code(200);
                    $success = array(
                        "code" => http_response_code(200),
                        "status" => true,
                        "message" => "Add to cart success."
                    );
                    echo json_encode($success);
                } else {
                    error_log(date("Y-m-d H:i:s")." Add to cart failed.\r\n", 3, "../log/error_log.log");
                    http_response_code(404);
                    $success = array(
                        "code" => http_response_code(404),
                        "status" => false,
                        "message" => "Add to cart failed."
                    );
                    echo json_encode($success);
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