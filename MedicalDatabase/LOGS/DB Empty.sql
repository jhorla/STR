-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2024 at 08:30 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `patientdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `CheckIn_ID` int(11) NOT NULL,
  `Patient_ID` int(11) NOT NULL,
  `CheckInTime` text DEFAULT NULL,
  `CheckInDate` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeesunits`
--

CREATE TABLE `employeesunits` (
  `Unit_ID` int(11) NOT NULL,
  `Occupation_ID` int(11) NOT NULL,
  `Unit` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employeesunits`
--

INSERT INTO `employeesunits` (`Unit_ID`, `Occupation_ID`, `Unit`) VALUES
(0, 2, 'None'),
(1, 2, 'OCD'),
(2, 2, 'CID'),
(3, 2, 'FAD'),
(4, 2, 'SSD'),
(5, 2, 'Teaching Staff'),
(6, 2, 'Non-Teaching Staff'),
(7, 2, 'JO');

-- --------------------------------------------------------

--
-- Table structure for table `occupations`
--

CREATE TABLE `occupations` (
  `Occupation_ID` int(11) NOT NULL,
  `Occupation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `occupations`
--

INSERT INTO `occupations` (`Occupation_ID`, `Occupation`) VALUES
(1, 'Student'),
(2, 'Faculty');

-- --------------------------------------------------------

--
-- Table structure for table `patientinfo`
--

CREATE TABLE `patientinfo` (
  `Patient_ID` int(11) NOT NULL,
  `IDNo` text NOT NULL,
  `Occupation` int(11) NOT NULL,
  `StudentGrade` int(11) DEFAULT 0,
  `StudentSection` int(11) DEFAULT 0,
  `FacultyUnit` int(11) DEFAULT 0,
  `Name` text NOT NULL,
  `ContactNo` text NOT NULL,
  `EmergencyContactName` text NOT NULL,
  `EmergencyContactNo1` text NOT NULL,
  `EmergencyContactNo2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentgrades`
--

CREATE TABLE `studentgrades` (
  `Grade_ID` int(11) NOT NULL,
  `Occupation_ID` int(11) NOT NULL,
  `Grade` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentgrades`
--

INSERT INTO `studentgrades` (`Grade_ID`, `Occupation_ID`, `Grade`) VALUES
(0, 1, 'None'),
(1, 1, '7'),
(2, 1, '8'),
(3, 1, '9'),
(4, 1, '10'),
(5, 1, '11'),
(6, 1, '12');

-- --------------------------------------------------------

--
-- Table structure for table `studentsections`
--

CREATE TABLE `studentsections` (
  `Section_ID` int(11) NOT NULL,
  `Occupation_ID` int(11) NOT NULL,
  `Grade_ID` int(11) NOT NULL,
  `Section` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentsections`
--

INSERT INTO `studentsections` (`Section_ID`, `Occupation_ID`, `Grade_ID`, `Section`) VALUES
(0, 1, 0, 'None'),
(1, 1, 1, 'Diamond'),
(2, 1, 1, 'Jade'),
(3, 1, 1, 'Ruby'),
(4, 1, 2, 'Adelfa'),
(5, 1, 2, 'Camia'),
(6, 1, 2, 'Sampaguita'),
(7, 1, 3, 'Beryllium'),
(8, 1, 3, 'Helium'),
(9, 1, 3, 'Lithium'),
(10, 1, 4, 'Gluon'),
(11, 1, 4, 'Graviton'),
(12, 1, 4, 'Photon'),
(13, 1, 5, 'A'),
(14, 1, 5, 'B'),
(15, 1, 5, 'C'),
(16, 1, 5, 'D'),
(17, 1, 5, 'E'),
(18, 1, 5, 'F'),
(19, 1, 6, 'A'),
(20, 1, 6, 'B'),
(21, 1, 6, 'C'),
(22, 1, 6, 'D'),
(23, 1, 6, 'E'),
(24, 1, 6, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `vitalsigns`
--

CREATE TABLE `vitalsigns` (
  `VitalSigns_ID` int(11) NOT NULL,
  `Patient_ID` int(11) NOT NULL,
  `CheckIn_ID` int(11) NOT NULL,
  `BloodPressure` text NOT NULL,
  `Temperature` text NOT NULL,
  `OxygenSaturation` text NOT NULL,
  `PulseRate` text NOT NULL,
  `DocsNote` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`CheckIn_ID`);

--
-- Indexes for table `employeesunits`
--
ALTER TABLE `employeesunits`
  ADD PRIMARY KEY (`Unit_ID`);

--
-- Indexes for table `occupations`
--
ALTER TABLE `occupations`
  ADD PRIMARY KEY (`Occupation_ID`);

--
-- Indexes for table `patientinfo`
--
ALTER TABLE `patientinfo`
  ADD PRIMARY KEY (`Patient_ID`);

--
-- Indexes for table `studentgrades`
--
ALTER TABLE `studentgrades`
  ADD PRIMARY KEY (`Grade_ID`),
  ADD KEY `Occupation_ID` (`Occupation_ID`);

--
-- Indexes for table `studentsections`
--
ALTER TABLE `studentsections`
  ADD PRIMARY KEY (`Section_ID`),
  ADD KEY `Grade_ID` (`Grade_ID`),
  ADD KEY `Occupation_ID` (`Occupation_ID`);

--
-- Indexes for table `vitalsigns`
--
ALTER TABLE `vitalsigns`
  ADD PRIMARY KEY (`VitalSigns_ID`),
  ADD KEY `CheckIn_ID` (`CheckIn_ID`),
  ADD KEY `Patient_ID` (`Patient_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `CheckIn_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employeesunits`
--
ALTER TABLE `employeesunits`
  MODIFY `Unit_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `occupations`
--
ALTER TABLE `occupations`
  MODIFY `Occupation_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patientinfo`
--
ALTER TABLE `patientinfo`
  MODIFY `Patient_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `studentgrades`
--
ALTER TABLE `studentgrades`
  MODIFY `Grade_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `studentsections`
--
ALTER TABLE `studentsections`
  MODIFY `Section_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `vitalsigns`
--
ALTER TABLE `vitalsigns`
  MODIFY `VitalSigns_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
