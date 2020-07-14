<?php
// include connection class
include("DBConnection.php");
// Event
class Newsletter 
{
    protected $db;
    private $_name;
    private $_email;
    private $_verifyCode;
    private $_isVerified;
    private $_createdDate;
    private $_modifiedDate;
    private $_status;

    public function setName($name) {
        $this->_name = $name;
    }
    public function setEmail($email) {
        $this->_email = $email;
    }
    public function setVerifyCode($verifyCode) {
        $this->_verifyCode = $verifyCode;
    }

    public function setIsVerified($isVerified) {
        $this->_isVerified = $isVerified;
    }

    public function setStatus($status) {
        $this->_status = $status;
    }
    
    // __construct
    public function __construct() {
        $this->db = new DBConnection();
        $this->db = $this->db->returnConnection();
    }

    // create record in database
    public function create() {
		try {
    		$sql = 'INSERT INTO newsletter (name, email, verification_code, is_verified, created_date, status)  VALUES (:name, :email, :verification_code, :is_verified, :created_date, :status)';
    		$data = [
			    'name' => $this->_name,
                'email' => $this->_email,
			    'verification_code' => $this->_verifyCode,
                'is_verified' => $this->_isVerified,
                'created_date' => date('Y-m-d H:i:s', time()),
                'status' => $this->_status,
			];
	    	$stmt = $this->db->prepare($sql);
	    	$stmt->execute($data);
			$status = $this->db->lastInsertId();
            return $status;

		} catch (Exception $err) {
    		die("Oh noes! There's an error in the query! ".$err);
		}

    }

    // update record in database
    public function update() {
        try {
		    $sql = "UPDATE newsletter SET is_verified=:is_verified, status=:status WHERE verification_code=:verification_code";
		    $data = [
			    'is_verified' => $this->_isVerified,
                'status' => $this->_status,
                'verification_code' => $this->_verifyCode,
			];
			$stmt = $this->db->prepare($sql);
			$stmt->execute($data);
			$status = $stmt->rowCount();
            return $status;
		} catch (Exception $err) {
			die("Oh noes! There's an error in the query! " . $err);
		}
    }

    // get data from database
    public function getRow() {
        try {
            $sql = "SELECT verification_code FROM newsletter WHERE email=:email";
            $stmt = $this->db->prepare($sql);
            $data = [
                'email' => $this->_email
            ];
            $stmt->execute($data);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $err) {
            die("Oh noes! There's an error in the query!" .$err);
        }
    }

    // delete record from database
    public function delete() {
    	try {
	    	$sql = "DELETE FROM newsletter WHERE verification_code=:verification_code";
		    $stmt = $this->db->prepare($sql);
		    $data = [
		    	'verification_code' => $this->_verifyCode
			];
	    	$stmt->execute($data);
            $status = $stmt->rowCount();
            return $status;
	    } catch (Exception $err) {
		    die("Oh noes! There's an error in the query! " . $err);
		}
    }
}
?>