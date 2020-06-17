<?php
namespace Library;

class BookLocations extends Books
{
	
	public function getAllBookLocations(){
			
	$fields = 'books.book_id, books.title, 
			   books.author, books.publish_date, 
			   book_locations.building, 
			   book_locations.bookcase, book_locations.row';
			   
	$query = 'SELECT ' . $fields . ' FROM books INNER JOIN book_locations ON books.book_id=book_locations.book_id';
	$paramValue = '';
	$paramType = '';
		
		$allBookInfo = $this->ds->select($query, $paramType, $paramValue);
		
		return json_encode($allBookInfo);
	}
	
	// return information for a book given any search term (only 5 key terms implemented)
	// If more than one book is found in db (no book id input) all results are returned.
	
	public function getBookLocation($bookId, $bookTitle, $bookAuthor, $bookPublisher, $bookDate) {
	
	$fields = 'books.book_id, books.title, 
			   books.author, books.publish_date, 
			   book_locations.building, 
			   book_locations.bookcase, book_locations.row';
		
	$query = 'SELECT ' . $fields . ' FROM books INNER JOIN book_locations ON books.book_id=book_locations.book_id WHERE books.book_id = ?'; 
	$paramType = 'i';	

	
	if ($bookId != 0) {
		
		$paramValue = array((int) $bookId);
		
		
		$bookInfo = $this->ds->select($query, $paramType, $paramValue);
		
		return json_encode($bookInfo);
		
	} else {
		
		$thisId = $this->getBookId($bookTitle, $bookAuthor, $bookPublisher, $bookDate);
		
		if (!empty ($thisId) && $thisId != 0){
			
		$bookInfo = array();
		// Return one or many results from search.
			foreach($thisId as $value) {
	
				$paramValue = array((int) $value["book_id"]);
				$result = $this->ds->select($query, $paramType, $paramValue);
				
				if (!empty ($result)){
					
					array_push($bookInfo, $result);
					
				
				}
			}
			return $bookInfo;		
	} else {
		return array("Status", "error", "Message" => "No books found with given search terms! Sorry!");
	}
	}
	}

	// generate new location for book & insert

	public function createNewBookLocation($book_id) {
		
		// TODO: Remove Hardcode. Location should either be automatically determined from book details or input by the user with the book.
		
		$name = "Cardiff Library";
		$building = "West Wing";
		$bookcase = rand(1, 10);
		$row = chr(rand(97,122));
		
		// TODO: Check inputs are valid & not null e.g a row is a letter and a bookcase is a number (arbitrary).
			
			$query = 'INSERT INTO book_locations (`book_id`, `library_name`, `building`, `bookcase`, `row`) VALUES (?, ?, ?, ?, ?)';
			$paramType = 'issss';
            $paramValue = array(
               $book_id,
			   $name,
			   $building,
			   $bookcase,
			   $row
            );
			$locationId = $this->ds->insert($query, $paramType, $paramValue);
            if(! empty($locationId)) {	
			return array("Status" => "success", "Message" => "New book successfully inserted. Book Id = " . $locationId);
			} else {
				 return array("Status" => "error", "Message" => "There has been a connection error! Please try again.");
			}
		
	} 
	
		
		
	
	
	
	
}