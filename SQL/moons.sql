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
-- Table structure for table `moons`
--

CREATE TABLE `moons` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `temperature` varchar(100) DEFAULT NULL,
  `age` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `distance` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moons`
--

INSERT INTO `moons` (`id`, `name`, `image_path`, `temperature`, `age`, `description`, `distance`) VALUES
(5, 'Moon', 'uploads/Moon_1749374081.png', '127°C (Day), -173°C (Night)', '4.53 Billion Years', 'The Moon is Earth\'s only natural satellite. It orbits around Earth at an average distance of 384399 km (238,854 mi; about 30 times Earth\'s diameter). The Moon rotates, but keeps facing Earth with the same near side. This tidal locking results from Earth\'s gravitational pull having synchronized the Moon\'s rotation period (lunar day) to its orbital period (lunar month) of 29.5 Earth days. Conversely, the gravitational pull of the Moon on Earth is the main driver of Earth\'s tides.\r\n\r\nIn geophysical terms, the Moon is a planetary-mass object or satellite planet. Its mass is 1.2% that of the Earth, and its diameter is 3,474 km (2,159 mi), roughly one-quarter of Earth\'s (about as wide as the contiguous United States). Within the Solar System, it is the largest and most massive satellite in relation to its parent planet, the fifth-largest and fifth-most massive moon overall, and larger and more massive than all known dwarf planets.[17] Its surface gravity is about one-sixth of Earth\'s, about half that of Mars, and the second-highest among all moons in the Solar System, after Jupiter\'s moon Io. The body of the Moon is differentiated and terrestrial, with no significant hydrosphere, atmosphere, or magnetic field. The lunar surface is covered in lunar dust and marked by mountains, impact craters, their ejecta, ray-like streaks, rilles and, mostly on the near side of the Moon, by dark maria (\'seas\'), which are plains of cooled lava. These maria were formed when molten lava flowed into ancient impact basins. The Moon formed 4.51 billion years ago, not long after Earth\'s formation, out of the debris from a giant impact between Earth and a hypothesized Mars-sized body called Theia.\r\n\r\nThe Moon is, except when passing through Earth\'s shadow during a lunar eclipse, always illuminated by the Sun, but from Earth the visible illumination shifts during its orbit, producing the lunar phases. The Moon is the brightest celestial object in Earth\'s night sky. This is mainly due to its large angular diameter, while the reflectance of the lunar surface is comparable to that of asphalt. The apparent size is nearly the same as that of the Sun, allowing it to cover the Sun completely during a total solar eclipse. From Earth about 59% of the lunar surface is visible due to cyclical shifts in perspective (libration), making parts of the far side of the Moon visible.', '384,400 KM from Earth');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `moons`
--
ALTER TABLE `moons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `moons`
--
ALTER TABLE `moons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;