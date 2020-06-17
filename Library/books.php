<?php
namespace Library;

class Books
{

    protected $ds;
	
	 function __construct()
    {
        require_once __DIR__ . './../lib/connectionSource.php';
        $this->ds = new Connection();
	
    }	
	
	// Return all valid book_id's from db given any four logical inputs.
	// Only one input required to return.
	public function getBookId($bookTitle, $bookAuthor, $bookPublisher, $bookDate){
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
	public function validate_input($data) {
		foreach ($data as $value) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
		}
    return $data;
    }  
	
	// insert new book into books
	// create new location for book
	// generate new status (default) for new book & insert
	public function insertNewBook($title, $author, $publisher, $publish_date, $edition){
		
		if (!is_int($edition)) {
			$edition = null;
		}	
		// check title, author, publish_date are not null. (arbitrary rule)
		if ($title != "" && $author != "" && $publish_date != ""){

		$newBook = array("title" => $title, "author" => $author, "publisher" => $publisher, "p_date" => $publish_date, "edition" => $edition);
		
		$newBook = $this->validate_input($newBook);
			
			$query = 'INSERT INTO books (`title`, `author`, `publisher`, `publish_date`, `edition`) VALUES (?, ?, ?, ?, ?)';
			$paramType = 'sssss';
            $paramValue = array(
                $newBook["title"],
				$newBook["author"],
				$newBook["publisher"],
				$newBook["p_date"],
				$newBook["edition"]
            );
			$bookId = $this->ds->insert($query, $paramType, $paramValue);
            if(! empty($bookId)) {
			
					require_once './Library/book_locations.php';
					$locations = new BookLocations();
					
					$newLocation = $locations->createNewBookLocation($bookId);
				
					if ($newLocation["Status"] == "success"){
						
						require_once './Library/book_status.php';
						$status = new BookStatus();
					
						$newStatus = $status->createNewBookStatus($bookId);
				
						if ($newStatus["Status"] == "success"){
							return array("Status" => "success", "Message" => "New book successfully inserted. Book Id = " . $bookId);
							
						} else {
				 return array("Status" => "error", "Message" => "There has been an error! Please support.");
			}
					} else {
				 return array("Status" => "error", "Message" => "There has been an error! Please contact support.");
			}
			} else {
				 return array("Status" => "error", "Message" => "There has been a connection error! Please try again.");
			}
		
	} else {
		 return array("Status" => "error", "Message" => "You must provide a valid Title, Author and Publish Date for new books to be accepted!");
		}
	}
}
