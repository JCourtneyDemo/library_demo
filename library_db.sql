-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2020 at 01:36 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_db`
--
CREATE DATABASE IF NOT EXISTS `library_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `library_db`;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `publish_date` varchar(255) DEFAULT NULL,
  `edition` int(11) DEFAULT NULL,
  `member_id` int(11) UNSIGNED DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `publisher`, `publish_date`, `edition`, `member_id`, `reg_date`) VALUES
(10000001, 'Harry Potter', 'J K Rowling', 'Lorem', '2000-11-01', 1, NULL, '2020-06-15 16:46:06'),
(50000001, 'Life of Birds', 'David Attenborough', 'Ipsem', '1995-05-01', 2, 50000065, '2020-06-15 16:49:18'),
(50000101, '1984', 'George Orwell', 'Lorem Ipsem', '1984-10-10', NULL, 55000065, '2020-03-11 17:44:03');

-- --------------------------------------------------------

--
-- Table structure for table `book_locations`
--

CREATE TABLE `book_locations` (
  `book_id` int(11) UNSIGNED NOT NULL,
  `library_name` varchar(255) NOT NULL,
  `building` varchar(255) NOT NULL,
  `bookcase` varchar(255) NOT NULL,
  `row` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book_locations`
--

INSERT INTO `book_locations` (`book_id`, `library_name`, `building`, `bookcase`, `row`) VALUES
(10000001, 'Cardiff Library', 'East Wing', '10', 'B'),
(50000001, 'Cardiff Library', 'West Wing', '1', 'D'),
(50000101, 'Cardiff Library', 'West Wing', '7', 'F');

-- --------------------------------------------------------

--
-- Table structure for table `book_status`
--

CREATE TABLE `book_status` (
  `book_id` int(11) UNSIGNED NOT NULL,
  `book_status` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `member_id` varchar(255) DEFAULT NULL,
  `book_rental_date` date DEFAULT NULL,
  `book_return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book_status`
--

INSERT INTO `book_status` (`book_id`, `book_status`, `member_id`, `book_rental_date`, `book_return_date`) VALUES
(10000001, 0, NULL, NULL, NULL),
(50000001, 2, '50000065', '2020-04-08', '2020-06-16'),
(50000101, 1, '55000065', '2020-06-08', '2020-08-14');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address_line_1` varchar(255) NOT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `county` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `current_total_books` int(11) NOT NULL,
  `overdue_fines_due` int(11) DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `first_name`, `last_name`, `address_line_1`, `address_line_2`, `town`, `county`, `postcode`, `email`, `phone`, `current_total_books`, `overdue_fines_due`, `reg_date`) VALUES
(50000065, 'Jack', 'Courtney', '21', 'Blaen Cefn', 'Winch Wen', 'Swansea', 'SA17LF', 'jack.r.courtney@gmail.com', '07585707827', 1, NULL, '2020-06-15 16:51:15'),
(50006065, 'John', 'Doe', 'Line One', 'Line Two', 'Town name', 'County Name', 'postcode', 'test@test.com', '0800123456', 0, NULL, '2020-06-15 16:51:15'),
(55000065, 'Jack', 'Courtney', '', '', '', '', '', 'test@test.com', '', 1, NULL, '2020-06-17 08:51:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `title` (`title`),
  ADD KEY `author` (`author`),
  ADD KEY `publish_date` (`publish_date`);

--
-- Indexes for table `book_locations`
--
ALTER TABLE `book_locations`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `book_status`
--
ALTER TABLE `book_status`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `book_status` (`book_status`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50000516;

--
-- AUTO_INCREMENT for table `book_locations`
--
ALTER TABLE `book_locations`
  MODIFY `book_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50000516;

--
-- AUTO_INCREMENT for table `book_status`
--
ALTER TABLE `book_status`
  MODIFY `book_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50000516;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55005067;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
