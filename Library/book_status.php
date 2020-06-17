<?php
namespace Library;
use \DateTime;
class BookStatus extends Books
{
	
	// update the status of a book
	// most likely done periodically (once a day)
	// if (today > return_date && status > 1 ) status = 2.
	// on lending of book to member set status to 1.
	// on return of book by member reset status to 0.
	public function updateStatus($book_id, $newStatus) {
		
	}
	
	
	// return information for a book given any search term (only 5 key terms implemented)
	// If more than one book is found in db (no book id input) all results are returned.
	
	public function getBookStatus($bookId, $bookTitle, $bookAuthor, $bookPublisher, $bookDate) {
	
	$fields = 'books.book_id, books.title, 
			   books.author,
			   book_status.book_status,
			   book_status.member_id,
			   book_status.book_rental_date, book_return_date';
		
	$query = 'SELECT ' . $fields . ' FROM books INNER JOIN book_status ON books.book_id=book_status.book_id WHERE books.book_id = ?'; 
	$paramType = 'i';	

	if ($bookId != 0) {
		
		$paramValue = array((int) $bookId);
		
		$bookStatusInfo = $this->ds->select($query, $paramType, $paramValue);
		
		return json_encode($bookStatusInfo);
		
	} else {
		
		$thisId = $this->getBookId($bookTitle, $bookAuthor, $bookPublisher, $bookDate);
		
		if (!empty ($thisId) && $thisId != 0){
			
		$bookStatusInfo = array();
		// Return one or many results from search.
			foreach($thisId as $value) {
	
				$paramValue = array((int) $value["book_id"]);
				$result = $this->ds->select($query, $paramType, $paramValue);
				
				if (!empty ($result)){
					
					array_push($bookStatusInfo, $result);
					
				
				}
			}
			return $bookStatusInfo;		
	} else {
		return array("Status", "error", "Message" => "No books found with given search terms! Sorry!");
	}
	}
	}
	
	public function getStatusByMemberName($first_name, $last_name) {
	$fields = 'members.member_id,
				books.book_id, 
				books.title, 
				books.author, 
				book_status.book_rental_date,
				book_status.book_return_date';
		
	$query = 'SELECT ' . $fields . ' FROM members USE INDEX (first_name, last_name) 
			INNER JOIN books ON books.member_id=members.member_id 
			INNER JOIN book_status ON books.book_id=book_status.book_id 
			WHERE members.first_name=? AND members.last_name=?';
			
	$paramValue = array(
		$first_name,
		$last_name
		);
			
		$paramType = 'ss';
		
		$memberBookStatus = $this->ds->select($query, $paramType, $paramValue);
		
		return json_encode($memberBookStatus);

		}

	public function createNewBookStatus($book_id) {
		
		// TODO: Check inputs are valid & not null e.g a row is a letter and a bookcase is a number (arbitrary).
			
			$query = 'INSERT INTO book_status (`book_id`) VALUES (?)';
			$paramType = 'i';
            $paramValue = array(
               $book_id,
            );
			$statusId = $this->ds->insert($query, $paramType, $paramValue);
            if(! empty($statusId)) {	
			return array("Status" => "success", "Message" => "New book successfully inserted. Book Id = " . $statusId);
			} else {
				 return array("Status" => "error", "Message" => "There has been a connection error! Please try again.");
			}
		
	}
	
	// overdue book search function 
	public function getAllLentBooks() {
	
	$fields = 'books.book_id, 
				books.title, 
				books.author, 
				book_locations.building, 
				book_locations.bookcase, 
				book_locations.row, 
				members.member_id,
				members.first_name,
				members.last_name,
				members.email,
				members.current_total_books,
				members.overdue_fines_due,
				book_status.book_rental_date,
				book_status.book_return_date';
		
	$query = 'SELECT ' . $fields . ' FROM books INNER JOIN book_locations ON books.book_id=book_locations.book_id INNER JOIN book_status ON books.book_id=book_status.book_id INNER JOIN members ON books.member_id=members.member_id WHERE book_status.book_status > 0';
			
	$paramValue = '';		
	$paramType = '';
		
		$allLentBooks = $this->ds->select($query, $paramType, $paramValue);
		
		return json_encode($allLentBooks);
		
	}
	
	public function getAllOverdueBooks() {
		//assumes status is reset when book is returned.
		$allLentBooks = $this->getAllLentBooks();
		$allLentBooks = json_decode($allLentBooks, true);
		$overdueBooks = array();
		$now = date("Y-m-d");
		
		foreach ($allLentBooks as $book) {
				// use DateTime for accuracy.
				$rent = new DateTime($book["book_rental_date"]);
				$return = new DateTime($book["book_return_date"]);
				
				//return number of days between dates.
				$currentRentalPeriod = $return->diff($rent)->format("%a");
				
				// overdue if return date is in the past and it has been over 30 days since rental.
				if (($currentRentalPeriod > 30) && ($now > $book["book_return_date"])) {
					array_push($overdueBooks, $book);
				}
		}
		return json_encode($overdueBooks);	
	}
		
}