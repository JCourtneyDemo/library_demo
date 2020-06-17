<?php
namespace Library;

class Connection
{

    private const HOST = 'localhost';

    private const USERNAME = 'root';

    private const PASSWORD = '';

    private const DATABASENAME = 'library_db';

    private $connect;

    function __construct()
    {
        $this->connect = $this->getConnection();
    }

    public function getConnection()
    {
        $connect = new \mysqli(self::HOST, self::USERNAME, self::PASSWORD, self::DATABASENAME);

        if (mysqli_connect_errno()) {
            trigger_error("Problem with connecting to database.");
        }

        $connect->set_charset("utf8");
        return $connect;
    }
	
	// if result != 0 select success.
    public function select($query, $paramType = "", $paramArray = array())
    {
		
        $stmt = $this->connect->prepare($query);

        if (! empty($paramType) && ! empty($paramArray)) {

            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }

        if (! empty($resultset)) {
            return $resultset;
        }
		}
	
	// if result != 0 insert success.
    public function insert($query, $paramType, $paramArray)
    {
        $stmt = $this->connect->prepare($query);
        $this->bindQueryParams($stmt, $paramType, $paramArray);
        $stmt->execute();
        $insertId = $stmt->insert_id;
        return $insertId;
    }
	
	//return true if update successful
	public function update($query, $paramType, $paramArray) 
	{
		$update = false;
         $stmt = $this->connect->prepare($query);
			
			if ($stmt === false) {
			trigger_error($this->connect->error, E_USER_ERROR);
			return;
	}	     
	  if (! empty($paramType) && ! empty($paramArray)) {

            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
     $stmt->execute();
		if ($stmt === false) {
			 trigger_error($this->connect->error, E_USER_ERROR);
			return;
		} else {
			$update = true;
			return $update;
		}
	
    }
 
    public function execute($query, $paramType = "", $paramArray = array())
    {
        $stmt = $this->connect->prepare($query);

        if (! empty($paramType) && ! empty($paramArray)) {
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
    }
 
    public function bindQueryParams($stmt, $paramType, $paramArray = array())
    {
        $paramValueReference[] = & $paramType;
        for ($i = 0; $i < count($paramArray); $i ++) {
            $paramValueReference[] = & $paramArray[$i];
        }
        call_user_func_array(array(
            $stmt,
            'bind_param'
        ), $paramValueReference);
    }
   
}