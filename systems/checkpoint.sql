SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Database: `checkpoint`
-- --------------------------------------------------------

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username varchar(100) NOT NULL,
    tripid INT(10),
    longitude varchar(97),
    latitude varchar(97),
    type varchar(97),
    date DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE absen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    longitude VARCHAR(97),
    latitude VARCHAR(97),
    date DATETIME,
    proof LONGBLOB
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trips INT(10),
    username varchar(100) NOT NULL,
    password varchar(150) NOT NULL,
    fullname varchar(100) NOT NULL,
    type varchar(100) NOT NULL
    phone varchar(100) NOT NULL,
    email varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
