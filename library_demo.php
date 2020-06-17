<?php
use Library\Books;
	require_once './Library/books.php';
	$books = new Books();
	
// $newBook = $books->insertNewBook('B', 'A', 'E', '1980-12-02', '');
	
use Library\BookLocations;
  require_once './Library/book_locations.php';
    $locations = new BookLocations();

	// $bookLoc = $locations->getBookLocation('', '', 'J K Rowling', '', '2000-11-01');
	// $allBookLocs = $locations->getAllBookLocations();
	
use Library\BookStatus;
	require_once './Library/book_status.php';
	$status = new BookStatus();
	
	
	// $bookStatus = $status->getBookStatus('', '', 'J K Rowling', '', '2000-11-01');
	// $bookStatusByName = $status->getStatusByMemberName("Jack", "Courtney");
	//$allLentBooks = $status->getAllLentBooks();
	$allOverdueBooks = $status->getAllOverdueBooks();
	
use Library\Members;
		require_once './Library/library_members.php';
		$members = new Members();
	
		// $newMember = $members->addNewMember("Jack", "Courtney", '', '', '', '', '', "test@test.com", '');
		
	
?>
<html>

<body style="padding-top:100px">

<h1><?php // echo $newBook["Message"]; ?></h1>

<h1><?php // echo $bookLocs; ?></h1>
<h1><?php // echo $allBookLocs; ?></h1>


<h1><?php//echo $allLentBooks; ?></h1>
<h1><?php //echo $allOverdueBooks; ?></h1>
<h1><?php //echo $bookStatus; ?></h1>
<h1><?php //echo $bookStatusByName; ?></h1>

<h1><?php // echo $newMember["Message"]; ?></h1>

</body>
</html>
