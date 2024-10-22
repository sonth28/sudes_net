<?php
include 'config/config.php';

class products
{
    private $db;
    var $dbConnect;
    var $result;

    function __construct()
    {
    }

    function _destruct()
    {
    }

    function getProducts()
    {
        $this->db = new config();
        ob_start();
        session_start();
        if (!empty($_SESSION['token']) && $_SESSION['token'] == $this->db->getBearerToken()) {
            $this->dbConnect = $this->db->connect();
            if ($this->dbConnect == NULL) {
                $this->db->sendResponse(503, '{“error_message”:' . $this->db->getStatusCodeMessage(503) . '}');
            } else {
                $sql = 'SELECT * FROM products ORDER BY id DESC';
                $per_page = !empty($_REQUEST['per_page']) ? $_REQUEST['per_page'] : null;
                $from = !empty($_REQUEST['page']) ? ($_REQUEST['page'] - 1) * $per_page + 1 : null;
                $to = !empty($_REQUEST['page']) ? $_REQUEST['page'] * $per_page : null;

                if ($per_page != null && $from != null && $to != null) {
                    $sql = "SELECT * FROM products BETTWEEN " . $from . " AND " . $to . " ORDER BY id DESC";
                }
                $this->result = $this->dbConnect->query($sql);
            }
            if ($this->result != NULL) {
                $resultGet = array();
                while ($row = $this->result->fetch_assoc()) {
                    $resultGet[] = $row;
                }
                $this->db->sendResponse(200, '{"status": "success","message": "Get products successfully","data":' . json_encode($resultGet) . '}');
            } else {
                $this->db->sendResponse(200, '{"status": "success","message": "Get products successfully","data":' . null . '}');
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

    function getOneProduct()
    {
        $this->db = new config();
        ob_start();
        session_start();
        if (!empty($_SESSION['token']) && $_SESSION['token'] == $this->db->getBearerToken()) {
            $this->dbConnect = $this->db->connect();
            if ($this->dbConnect == NULL) {
                $this->db->sendResponse(503, '{“error_message”:' . $this->db->getStatusCodeMessage(503) . '}');
            } else {
                $id = !empty($_SERVER['PATH_INFO']) ? substr($_SERVER['PATH_INFO'], 1) : NULL;
                if (!empty($id)) {
                    $sql = 'SELECT * FROM products WHERE id = ' . $id;
                    $this->result = $this->dbConnect->query($sql);
                }
            }
            if ($this->result != NULL) {
                $this->db->sendResponse(200, '{"status": "success","message": "Get one products successfully","data":' . json_encode($this->result->fetch_assoc()) . '}');
            } else {
                $this->db->sendResponse(200, '{"status": "success","message": "Get one products successfully","data":' . null . '}');
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

    function createProduct()
    {
        $this->db = new config();
        ob_start();
        session_start();
        if (!empty($_SESSION['token']) && $_SESSION['token'] == $this->db->getBearerToken()) {
            $this->dbConnect = $this->db->connect();
            if ($this->dbConnect == NULL) {
                $this->db->sendResponse(503, '{“error_message”:' . $this->db->getStatusCodeMessage(503) . '}');
            } else {
                if (!empty($_REQUEST['vendor_id']) && !empty($_REQUEST['name']) && !empty($_REQUEST['quantity']) && !empty($_REQUEST['image'])) {
                    $vendor_id = $_REQUEST['vendor_id'];
                    $name = $_REQUEST['name'];
                    $quantity = $_REQUEST['quantity'];
                    $image = $_REQUEST['image'];
                    $created_date = date("Y-m-d");
                    $sql = "INSERT INTO products (vendor_id, name, quantity, image, created_date) values ('$vendor_id', '$name', '$quantity', '$image', '$created_date')";
                    $insert = $this->dbConnect->query($sql);
                    if ($insert) {
                        http_response_code(200);
                        $success = array(
                            "code" => http_response_code(200),
                            "status" => true,
                            "message" => "Create product success."
                        );
                        echo json_encode($success);
                    } else {
                        error_log(date("Y-m-d H:i:s")." Create product fail\r\n", 3, "../log/error_log.log");
                        http_response_code(404);
                        $error = array(
                            "code" => http_response_code(404),
                            "status" => false,
                            "message" => "Create product error."
                        );
                        echo json_encode($error);
                    }
                } else {
                    error_log(date("Y-m-d H:i:s")." Create product fail\r\n", 3, "../log/error_log.log");
                    http_response_code(404);
                    $error = array(
                        "code" => http_response_code(404),
                        "status" => false,
                        "message" => "Create product error."
                    );
                    echo json_encode($error);
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
