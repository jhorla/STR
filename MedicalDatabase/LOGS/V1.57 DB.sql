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

--
-- Dumping data for table `patientinfo`
--

INSERT INTO `patientinfo` (`Patient_ID`, `IDNo`, `Occupation`, `StudentGrade`, `StudentSection`, `FacultyUnit`, `Name`, `ContactNo`, `EmergencyContactName`, `EmergencyContactNo1`, `EmergencyContactNo2`) VALUES
(1, '10-2010-002', 1, 1, 1, 0, 'Gertude  Cantillo', '09825692231', '', '', ''),
(2, '11-2019-010', 1, 1, 2, 0, 'Barbara  Lopez', '09391750134', '', '', ''),
(3, '', 1, 1, 3, 0, 'Samantha  Olarte', '09748837859', '', '', ''),
(4, '', 1, 1, 1, 0, 'Crissan Magbantay', '09378595174', '', '', ''),
(5, '', 1, 1, 3, 0, 'Sierra Sulit', '09785563579', '', '', ''),
(6, '', 1, 1, 2, 0, 'Marquilla Balle', '09752157908', '', '', ''),
(7, '', 1, 1, 1, 0, 'Reya Lorete', '09670778552', '', '', ''),
(8, '', 1, 1, 3, 0, 'Edward Concepcion', '09068078631', '', '', ''),
(9, '', 1, 1, 3, 0, 'Karson Kelvin Batara', '09365671746', '', '', ''),
(10, '', 1, 1, 2, 0, 'Dasher Jay Ilaban', '09746547789', '', '', ''),
(11, '', 1, 1, 1, 0, 'Hudson Yap', '09193595810', '', '', ''),
(12, '', 1, 2, 6, 0, 'Jean Bonachita', '09786869865', '', '', ''),
(13, '', 1, 2, 6, 0, 'Estella Magdala', '09367785682', '', '', ''),
(14, '', 1, 2, 5, 0, 'Buena Ortega', '09864267854', '', '', ''),
(15, '', 1, 2, 4, 0, 'Eric Marcelo', '09237856172', '', '', ''),
(16, '', 1, 2, 4, 0, 'Gerardo Billy Amurao', '09134952046', '', '', ''),
(17, '', 1, 2, 6, 0, 'Skyler Guevarra', '09236588173', '', '', ''),
(18, '', 1, 2, 5, 0, 'Kori Digamon', '09872234456', '', '', ''),
(19, '', 1, 2, 4, 0, 'Marco Tan Gudarra', '09223456789', '', '', ''),
(20, '', 1, 3, 7, 0, 'Maria Mary  Clara ', '09758291345', '', '', ''),
(21, '', 2, NULL, NULL, 2, 'Jennifer  Perez', '09858868652', '', '', ''),
(22, '', 1, 3, 9, 0, 'Karen Villanueva', '09878673432', '', '', ''),
(23, '', 1, 3, 9, 0, 'Edenia Marquez', '09347683471', '', '', ''),
(24, '', 1, 3, 8, 0, 'Briana Felipe', '09998864356', '', '', ''),
(25, '', 1, 3, 8, 0, 'Ignazio Matapang', '09876476812', '', '', ''),
(26, '', 1, 3, 8, 0, 'Roldan Saiden', '09283672145', '', '', ''),
(27, '', 1, 3, 9, 0, 'Moises Cruz', '09786278613', '', '', ''),
(28, '', 1, 3, 9, 0, 'Javiero Alcazar', '09008224567', '', '', ''),
(29, '', 1, 3, 9, 0, 'Jayce Estrella', '09000274614', '', '', ''),
(30, '', 1, 4, 11, 0, 'Jennie Jen  Kim', '09998543213', '', '', ''),
(31, '', 1, 4, 12, 0, 'Susan  Velasquez', '09990875431', '', '', ''),
(32, '', 1, 4, 10, 0, 'Iggy Piaoan', '09124785932', '', '', ''),
(33, '', 1, 4, 10, 0, 'Jana Ray', '09864315674', '', '', ''),
(34, '', 1, 4, 12, 0, 'Conner Magalong', '09073486914', '', '', ''),
(35, '', 1, 4, 12, 0, 'Ismael Adelio Salumbidez', '09888817345', '', '', ''),
(36, '', 1, 5, 13, 0, 'Jill  Pilapil', '09785334567', '', '', ''),
(37, '', 1, 5, 14, 0, 'Rose  Yoshikawa', '09472568461', '', '', ''),
(38, '', 1, 5, 15, 0, 'Lesley Evangelista', '09867387313', '', '', ''),
(39, '', 1, 5, 13, 0, 'Cristiano Mineque', '09862386325', '', '', ''),
(40, '', 1, 5, 13, 0, 'Ryley Dallas Carandang', '09998888931', '', '', ''),
(41, '', 1, 5, 14, 0, 'Chengco Ancheta', '09086222334', '', '', ''),
(42, '', 1, 6, 19, 0, 'Julian Naga', '09865494032', '', '', ''),
(43, '', 1, 6, 20, 0, 'Maria Hwasa Agbayani', '09857389245', '', '', ''),
(44, '', 1, 6, 21, 0, 'Solar  Arai', '09345862456', '', '', ''),
(45, '', 1, 6, 19, 0, 'Leo Forteza', '09165367113', '', '', ''),
(46, '', 1, 6, 20, 0, 'Aries Dulnuan', '09357138471', '', '', ''),
(47, '', 1, 6, 21, 0, 'Camron Casan', '09327868184', '', '', ''),
(48, '', 1, 6, 19, 0, 'Augustus Aden Amer', '09856746874', '', '', ''),
(49, '', 1, 6, 20, 0, 'Chayo George Paloma', '09444557245', '', '', ''),
(50, '', 1, 6, 21, 0, 'Amadeo Elvin Umali', '09888877773', '', '', ''),
(51, '', 2, 0, 0, 0, 'Johhny Smith', '09863468919', '', '', ''),
(52, '', 2, 0, 0, 3, 'Georgia Jennifer  Smith ', '09672783815', '', '', ''),
(53, '', 2, 0, 0, 0, 'Alexander  Hamilton ', '09218947878', '', '', ''),
(54, '', 2, 0, 0, 0, 'Eliza  Hamilton ', '09178464787', '', '', ''),
(55, '', 2, 0, 0, 0, 'Angelica  Schuyler ', '09542681837', '', '', ''),
(56, '', 2, 0, 0, 0, 'Peggy  Schuyler ', '09767482678', '', '', ''),
(57, '', 2, 0, 0, 0, 'Sydney  Sweeney ', '09412147541', '', '', ''),
(58, '', 2, 0, 0, 0, 'Renee  Rap ', '09655211175', '', '', ''),
(59, '', 2, 0, 0, 0, 'Troye  Sivan ', '09251235148', '', '', ''),
(60, '', 2, 0, 0, 0, 'Timothee  Chalamet', '09355874121', '', '', ''),
(61, '', 2, 0, 0, 0, 'Cate  Blanchett ', '09999994127', '', '', ''),
(62, '', 2, 0, 0, 0, 'Sandra  Bullock', '09783791471', '', '', ''),
(63, '', 2, 0, 0, 0, 'Scarlett  Johansson ', '09551651238', '', '', ''),
(64, '', 2, 0, 0, 0, 'Changbin  Seo ', '09651128789', '', '', ''),
(65, '', 2, 0, 0, 0, 'James  Hetfield ', '09653321589', '', '', ''),
(66, '', 2, 0, 0, 0, 'Jungkook  Jeon ', '09632587258', '', '', ''),
(67, '', 2, 0, 0, 0, 'Josh  Hutcherson ', '09658785365', '', '', ''),
(68, '', 2, 0, 0, 0, 'Amy  Schumer ', '09865234879', '', '', ''),
(69, '', 2, 0, 0, 0, 'James  Corden ', '09653287462', '', '', ''),
(70, '', 2, 0, 0, 0, 'Ellen  Degeneres ', '09658745231', '', '', ''),
(71, '', 2, 0, 0, 0, 'Dory  Fish ', '09193595910', '', '', ''),
(72, '', 2, 0, 0, 0, 'Steven  Universe ', '09383659128', '', '', ''),
(73, '', 2, 0, 0, 0, 'Giovannia  Allawan ', '09653257593', '', '', ''),
(74, '', 2, 0, 0, 0, 'Leonardo  Da Vinci ', '09665566246', '', '', ''),
(75, '', 2, 0, 0, 0, 'Leonardo  Dicaprio ', '09687452301', '', '', ''),
(76, '', 2, 0, 0, 0, 'Leonardo  Turtle ', '09878756321', '', '', ''),
(77, '', 2, 0, 0, 0, 'Kim  Kardashian ', '09879879875', '', '', ''),
(78, '', 2, 0, 0, 0, 'Kendall  Jenner ', '09856321896', '', '', ''),
(79, '', 2, 0, 0, 0, 'Khloe Kardashian ', '09874632598', '', '', ''),
(80, '', 2, 0, 0, 0, 'Kylie Jenner ', '09887887788', '', '', ''),
(81, '', 2, 0, 0, 0, 'Kourtney Kardashian ', '09632543887', '', '', ''),
(82, '', 2, 0, 0, 0, 'Kris Kardashian ', '09856217992', '', '', ''),
(83, '', 2, 0, 0, 0, 'Master Oogway', '09632321713', '', '', ''),
(84, '', 2, 0, 0, 0, 'Po Panda', '09254789315', '', '', ''),
(85, '', 2, 0, 0, 0, 'Pou Sivan ', '09111211236', '', '', ''),
(86, '', 2, 0, 0, 0, 'Lady Gertude ', '09989892398', '', '', ''),
(87, '', 2, 0, 0, 0, 'Brie  Larson', '09744554426', '', '', ''),
(88, '', 2, 0, 0, 0, 'Vivian  Pollux', '09656565267', '', '', ''),
(89, '', 2, 0, 0, 0, 'Henry Cavil', '09621133325', '', '', ''),
(90, '', 2, 0, 0, 0, 'Nayeon Im', '09665299823', '', '', ''),
(91, '', 2, 0, 0, 0, 'Jihyo Park ', '09989995593', '', '', ''),
(92, '', 2, 0, 0, 0, 'Sunghoon Park ', '09655238712', '', '', ''),
(93, '', 2, 0, 0, 0, 'Itto  Arataki', '09866541327', '', '', ''),
(94, '', 2, 0, 0, 0, 'Hesus  Kristo ', '09856216589', '', '', ''),
(95, '', 2, 0, 0, 0, 'Mary Juana ', '09315677423', '', '', ''),
(96, '', 2, 0, 0, 0, 'Jay  Park ', '09869869862', '', '', ''),
(97, '', 2, 0, 0, 0, 'Ru Paul ', '09565875874', '', '', ''),
(98, '', 2, 0, 0, 0, 'Trixie  Mattel ', '09621578413', '', '', ''),
(99, '', 2, 0, 0, 0, 'Timmy  Turner', '09874521462', '', '', ''),
(100, '', 2, 0, 0, 0, 'Jose  Rizal', '09733333333', '', '', ''),
(101, '', 2, NULL, 0, 2, 'CID TEST', '', '', '', '');

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
