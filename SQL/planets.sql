-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 01:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `planetarium_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `planets`
--

CREATE TABLE `planets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `temperature` varchar(100) DEFAULT NULL,
  `age` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `distance` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planets`
--

INSERT INTO `planets` (`id`, `name`, `image_path`, `temperature`, `age`, `description`, `distance`) VALUES
(9, 'Earth', 'uploads/Earth_1749307681.png', '15°C (Average)', '4.54 Billion Years', 'Home Sweet Home!\r\n\r\nEarth is the third planet from the Sun and the only astronomical object known to harbor life. This is enabled by Earth being an ocean world, the only one in the Solar System sustaining liquid surface water. Almost all of Earth\'s water is contained in its global ocean, covering 70.8% of Earth\'s crust. The remaining 29.2% of Earth\'s crust is land, most of which is located in the form of continental landmasses within Earth\'s land hemisphere. Most of Earth\'s land is at least somewhat humid and covered by vegetation, while large sheets of ice at Earth\'s polar deserts retain more water than Earth\'s groundwater, lakes, rivers, and atmospheric water combined. Earth\'s crust consists of slowly moving tectonic plates, which interact to produce mountain ranges, volcanoes, and earthquakes. Earth has a liquid outer core that generates a magnetosphere capable of deflecting most of the destructive solar winds and cosmic radiation.\r\n\r\nEarth has a dynamic atmosphere, which sustains Earth\'s surface conditions and protects it from most meteoroids and UV-light at entry. It has a composition of primarily nitrogen and oxygen. Water vapor is widely present in the atmosphere, forming clouds that cover most of the planet. The water vapor acts as a greenhouse gas and, together with other greenhouse gases in the atmosphere, particularly carbon dioxide (CO2), creates the conditions for both liquid surface water and water vapor to persist via the capturing of energy from the Sun\'s light. This process maintains the current average surface temperature of 14.76 °C (58.57 °F), at which water is liquid under normal atmospheric pressure. Differences in the amount of captured energy between geographic regions (as with the equatorial region receiving more sunlight than the polar regions) drive atmospheric and ocean currents, producing a global climate system with different climate regions, and a range of weather phenomena such as precipitation, allowing components such as nitrogen to cycle.', 'Nil'),
(10, 'Saturn', 'uploads/Saturn_1749307989.png', '-178°C (Average)', '4.5 Billion Years', 'Saturn is the sixth planet from the Sun and the second largest in the Solar System, after Jupiter. It is a gas giant, with an average radius of about 9 times that of Earth. It has an eighth the average density of Earth, but is over 95 times more massive. Even though Saturn is almost as big as Jupiter, Saturn has less than a third its mass. Saturn orbits the Sun at a distance of 9.59 AU (1,434 million km), with an orbital period of 29.45 years.\r\n\r\nSaturn\'s interior is thought to be composed of a rocky core, surrounded by a deep layer of metallic hydrogen, an intermediate layer of liquid hydrogen and liquid helium, and an outer layer of gas. Saturn has a pale yellow hue, due to ammonia crystals in its upper atmosphere. An electrical current in the metallic hydrogen layer is thought to give rise to Saturn\'s planetary magnetic field, which is weaker than Earth\'s, but has a magnetic moment 580 times that of Earth because of Saturn\'s greater size. Saturn\'s magnetic field strength is about a twentieth that of Jupiter.[27] The outer atmosphere is generally bland and lacking in contrast, although long-lived features can appear. Wind speeds on Saturn can reach 1,800 kilometres per hour (1,100 miles per hour).\r\n\r\nThe planet has a bright and extensive system of rings, composed mainly of ice particles, with a smaller amount of rocky debris and dust. At least 274 moons orbit the planet, of which 63 are officially named; these do not include the hundreds of moonlets in the rings. Titan, Saturn\'s largest moon and the second largest in the Solar System, is larger (but less massive) than the planet Mercury and is the only moon in the Solar System that has a substantial atmosphere.[28]', '1.4 Billion KM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `planets`
--
ALTER TABLE `planets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `planets`
--
ALTER TABLE `planets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;