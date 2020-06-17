<?php
namespace Library;

class Members
{

    protected $ds;
	
	 function __construct()
    {
        require_once __DIR__ . './../lib/connectionSource.php';
        $this->ds = new Connection();
	
    }	
	
	// Return all valid book_id's from db given any four logical inputs.
	// Only one input required to return.
	public function getMemberId($firstName, $lastName, $email, $phone){
		$query = 'SELECT books.book_id FROM books';
		$paramValue = array();
		$paramType = '';
		$count = 0;
		if (!empty ($bookTitle)) {
		$query .= ' WHERE title = ?';
		array_push($paramValue, $bookTitle);
		$paramType .= 's';
		$count++;
		}
		if (!empty ($bookAuthor)) {
			if ($count != 0){
			$query .= ' AND author = ?';
			} else {
			$query .= ' WHERE author = ?';	
			}
		array_push($paramValue, $bookAuthor);
		$paramType .= 's';
		$count++;
		}
		if (!empty ($bookPublisher)) {
			if ($count != 0){
				$query .= ' AND publisher = ?';
			} else {
				$query .= ' WHERE publisher = ?';
			}
				
		array_push($paramValue, $bookPublisher);
		$paramType .= 's';
		$count++;
		}
		if (!empty ($bookDate)) {
		if ($count != 0){
		$query .= ' AND publish_date = ?';
		} else {
		$query .= ' WHERE publish_date = ?';	
		}
		
		array_push($paramValue, $bookDate);
		$paramType .= 's';
		}
		if (!empty ($paramValue)){
			return $this->ds->select($query, $paramType, $paramValue);
			
		} else {
			return 0;
		}
	
	}

	// escape/validate inputs
	private function validate_input($data) {
		foreach ($data as $value) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
		}
    return $data;
    }  
	

	public function addNewMember($firstName, $lastName, $addressLineOne, $addressLineTwo, $town, $county, $postcode, $email, $phone){
	
		if ($firstName != "" && $lastName != "" && $email != ""){

		$newMember = array("firstName" => $firstName, "lastName" => $lastName, "addressLineOne" => $addressLineOne, "addressLineTwo" => $addressLineTwo, "town" => $town, "county" => $county, "postcode" => $postcode, "email" => $email, "phone" => $phone);
		
		//TODO: Much more extensive validation. 
		$newMember = $this->validate_input($newMember);
			
			$query = 'INSERT INTO members (`first_name`, `last_name`, `address_line_1`, `address_line_2`, `town`, `county`, `postcode`, `email`, `phone`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
			$paramType = 'sssssssss';
            $paramValue = array(
               $newMember["firstName"],
			   $newMember["lastName"],
			   $newMember["addressLineOne"],
			   $newMember["addressLineTwo"],
			   $newMember["town"],
			   $newMember["county"],
			   $newMember["postcode"],
			   $newMember["email"],
			   $newMember["phone"],
            );
			$memberId = $this->ds->insert($query, $paramType, $paramValue);
            if(! empty($memberId)) {	
			return array("Status" => "success", "Message" => "Your membership has been successful! MemberId = " . $memberId);
			} else {
				 return array("Status" => "error", "Message" => "There has been a connection error! Please try again.");
			}
		
	} else {
		 return array("Status" => "error", "Message" => "You must provide a valid name and email for your membership to be accepted!");
		}
	}
}
