-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2025 at 07:44 PM
-- Server version: 8.0.42
-- PHP Version: 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web1220480_proj`
--

-- --------------------------------------------------------

--
-- Table structure for table `apartmentpreviewschedule`
--

CREATE TABLE `apartmentpreviewschedule` (
  `ScheduleID` int NOT NULL,
  `ApartmentID` bigint NOT NULL,
  `PreviewDay` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') COLLATE utf8mb4_general_ci NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apartmentpreviewschedule`
--

INSERT INTO `apartmentpreviewschedule` (`ScheduleID`, `ApartmentID`, `PreviewDay`, `StartTime`, `EndTime`) VALUES
(1, 1000002, 'Sunday', '10:00:00', '12:00:00'),
(2, 1000002, 'Tuesday', '14:00:00', '16:00:00'),
(3, 1000002, 'Friday', '09:30:00', '11:00:00'),
(4, 1000004, 'Thursday', '02:35:00', '17:36:00'),
(5, 1000004, 'Tuesday', '01:30:00', '04:57:00'),
(6, 1000004, 'Saturday', '22:00:00', '23:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `apartment_home`
--

CREATE TABLE `apartment_home` (
  `ApartmentID` bigint NOT NULL,
  `ApartmentNumber` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `NumberOfRooms` int DEFAULT NULL,
  `NumberOfBathrooms` int DEFAULT NULL,
  `IsFurnished` tinyint(1) DEFAULT NULL,
  `MonthlyRent` int DEFAULT NULL,
  `Area` decimal(10,2) DEFAULT NULL,
  `HeatingSystem_AirConditioning` tinyint(1) DEFAULT NULL,
  `AccessControl` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ExtraAmenities` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `OwnerID` bigint DEFAULT NULL,
  `Description` text COLLATE utf8mb4_general_ci,
  `NearbyLinks` text COLLATE utf8mb4_general_ci,
  `IsApproved` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apartment_home`
--

INSERT INTO `apartment_home` (`ApartmentID`, `ApartmentNumber`, `NumberOfRooms`, `NumberOfBathrooms`, `IsFurnished`, `MonthlyRent`, `Area`, `HeatingSystem_AirConditioning`, `AccessControl`, `ExtraAmenities`, `OwnerID`, `Description`, `NearbyLinks`, `IsApproved`) VALUES
(1000001, 'A101', 3, 2, 1, 1200, 85.50, 1, 'Yes', 'Pool, Gym', 100000001, 'Nice apartment near downtown', NULL, 0),
(1000002, 'B202', 2, 1, 0, 900, 60.00, 0, 'No', 'Parking', 100000002, 'Cozy apartment with parking', NULL, 0),
(1000003, 'C303', 4, 3, 1, 1500, 120.00, 1, 'Yes', 'Garden, Garage', 100000003, 'Spacious and modern', NULL, 0),
(1000004, '44C', 2, 2, 1, 400, 600.00, 1, 'yes', 'yse', 100000002, 'layal yaser abed', NULL, 1),
(1000005, '88c', 3, 3, 1, 900, 1005.00, 0, 'yes', 'yse', 100000002, 'ggdf', NULL, 1),
(1000008, '23ii', 4, 4, 1, 4, 4.00, 0, 'yes', 'yse', 100000002, 'hghg', NULL, 1),
(1000011, 'oo4', 9, 9, 1, 9, 9.00, 0, 'yes', 'yse', 100000002, 'iiu', NULL, 1),
(1000012, 'bb3', 5, 5, 0, 5, 5.00, 1, 'yes', 'yse', 100000002, 'ret', NULL, 1),
(1000018, 'pp7', 2, 2, 1, 2, 2.00, 0, 'yes', 'yse', 100000002, 'hfgh', NULL, 1),
(1000019, 'yy2', 2, 2, 1, 600, 2.00, 0, 'yse', 'yse', 100000009, 'layal yaser abed', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `apartment_images`
--

CREATE TABLE `apartment_images` (
  `ApartmentID` bigint NOT NULL,
  `ImageNumber` int NOT NULL,
  `ImageName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apartment_images`
--

INSERT INTO `apartment_images` (`ApartmentID`, `ImageNumber`, `ImageName`) VALUES
(1000001, 1, '1000001-1.jpg'),
(1000001, 2, '1000001-2.jpg'),
(1000001, 3, '1000001-3.jpg'),
(1000002, 1, '1000002-1.jpg'),
(1000002, 2, '1000002-2.jpg'),
(1000002, 3, '1000002-3.jpg'),
(1000003, 1, '1000003-1.jpg'),
(1000003, 2, '1000003-2.jpg'),
(1000003, 3, '1000003-3.jpg'),
(1000004, 1, '44C-1.jpg'),
(1000004, 2, '44C-2.jpg'),
(1000004, 3, '44C-3.jpg'),
(1000004, 4, '44C-4.jpg'),
(1000005, 1, '1000005-1.jpg'),
(1000005, 2, '1000005-2.jpg'),
(1000005, 3, '1000005-3.jpg'),
(1000008, 1, '23ii-1.jpg'),
(1000008, 2, '23ii-2.jpg'),
(1000008, 3, '23ii-3.jpg'),
(1000008, 4, '23ii-4.jpg'),
(1000011, 1, '1000011-1.jpg'),
(1000011, 2, '1000011-2.jpg'),
(1000011, 3, '1000011-3.jpg'),
(1000012, 1, '1000012-1.jpg'),
(1000012, 2, '1000012-2.jpg'),
(1000012, 3, '1000012-3.jpg'),
(1000018, 1, '1000018-1.jpg'),
(1000018, 2, '1000018-2.jpg'),
(1000018, 3, '1000018-3.jpg'),
(1000019, 1, '1000019-1.jpg'),
(1000019, 2, '1000019-2.jpg'),
(1000019, 3, '1000019-3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `creditcard`
--

CREATE TABLE `creditcard` (
  `CardNumber` char(9) COLLATE utf8mb4_general_ci NOT NULL,
  `CardName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `SecurityCode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ExpiryDate` date DEFAULT NULL,
  `CustomerID` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `creditcard`
--

INSERT INTO `creditcard` (`CardNumber`, `CardName`, `SecurityCode`, `ExpiryDate`, `CustomerID`) VALUES
('111222333', 'Customer One', '123', '2026-12-31', 100000001),
('123456789', 'Customer Card', '321', '2026-12-31', 100000010),
('444555666', 'Customer Two', '456', '2025-11-30', 100000002),
('777888999', 'Customer Three', '789', '2027-01-31', 100000003),
('852963741', 'layal', '456', '2025-10-21', 100000009);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` bigint NOT NULL,
  `NationalID` char(9) COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MobileNumber` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `TelephoneNumber` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Username` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `custmerImage` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `NationalID`, `Name`, `Password`, `DateOfBirth`, `Email`, `MobileNumber`, `TelephoneNumber`, `Username`, `custmerImage`) VALUES
(100000001, '111222333', 'Customer One', 'custpass1', '1990-01-01', 'customer1@example.com', '0555555555', '0111111111', NULL, NULL),
(100000002, '444555666', 'Customer Two', 'custpass2', '1985-05-05', 'customer2@example.com', '0666666666', '0222222222', NULL, NULL),
(100000003, '777888999', 'Customer Three', 'custpass3', '1995-09-09', 'customer3@example.com', '0777777777', '0333333333', 'layal', 'C100000003.jpg'),
(100000004, '111111111', 'layal', 'layal123', '2024-11-12', 'abed.layal@yahoo.com', '0599314027', '0599314085', '100000004@customer.flate', NULL),
(100000005, '789456123', 'layaa', '8layal', '2025-06-02', 'abed.layal@yahoo.com', '0599314027', '0599314085', 'layal4', NULL),
(100000009, '852963741', 'layal', '2layal', '2024-12-10', 'abed.layal@yahoo.com', '0599314027', '0599314085', '2layal', 'C100000009.jpg'),
(100000010, '333333333', 'Customer User', 'Customer', '1995-05-05', 'customer@example.com', '059333333', '026777777', 'Customer', 'C100000010.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `customeraddress`
--

CREATE TABLE `customeraddress` (
  `AddressID` int NOT NULL,
  `CustomerID` bigint NOT NULL,
  `FlatOrHouseNo` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `StreetName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `City` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PostalCode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customeraddress`
--

INSERT INTO `customeraddress` (`AddressID`, `CustomerID`, `FlatOrHouseNo`, `StreetName`, `City`, `PostalCode`) VALUES
(1, 100000001, '5', 'Oak Street', 'CityOne', '11001'),
(2, 100000002, '15', 'Pine Street', 'CityTwo', '22002'),
(3, 100000003, '25', 'Maple Street', 'CityThree', '33009'),
(4, 100000010, '12A', 'Main Street', 'Amman', '11194');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `ManagerID` int UNSIGNED NOT NULL,
  `Name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IDNumber` char(9) COLLATE utf8mb4_general_ci NOT NULL,
  `Phone` char(9) COLLATE utf8mb4_general_ci NOT NULL,
  `Username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`ManagerID`, `Name`, `Image`, `IDNumber`, `Phone`, `Username`, `Password`) VALUES
(100001, 'Ahmad Al-Saleh', '100001.jpg', '123456789', '059123456', 'ahmad', 'pass123'),
(100002, 'Sara Khalil', '100002.jpg', '987654321', '059987654', 'sara', 'pass123'),
(100003, 'Omar Al-Hassan', '100003.jpg', '456789123', '059456789', 'omar', 'pass123'),
(100004, 'Lina Abu Said', '100004.jpg', '789123456', '059789123', 'lina', 'pass123'),
(100005, 'System Manager', 'manager.jpg', '111111111', '059111111', 'Manager', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `marketing_info`
--

CREATE TABLE `marketing_info` (
  `MarketingID` int NOT NULL,
  `ApartmentID` bigint NOT NULL,
  `Title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Description` text COLLATE utf8mb4_general_ci,
  `URL` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marketing_info`
--

INSERT INTO `marketing_info` (`MarketingID`, `ApartmentID`, `Title`, `Description`, `URL`) VALUES
(1, 1000001, 'Summer Special', 'Get 10% off if you rent before July!', 'https://marketing.example.com/summer-special'),
(2, 1000002, 'Cozy & Affordable', 'Perfect for small families looking for comfort.', 'https://marketing.example.com/cozy-affordable'),
(3, 1000003, 'Luxury Living', 'Experience luxury and spacious living at a great price.', 'https://marketing.example.com/luxury-living'),
(7, 1000018, '', '', ''),
(8, 1000019, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `MessageID` int NOT NULL,
  `Title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `MessageBody` text COLLATE utf8mb4_general_ci NOT NULL,
  `MessageDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SenderType` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `SenderID` bigint NOT NULL,
  `RecipientType` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `RecipientID` bigint NOT NULL,
  `IsRead` tinyint(1) DEFAULT '0',
  `MessageType` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`MessageID`, `Title`, `MessageBody`, `MessageDate`, `SenderType`, `SenderID`, `RecipientType`, `RecipientID`, `IsRead`, `MessageType`) VALUES
(7, 'Rental Confirmation', 'Please confirm the rental for apartment #1000001.', '2025-06-11 08:36:13', 'Customer', 100000001, 'Manager', 100001, 1, 'RentConfirm'),
(8, 'Offer Accepted', 'The owner approved the offer for apartment #1000002.', '2025-06-11 08:36:13', 'Owner', 100000001, 'Manager', 100001, 0, 'OfferAccepted'),
(9, 'Welcome to Our Platform', 'Thank you for registering with us!', '2025-06-11 08:36:13', 'Manager', 100001, 'Customer', 100000001, 1, 'General'),
(10, 'Apartment Approval Status', 'Your apartment listing #1000002 has been approved.', '2025-06-11 08:36:13', 'Manager', 100001, 'Owner', 100000001, 1, 'General'),
(11, 'Confirm Rent', 'I confirm the rent for apartment #1000003 from June.', '2025-06-11 08:36:13', 'Customer', 100000002, 'Manager', 100001, 0, 'RentConfirm'),
(12, 'Offer Accepted', 'Owner has accepted the rental offer for apartment #1000004.', '2025-06-11 08:36:13', 'Owner', 100000002, 'Manager', 100001, 0, 'OfferAccepted'),
(13, 'Rental Approval Needed', 'Customer #100000003 requests to rent apartment #1000002 from 2025-06-26 to 2025-08-14. Please approve the rental.', '2025-06-11 08:48:19', 'Customer', 100000003, 'Owner', 100000002, 1, 'RentApprovalRequest'),
(14, 'Rental Approved', 'Your apartment has been approved and successfully rented. You can collect the key from the owner: Owner Two, Mobile: 0976543210.', '2025-06-11 08:56:10', 'Owner', 100000002, 'Customer', 100000003, 0, 'RentConfirm'),
(16, 'Preview Appointment Request', 'A customer has requested a preview for your flat (ID: 1000002). Please log in to accept or decline.', '2025-06-11 22:23:28', 'Customer', 100000003, 'Owner', 100000002, 0, 'PreviewRequest'),
(17, 'New Flat Pending Approval', 'Owner #100000002 has submitted flat #44C (Pending ID: 1). Please review.', '2025-06-12 10:41:47', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(18, 'Your Apartment Has Been Approved', 'Your flat #44C has been approved and is now available on the system.', '2025-06-12 10:46:54', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalAccepted'),
(19, 'Preview Appointment Request', 'A customer has requested a preview for your flat (ID: 1000004). Please log in to accept or decline.', '2025-06-12 12:44:38', 'Customer', 100000003, 'Owner', 100000002, 0, 'PreviewRequest'),
(20, 'Rental Approval Needed', 'Customer #100000003 requests to rent apartment #1000004 from 2025-06-06 to 2025-08-22. Please approve the rental.', '2025-06-12 14:41:41', 'Customer', 100000003, 'Owner', 100000002, 1, 'RentApprovalRequest'),
(21, 'Rental Approved', 'Your apartment has been approved and successfully rented. You can collect the key from the owner: Owner Two, Mobile: 0976543210.', '2025-06-12 14:47:58', 'Owner', 100000002, 'Customer', 100000003, 0, 'RentConfirm'),
(22, 'Flat Rented Notification', 'A flat has been rented!\n\nApartment #: 44C\nArea: 600.00 m²\nMonthly Rent: 400\nRental Period: 2025-06-06 to 2025-08-22\n\nOwner: Owner Two, Mobile: 0976543210\nCustomer: Customer Three, Mobile: 0777777777', '2025-06-12 14:47:58', 'Owner', 100000002, 'Manager', 100001, 1, 'RentNotification'),
(23, 'Rental Approval Needed', 'Customer #100000003 requests to rent apartment #1000004 from 2025-06-28 to 2025-07-25. Please approve the rental.', '2025-06-12 15:01:55', 'Customer', 100000003, 'Owner', 100000002, 1, 'RentApprovalRequest'),
(24, 'Rental Approved', 'Your apartment has been approved and successfully rented. You can collect the key from the owner: Owner Two, Mobile: 0976543210.', '2025-06-12 15:02:50', 'Owner', 100000002, 'Customer', 100000003, 0, 'RentConfirm'),
(25, 'Flat Rented Notification', 'A flat has been rented!\n\nApartment #: 44C\nArea: 600.00 m²\nMonthly Rent: 400\nRental Period: 2025-06-28 to 2025-07-25\n\nOwner: Owner Two, Mobile: 0976543210\nCustomer: Customer Three, Mobile: 0777777777', '2025-06-12 15:02:50', 'Owner', 100000002, 'Manager', 100001, 1, 'RentNotification'),
(26, 'New Flat Pending Approval', 'Owner #100000002 has submitted flat #88c (Pending ID: 2). Please review.', '2025-06-12 15:11:57', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(27, 'Your Apartment Has Been Approved', 'Your flat #88c has been approved and is now available on the system.', '2025-06-12 15:12:39', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalAccepted'),
(28, 'Rental Approval Needed', 'Customer #100000003 requests to rent apartment #1000004 from 2025-06-28 to 2025-07-30. Please approve the rental.', '2025-06-12 15:31:19', 'Customer', 100000003, 'Owner', 100000002, 1, 'RentApprovalRequest'),
(29, 'Rental Approved', 'Your apartment has been approved and successfully rented. You can collect the key from the owner: Owner Two, Mobile: 0976543210.', '2025-06-12 15:37:13', 'Owner', 100000002, 'Customer', 100000003, 0, 'RentConfirm'),
(44, 'New Flat Pending Approval', 'Approved: \r\nYour apartment has been approved by the manager and is now listed on the platform.\r\n\r\nApartment Number: qw1\r\nMonthly Rent: 5 JD\r\nArea: 5.00 m²\r\n        ', '2025-06-12 22:26:44', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(45, 'Apartment Approved', '\r\nYour apartment has been approved by the manager and is now listed on the platform.\r\n\r\nApartment Number: qw1\r\nMonthly Rent: 5 JD\r\nArea: 5.00 m²\r\n        ', '2025-06-12 22:27:16', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalConfirm'),
(46, 'New Flat Pending Approval', 'Approved: \r\nYour apartment has been approved by the manager and is now listed on the platform.\r\n\r\nApartment Number: qq3\r\nMonthly Rent: 6 JD\r\nArea: 6.00 m²\r\n        ', '2025-06-12 22:40:37', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(47, 'Apartment Approved', '\r\nYour apartment has been approved by the manager and is now listed on the platform.\r\n\r\nApartment Number: qq3\r\nMonthly Rent: 6 JD\r\nArea: 6.00 m²\r\n        ', '2025-06-12 22:41:18', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalConfirm'),
(48, 'New Flat Pending Approval', 'Approved: \r\nYour apartment has been approved by the manager and is now listed on the platform.\r\n\r\nApartment Number: oo4\r\nMonthly Rent: 9 JD\r\nArea: 9.00 m²\r\n        ', '2025-06-12 22:49:57', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(49, 'Apartment Approved', '\r\nYour apartment has been approved by the manager and is now listed on the platform.\r\n\r\nApartment Number: oo4\r\nMonthly Rent: 9 JD\r\nArea: 9.00 m²\r\n        ', '2025-06-12 22:50:30', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalConfirm'),
(50, 'New Flat Pending Approval', 'Approved: \r\nYour apartment has been approved by the manager and is now listed on the platform.\r\n\r\nApartment Number: bb3\r\nMonthly Rent: 5 JD\r\nArea: 5.00 m²\r\n        ', '2025-06-12 22:56:59', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(51, 'Apartment Approved', '\r\nYour apartment has been approved by the manager and is now listed on the platform.\r\n\r\nApartment Number: bb3\r\nMonthly Rent: 5 JD\r\nArea: 5.00 m²\r\n        ', '2025-06-12 22:57:24', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalConfirm'),
(52, 'New Flat Pending Approval', 'Approved: Your apartment has been approved by the manager and is now listed on the platform.', '2025-06-12 23:29:04', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(53, 'New Flat Pending Approval', 'Approved: Your apartment has been approved by the manager and is now listed on the platform.', '2025-06-13 00:00:07', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(54, 'Apartment Approved', 'Your apartment has been approved by the manager and is now listed on the platform.', '2025-06-13 00:55:22', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalConfirm'),
(55, 'Apartment Approved', 'Your apartment has been approved by the manager and is now listed on the platform.', '2025-06-13 01:05:39', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalConfirm'),
(56, 'New Flat Pending Approval', 'Owner #100000002 has submitted flat #ii (Pending ID: 12). Please review.', '2025-06-13 04:43:23', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(57, 'Apartment Approved', 'Your apartment has been approved by the manager and is now listed on the platform.\n\nApartment Number: ii\nMonthly Rent: 4 JD\nArea: 4.00 m²\n\n\nPending ID: 12', '2025-06-13 04:43:49', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalNotification'),
(58, 'New Flat Pending Approval', 'Owner #100000002 has submitted flat #kk (Pending ID: 13). Please review.', '2025-06-13 05:04:51', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(59, 'Apartment Approved', 'Your apartment has been approved by the manager and is now listed on the platform.', '2025-06-13 05:05:11', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalNotification'),
(60, 'New Flat Pending Approval', 'Owner #100000002 has submitted flat #dd2 (Pending ID: 14). Please review.', '2025-06-13 08:23:34', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(61, 'Apartment Approved', 'Your apartment has been approved by the manager and is now listed on the platform.', '2025-06-13 08:24:02', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalNotification'),
(62, 'New Flat Pending Approval', 'Owner #100000002 has submitted flat #pp7 (Pending ID: 15). Please review.', '2025-06-13 08:49:35', 'Owner', 100000002, 'Manager', 100001, 1, 'Approval'),
(63, 'Apartment Approved', 'Your apartment has been approved by the manager and is now listed on the platform.', '2025-06-13 08:50:05', 'Manager', 100001, 'Owner', 100000002, 0, 'ApprovalNotification'),
(64, 'Rental Approval Needed', 'Customer #100000003 requests to rent apartment #1000018 from 2025-06-20 to 2025-08-21. Total rent: $6.00. Please approve the rental.', '2025-06-13 10:44:41', 'Customer', 100000003, 'Owner', 100000002, 1, 'RentApprovalRequest'),
(65, 'Rental Approved', 'Your apartment has been approved and successfully rented. You can collect the key from the owner: Owner Two, Mobile: 0976543210.', '2025-06-13 10:45:19', 'Owner', 100000002, 'Customer', 100000003, 0, 'RentConfirm'),
(66, 'Flat Rented Notification', 'A flat has been rented!\n\nApartment #: pp7\nArea: 2.00 m²\nMonthly Rent: 2\nRental Period: 2025-06-20 to 2025-08-21\n\nOwner: Owner Two, Mobile: 0976543210\nCustomer: Customer Three, Mobile: 0777777777', '2025-06-13 10:45:19', 'Owner', 100000002, 'Manager', 100001, 1, 'RentNotification'),
(67, 'New Flat Pending Approval', 'Owner #100000009 has submitted flat #yy2 (Pending ID: 16). Please review.', '2025-06-13 17:50:05', 'Owner', 100000009, 'Manager', 100005, 1, 'Approval'),
(68, 'Apartment Approved', 'Your apartment has been approved by the manager and is now listed on the platform.', '2025-06-13 17:50:48', 'Manager', 100005, 'Owner', 100000009, 0, 'ApprovalNotification'),
(69, 'Rental Approval Needed', 'Customer #100000010 requests to rent apartment #1000008 from 2025-07-24 to 2025-10-22. Total rent: $12.00. Please approve the rental.', '2025-06-13 18:01:00', 'Customer', 100000010, 'Owner', 100000002, 0, 'RentApprovalRequest'),
(70, 'Rental Approval Needed', 'Customer #100000010 requests to rent apartment #1000018 from 2025-12-24 to 2026-02-24. Total rent: $4.00. Please approve the rental.', '2025-06-13 18:07:44', 'Customer', 100000010, 'Owner', 100000002, 1, 'RentApprovalRequest'),
(71, 'Rental Approved', 'Your apartment has been approved and successfully rented. You can collect the key from the owner: Owner Two, Mobile: 0976543210.', '2025-06-13 18:18:39', 'Owner', 100000002, 'Customer', 100000010, 0, 'RentConfirm'),
(72, 'Flat Rented Notification', 'A flat has been rented!\n\nApartment #: pp7\nArea: 2.00 m²\nMonthly Rent: 2\nRental Period: 2025-12-24 to 2026-02-24\n\nOwner: Owner Two, Mobile: 0976543210\nCustomer: Customer User, Mobile: 059333333', '2025-06-13 18:18:39', 'Owner', 100000002, 'Manager', 100001, 0, 'RentNotification'),
(73, 'Rental Approval Needed', 'Customer #100000003 requests to rent apartment #1000019 from 2025-08-19 to 2025-09-23. Total rent: $1,200.00. Please approve the rental.', '2025-06-13 18:33:14', 'Customer', 100000003, 'Owner', 100000009, 1, 'RentApprovalRequest'),
(74, 'Rental Approved', 'Your apartment has been approved and successfully rented. You can collect the key from the owner: Owner User, Mobile: 059222222.', '2025-06-13 18:34:33', 'Owner', 100000009, 'Customer', 100000003, 0, 'RentConfirm'),
(75, 'Flat Rented Notification', 'A flat has been rented!\n\nApartment #: yy2\nArea: 2.00 m²\nMonthly Rent: 600\nRental Period: 2025-08-19 to 2025-09-23\n\nOwner: Owner User, Mobile: 059222222\nCustomer: Customer Three, Mobile: 0777777777', '2025-06-13 18:34:33', 'Owner', 100000009, 'Manager', 100001, 0, 'RentNotification'),
(76, 'Rental Approval Needed', 'Customer #100000003 requests to rent apartment #1000004 from 2025-10-21 to 2026-03-24. Total rent: $2,400.00. Please approve the rental.', '2025-06-13 18:52:22', 'Customer', 100000003, 'Owner', 100000002, 1, 'RentApprovalRequest'),
(77, 'Rental Approved', 'Your apartment has been approved and successfully rented. You can collect the key from the owner: Owner Two, Mobile: 0976543210.', '2025-06-13 18:55:42', 'Owner', 100000002, 'Customer', 100000003, 0, 'RentConfirm'),
(78, 'Flat Rented Notification', 'A flat has been rented!\n\nApartment #: 44C\nArea: 600.00 m²\nMonthly Rent: 400\nRental Period: 2025-10-21 to 2026-03-24\n\nOwner: Owner Two, Mobile: 0976543210\nCustomer: Customer Three, Mobile: 0777777777', '2025-06-13 18:55:42', 'Owner', 100000002, 'Manager', 100005, 1, 'RentNotification');

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `OwnerID` bigint NOT NULL,
  `NationalID` char(9) COLLATE utf8mb4_general_ci NOT NULL,
  `OwnerName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EmailAddress` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PhoneNumber` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MobileNumber` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `BankName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `BankBranch` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `AccountNumber` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ownerImage` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Username` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`OwnerID`, `NationalID`, `OwnerName`, `EmailAddress`, `PhoneNumber`, `MobileNumber`, `Password`, `BankName`, `BankBranch`, `AccountNumber`, `ownerImage`, `Username`) VALUES
(100000001, '123456789', 'Owner One', 'owner1@example.com', '0123456789', '0987654321', 'pass1', 'Bank A', 'Branch A', '11111111', NULL, 'lauyal'),
(100000002, '987654321', 'Owner Two', 'owner2@example.com', '0234567890', '0976543210', 'custpass3', 'Bank B', 'Branch B', '22222222', NULL, 'layal1'),
(100000003, '456789123', 'Owner Three', 'owner3@example.com', '0345678901', '0965432109', 'pass3', 'Bank C', 'Branch C', '33333333', NULL, NULL),
(100000004, '456123789', 'yaser', 'abed.layal2005@gmail.com', '0599314085', '0599314052', '9layal', 'ald', 'dddcv', '123456', NULL, 'ahmad8'),
(100000007, '789456123', 'yaser', 'abed.layal2005@gmail.com', '0599314085', '0599314052', '9layal', 'ald', 'dddcv', '123456', NULL, '9layal'),
(100000008, '741852963', 'yaser', 'abed.layal2005@gmail.com', '0599314085', '0599314052', '7layal', 'ald', 'dddcv', '123456', '100000008.jpg', 'q'),
(100000009, '222222222', 'Owner User', 'owner@example.com', '026666666', '059222222', 'Owner', 'Bank A', 'Main Branch', '123456789012', 'O100000009.jpg', 'Owner');

-- --------------------------------------------------------

--
-- Table structure for table `owneraddress`
--

CREATE TABLE `owneraddress` (
  `AddressID` int NOT NULL,
  `OwnerID` bigint NOT NULL,
  `FlatOrHouseNo` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `StreetName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `City` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PostalCode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owneraddress`
--

INSERT INTO `owneraddress` (`AddressID`, `OwnerID`, `FlatOrHouseNo`, `StreetName`, `City`, `PostalCode`) VALUES
(1, 100000001, '10A', 'Main Street', 'CityOne', '10001'),
(2, 100000002, '20B', 'Second Street', 'CityTwo', '20002'),
(3, 100000003, '30C', 'Third Street', 'CityThree', '30003'),
(4, 100000004, 'f3', 'almas', 'ramallh', '40w'),
(5, 100000007, 'f3', 'almas', 'ramallh', '40w'),
(6, 100000008, 'f3', 'almas', 'ramallh', '40w'),
(7, 100000009, '7B', 'King Abdullah Street', 'Zarqa', '13110');

-- --------------------------------------------------------

--
-- Table structure for table `pendingapartmentimages`
--

CREATE TABLE `pendingapartmentimages` (
  `TempID` int NOT NULL,
  `ImageNumber` int NOT NULL,
  `ImageName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendingapartmentimages`
--

INSERT INTO `pendingapartmentimages` (`TempID`, `ImageNumber`, `ImageName`) VALUES
(6, 1, 'qw1-1.jpg'),
(6, 2, 'qw1-2.jpg'),
(6, 3, 'qw1-3.jpg'),
(10, 1, 'zz-1.jpg'),
(10, 2, 'zz-2.jpg'),
(10, 3, 'zz-3.jpg'),
(11, 1, 'pp4-1.jpg'),
(11, 2, 'pp4-2.jpg'),
(11, 3, 'pp4-3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pendingapartments`
--

CREATE TABLE `pendingapartments` (
  `TempID` int NOT NULL,
  `ApartmentNumber` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `NumberOfRooms` int DEFAULT NULL,
  `NumberOfBathrooms` int DEFAULT NULL,
  `IsFurnished` tinyint(1) DEFAULT NULL,
  `MonthlyRent` int DEFAULT NULL,
  `Area` decimal(10,2) DEFAULT NULL,
  `HeatingSystem_AirConditioning` tinyint(1) DEFAULT NULL,
  `AccessControl` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ExtraAmenities` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Description` text COLLATE utf8mb4_general_ci,
  `OwnerID` bigint DEFAULT NULL,
  `MarketingTitle` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MarketingDesc` text COLLATE utf8mb4_general_ci,
  `MarketingURL` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_marketing_info`
--

CREATE TABLE `pending_marketing_info` (
  `PendingMarketingID` int NOT NULL,
  `TempID` int NOT NULL,
  `Title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Description` text COLLATE utf8mb4_general_ci,
  `URL` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `previewbookings`
--

CREATE TABLE `previewbookings` (
  `BookingID` int NOT NULL,
  `CustomerID` bigint NOT NULL,
  `ScheduleID` int NOT NULL,
  `BookingDate` date NOT NULL,
  `Status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `previewbookings`
--

INSERT INTO `previewbookings` (`BookingID`, `CustomerID`, `ScheduleID`, `BookingDate`, `Status`) VALUES
(1, 100000003, 3, '2025-06-11', 'Pending'),
(2, 100000003, 4, '2025-06-12', 'Pending'),
(3, 100000003, 6, '2025-06-12', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `CustomerID` bigint NOT NULL,
  `ApartmentID` bigint NOT NULL,
  `RentalStartDate` date DEFAULT NULL,
  `RentalEndDate` date DEFAULT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `RentalStatus` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental`
--

INSERT INTO `rental` (`CustomerID`, `ApartmentID`, `RentalStartDate`, `RentalEndDate`, `TotalAmount`, `RentalStatus`) VALUES
(100000001, 1000001, '2024-01-01', '2024-12-23', 0.00, 'Active'),
(100000002, 1000002, '2023-06-01', '2024-12-23', 0.00, 'Active'),
(100000003, 1000001, '2025-06-21', '2025-07-31', 0.00, NULL),
(100000003, 1000002, '2025-06-26', '2025-08-14', 0.00, NULL),
(100000003, 1000004, '2025-10-21', '2026-03-24', 2400.00, NULL),
(100000003, 1000018, '2025-06-20', '2025-08-21', 0.00, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apartmentpreviewschedule`
--
ALTER TABLE `apartmentpreviewschedule`
  ADD PRIMARY KEY (`ScheduleID`),
  ADD KEY `ApartmentID` (`ApartmentID`);

--
-- Indexes for table `apartment_home`
--
ALTER TABLE `apartment_home`
  ADD PRIMARY KEY (`ApartmentID`),
  ADD KEY `OwnerID` (`OwnerID`);

--
-- Indexes for table `apartment_images`
--
ALTER TABLE `apartment_images`
  ADD PRIMARY KEY (`ApartmentID`,`ImageNumber`);

--
-- Indexes for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`CardNumber`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `NationalID` (`NationalID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `customeraddress`
--
ALTER TABLE `customeraddress`
  ADD PRIMARY KEY (`AddressID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`ManagerID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `marketing_info`
--
ALTER TABLE `marketing_info`
  ADD PRIMARY KEY (`MarketingID`),
  ADD KEY `ApartmentID` (`ApartmentID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`MessageID`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`OwnerID`),
  ADD UNIQUE KEY `NationalID` (`NationalID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `owneraddress`
--
ALTER TABLE `owneraddress`
  ADD PRIMARY KEY (`AddressID`),
  ADD KEY `OwnerID` (`OwnerID`);

--
-- Indexes for table `pendingapartmentimages`
--
ALTER TABLE `pendingapartmentimages`
  ADD PRIMARY KEY (`TempID`,`ImageNumber`);

--
-- Indexes for table `pendingapartments`
--
ALTER TABLE `pendingapartments`
  ADD PRIMARY KEY (`TempID`);

--
-- Indexes for table `pending_marketing_info`
--
ALTER TABLE `pending_marketing_info`
  ADD PRIMARY KEY (`PendingMarketingID`),
  ADD KEY `TempID` (`TempID`);

--
-- Indexes for table `previewbookings`
--
ALTER TABLE `previewbookings`
  ADD PRIMARY KEY (`BookingID`),
  ADD UNIQUE KEY `CustomerID` (`CustomerID`,`ScheduleID`),
  ADD KEY `ScheduleID` (`ScheduleID`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`CustomerID`,`ApartmentID`),
  ADD KEY `ApartmentID` (`ApartmentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apartmentpreviewschedule`
--
ALTER TABLE `apartmentpreviewschedule`
  MODIFY `ScheduleID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `apartment_home`
--
ALTER TABLE `apartment_home`
  MODIFY `ApartmentID` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000020;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000012;

--
-- AUTO_INCREMENT for table `customeraddress`
--
ALTER TABLE `customeraddress`
  MODIFY `AddressID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `ManagerID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100006;

--
-- AUTO_INCREMENT for table `marketing_info`
--
ALTER TABLE `marketing_info`
  MODIFY `MarketingID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `MessageID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `OwnerID` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000010;

--
-- AUTO_INCREMENT for table `owneraddress`
--
ALTER TABLE `owneraddress`
  MODIFY `AddressID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pendingapartments`
--
ALTER TABLE `pendingapartments`
  MODIFY `TempID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pending_marketing_info`
--
ALTER TABLE `pending_marketing_info`
  MODIFY `PendingMarketingID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `previewbookings`
--
ALTER TABLE `previewbookings`
  MODIFY `BookingID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apartmentpreviewschedule`
--
ALTER TABLE `apartmentpreviewschedule`
  ADD CONSTRAINT `apartmentpreviewschedule_ibfk_1` FOREIGN KEY (`ApartmentID`) REFERENCES `apartment_home` (`ApartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `apartment_home`
--
ALTER TABLE `apartment_home`
  ADD CONSTRAINT `apartment_home_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `owner` (`OwnerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `apartment_images`
--
ALTER TABLE `apartment_images`
  ADD CONSTRAINT `apartment_images_ibfk_1` FOREIGN KEY (`ApartmentID`) REFERENCES `apartment_home` (`ApartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD CONSTRAINT `creditcard_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customeraddress`
--
ALTER TABLE `customeraddress`
  ADD CONSTRAINT `customeraddress_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `marketing_info`
--
ALTER TABLE `marketing_info`
  ADD CONSTRAINT `marketing_info_ibfk_1` FOREIGN KEY (`ApartmentID`) REFERENCES `apartment_home` (`ApartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `owneraddress`
--
ALTER TABLE `owneraddress`
  ADD CONSTRAINT `owneraddress_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `owner` (`OwnerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pending_marketing_info`
--
ALTER TABLE `pending_marketing_info`
  ADD CONSTRAINT `pending_marketing_info_ibfk_1` FOREIGN KEY (`TempID`) REFERENCES `pendingapartments` (`TempID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `previewbookings`
--
ALTER TABLE `previewbookings`
  ADD CONSTRAINT `previewbookings_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `previewbookings_ibfk_2` FOREIGN KEY (`ScheduleID`) REFERENCES `apartmentpreviewschedule` (`ScheduleID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `rental_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rental_ibfk_2` FOREIGN KEY (`ApartmentID`) REFERENCES `apartment_home` (`ApartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
