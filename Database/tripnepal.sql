-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3310
-- Generation Time: Oct 12, 2025 at 02:16 PM
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
-- Database: `tripnepal`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity`, `description`, `main_image`) VALUES
('Hiking', 'Hiking is a refreshing outdoor adventure that lets you explore nature’s beauty on foot — from peaceful forest trails to breathtaking mountain paths. Perfect for anyone who loves fresh air, scenic views, and a sense of freedom.', 'assets/activity/activity_68ea05bc509f86.37363032.jpg'),
('Tour', 'Nepal, a country of breathtaking landscapes and rich cultural heritage, is one of the best travel destinations in the world. Whether you\'re an adventure seeker, nature lover, or cultural explorer, Nepal offers an unforgettable experience. From the towering Himalayas to ancient temples, serene lakes, and vibrant cities, a tour in Nepal has something for everyone.', 'assets/activity/activity_68ea0894028b62.42214936.jpeg'),
('Trekking', 'Nepal is a trekker\'s paradise, home to the world’s highest peaks, including Mount Everest, and a vast network of trails that offer breathtaking landscapes, rich cultural experiences, and thrilling adventures. Whether you’re a seasoned mountaineer or a beginner seeking a scenic walk through the hills, Nepal has something for everyone.', 'assets/activity/activity_68ea060d9f2f61.57369017.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departure`
--

CREATE TABLE `departure` (
  `departure_id` int(11) NOT NULL,
  `trip_id` int(11) DEFAULT NULL,
  `departure` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `distination` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`distination`, `description`, `main_image`) VALUES
('Chitwan', 'Chitwan, located in the Terai region of Nepal, is famous for its rich biodiversity, dense forests, and vibrant Tharu culture. It is home to Chitwan National Park, Nepal’s first national park and a UNESCO World Heritage Site, making it a top destination for wildlife lovers and nature enthusiasts.Chitwan National Park is one of the best places in Asia for wildlife safaris. It covers 932 sq. km of lush forests, grasslands, and rivers, providing a habitat for rare and endangered species such as: One-horned rhinoceros 🦏 (one of the main attractions) Royal Bengal tiger 🐅 Asian elephant 🐘 Gharial crocodile 🐊 Over 500 bird species 🦜 ', 'uploads/destinations/67f39788cab4a1.16822939.jpg'),
('Kathmandu', 'Kathmandu, the capital city of Nepal, is a vibrant blend of ancient culture, rich history, and modern urban life. Nestled in a valley surrounded by the Himalayas, it is the largest city in the country and serves as its political, cultural, and economic hub. Kathmandu is home to several UNESCO World Heritage Sites, including the iconic Swayambhunath Stupa (Monkey Temple), the sacred Pashupatinath Temple, and the historic Kathmandu Durbar Square. These landmarks showcase the city\'s deep-rooted Hindu and Buddhist traditions, along with stunning Newar architecture.As the gateway to Nepal’s trekking destinations, Kathmandu is a hotspot for travelers. Thamel, a bustling tourist district, is filled with lively streets, shops, cafes, and trekking agencies. Visitors often start their Himalayan adventures here before heading to Everest Base Camp, Annapurna, or Langtang.Kathmandu offers a mix of traditional and modern lifestyles. Local markets like Ason and Indra Chowk are famous for their spices, textiles, and street food, while the city\'s restaurants serve everything from Newari cuisine (Yomari, Bara, Choila) to international dishes.', 'uploads/destinations/67f3974d915df3.45654471.jpg'),
('Lumbini', 'Lumbini, located in the Rupandehi district of Nepal, is one of the most sacred pilgrimage sites in the world. It is the birthplace of Siddhartha Gautama (Lord Buddha) and a UNESCO World Heritage Site. This peaceful and spiritual destination attracts Buddhists and travelers from all over the world.At the center of Lumbini lies the Maya Devi Temple, marking the exact spot where Queen Maya Devi gave birth to Prince Siddhartha in 563 BCE. Inside the temple, visitors can see an ancient stone marker and the remains of old structures from Buddha’s time.Emperor Ashoka of India, a great follower of Buddhism, visited Lumbini in 249 BCE and erected the Ashoka Pillar with an inscription confirming it as Buddha’s birthplace. This historical pillar stands as evidence of Lumbini’s authenticity.', 'uploads/destinations/67f397a18f6542.04774964.jpg'),
('Mustang', 'Mustang, often referred to as \'The Last Forbidden Kingdom,\' is one of Nepal\'s most mystical and scenic regions. Located in the northwestern Himalayas, Mustang is divided into Upper Mustang and Lower Mustang, each offering unique landscapes, rich culture, and historical significance.Mustang’s geography is unlike any other place in Nepal. The region is characterized by: Arid desert-like terrain with deep gorges and rock formations. Snow-capped peaks of the Annapurna and Dhaulagiri ranges. Kali Gandaki Gorge, the world’s deepest gorge.Mustang has a deep-rooted Tibetan Buddhist culture, reflected in its ancient monasteries, chortens, and caves. The region was once an independent Tibetan kingdom, and its influence is still evident today.', 'uploads/destinations/67f397daa28282.58886889.jpg'),
('Pokhara', 'Pokhara, often called the \'Tourist Capital of Nepal,\' is a breathtaking city known for its stunning natural beauty, adventure activities, and peaceful atmosphere. Located about 200 km west of Kathmandu, it is Nepal\'s second-largest city and a gateway to the Annapurna region. Pokhara is famous for its serene lakes, the most iconic being Phewa Lake, where visitors can enjoy boating with the beautiful reflection of Mt. Machhapuchhre (Fishtail) on the water. Other lakes like Begnas Lake and Rupa Lake offer a more peaceful escape into nature.Pokhara is the starting point for some of Nepal’s most famous trekking routes, including the Annapurna Circuit and Poon Hill Trek. It’s also a paradise for adventure lovers, offering paragliding, ultra-light flights, zip-lining, and bungee jumping with stunning Himalayan views.', 'uploads/destinations/67f397790929c2.39189378.jpg'),
('Solukhumbu', 'Solukhumbu, located in eastern Nepal, is one of the most iconic regions of the country. It is home to Mount Everest (Sagarmatha), the world&#039;s highest peak, and is a major destination for trekkers, mountaineers, and adventure seekers. The district is part of the Sagarmatha National Park, a UNESCO World Heritage Site known for its stunning landscapes, glaciers, and unique Sherpa culture.Major Attractions in Solukhumbu Mount Everest (8,848.86m) 🏔️ – The tallest mountain on Earth, attracting climbers from all over the world. Everest Base Camp (EBC) ⛺ – A bucket-list trekking destination that offers breathtaking views of Everest, Lhotse, and Nuptse. Namche Bazaar 🏡 – The vibrant trading hub and gateway to Everest, filled with cafes, lodges, and markets. Tengboche Monastery 🛕 – The largest monastery in the Khumbu region, offering spiritual significance and stunning Himalayan views. Gokyo Lakes 🌊 – A series of stunning turquoise glacial lakes, providing a peaceful and scenic alternative to the EBC trek. Solukhumbu is home to the Sherpa community, known for their mountaineering skills and hospitality. Visitors can experience: Traditional Sherpa villages like Khumjung and Phakding. Mani walls, chortens, and prayer flags that reflect Tibetan Buddhist traditions. Warm hospitality in tea houses and lodges along trekking routes. Trekking &amp; Adventure Everest Base Camp Trek 🥾 – A 12-14 day journey through breathtaking landscapes. Three Passes Trek ⛰️ – A challenging route crossing Kongma La, Cho La, and Renjo La. Gokyo Ri Trek 🏞️ – A stunning trek to panoramic viewpoints of Everest and the Gokyo Lakes.', 'uploads/destinations/67f397c320d8a1.52098330.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `itinerary`
--

CREATE TABLE `itinerary` (
  `itinerary_id` int(11) NOT NULL,
  `tripid` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `teamid` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `speciality` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `facebookid` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `tripid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `transportation` varchar(100) DEFAULT NULL,
  `accomodation` varchar(100) DEFAULT NULL,
  `maximumaltitude` varchar(20) DEFAULT NULL,
  `departurefrom` varchar(50) DEFAULT NULL,
  `bestseason` varchar(50) DEFAULT NULL,
  `triptype` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meals` varchar(100) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `fitnesslevel` varchar(30) DEFAULT NULL,
  `groupsize` varchar(11) DEFAULT NULL,
  `minimumage` int(11) DEFAULT NULL,
  `maximumage` int(11) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `activity` varchar(30) DEFAULT NULL,
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`tripid`, `title`, `price`, `transportation`, `accomodation`, `maximumaltitude`, `departurefrom`, `bestseason`, `triptype`, `meals`, `language`, `fitnesslevel`, `groupsize`, `minimumage`, `maximumage`, `description`, `location`, `duration`, `activity`, `views`) VALUES
(10, 'Swyambhu', 3000.00, 'Bus', 'full', '500', 'Kathmandu', 'Spring', '0', 'full course', 'nepali, English', 'Beginner', '2', 1, 70, 'Kathmandu, the capital city of Nepal, is a vibrant blend of ancient culture, rich history, and modern urban life. Nestled in a valley surrounded by the Himalayas, it is the largest city in the country and serves as its political, cultural, and economic hub. Kathmandu is home to several UNESCO World Heritage Sites, including the iconic Swayambhunath Stupa (Monkey Temple), the sacred Pashupatinath Temple, and the historic Kathmandu Durbar Square. These landmarks showcase the city\'s deep-rooted Hin', 'Kathmandu', '2', 'Hiking', 0),
(11, 'pokhara', 6000.00, 'Bus', 'full', '3000', 'Kathmandu', 'Summer', '0', 'full course', 'nepali, English', 'Beginner', '2-6', 2, 70, 'Pokhara, often called the \'Tourist Capital of Nepal,\' is a breathtaking city known for its stunning natural beauty, adventure activities, and peaceful atmosphere. Located about 200 km west of Kathmandu, it is Nepal\'s second-largest city and a gateway to the Annapurna region. Pokhara is famous for its serene lakes, the most iconic being Phewa Lake, where visitors can enjoy boating with the beautiful reflection of Mt. Machhapuchhre (Fishtail) on the water. Other lakes like Begnas Lake and Rupa Lak', 'Pokhara', '5', 'Tour', 0);

-- --------------------------------------------------------

--
-- Table structure for table `triptypes`
--

CREATE TABLE `triptypes` (
  `triptype` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `triptypes`
--

INSERT INTO `triptypes` (`triptype`, `description`, `main_image`) VALUES
('Budget Friendly', 'Nepal is one of the most affordable travel destinations in the world, offering breathtaking landscapes, rich cultural experiences, and thrilling adventures at a low cost. Whether you are a backpacker or a budget-conscious traveler, Nepal provides plenty of opportunities to explore without breaking the bank. From trekking in the Himalayas to experiencing local culture in vibrant cities, budget-friendly trips in Nepal are both exciting and affordable.', 'assets/triptype/68ea13016dd5d_pokhara.jpg'),
('Cultural', 'Nepal is a land of rich cultural heritage, where ancient traditions, diverse ethnic groups, and spiritual beliefs coexist harmoniously. With influences from both Hinduism and Buddhism, Nepal&#039;s culture is deeply rooted in its festivals, arts, music, dance, architecture, and way of life. The country is home to more than 120 ethnic groups and over 100 languages, making it a unique cultural melting pot.', 'assets/triptype/68ea161e4b686_67f39d0f396767.45024494.jpg'),
('Nature Friendly', 'Nepal, home to stunning mountains, lush forests, and diverse wildlife, is a paradise for eco-conscious travelers. Nature-friendly trips in Nepal focus on sustainable tourism, preserving the environment, supporting local communities, and minimizing the impact on nature. Whether trekking in the Himalayas, exploring national parks, or staying in eco-lodges, Nepal offers numerous eco-friendly travel experiences.', 'assets/triptype/68ea15f7777ec_nature.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `trip_bookings`
--

CREATE TABLE `trip_bookings` (
  `id` int(11) NOT NULL,
  `user_id` varchar(40) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `trip_name` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `adults` int(11) DEFAULT NULL,
  `children` int(11) DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `airport_pickup` enum('yes','no') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `payment_mode` varchar(50) DEFAULT NULL,
  `payment_status` enum('paid','not paid') DEFAULT 'not paid',
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `trip_details_view`
-- (See below for the actual view)
--
CREATE TABLE `trip_details_view` (
`tripid` int(11)
,`title` varchar(100)
,`price` decimal(10,2)
,`transportation` varchar(100)
,`accomodation` varchar(100)
,`maximumaltitude` varchar(20)
,`departurefrom` varchar(50)
,`bestseason` varchar(50)
,`triptype` varchar(100)
,`meals` varchar(100)
,`language` varchar(50)
,`fitnesslevel` varchar(30)
,`groupsize` varchar(11)
,`minimumage` int(11)
,`maximumage` int(11)
,`description` varchar(500)
,`location` varchar(255)
,`duration` varchar(255)
,`main_image` varchar(255)
,`side_image1` varchar(255)
,`side_image2` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `trip_images`
--

CREATE TABLE `trip_images` (
  `imgid` int(11) NOT NULL,
  `tripid` int(11) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `side_image1` varchar(255) DEFAULT NULL,
  `side_image2` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trip_images`
--

INSERT INTO `trip_images` (`imgid`, `tripid`, `main_image`, `side_image1`, `side_image2`, `uploaded_at`) VALUES
(8, 10, 'assets/trips/68eb7123e10985.00562902.jpg', 'assets/trips/68eb7123e14775.97958054.jpg', 'assets/trips/68eb7123e17426.62432507.jpg', '2025-10-12 09:13:07'),
(9, 11, 'assets/trips/68eb74dacd25e3.02468984.jpg', 'assets/trips/68eb74dacd5304.87885606.jpg', 'assets/trips/68eb74dacd7209.07653363.jpg', '2025-10-12 09:28:58');

-- --------------------------------------------------------

--
-- Table structure for table `trip_overviews`
--

CREATE TABLE `trip_overviews` (
  `overviewid` int(11) NOT NULL,
  `tripid` int(11) NOT NULL,
  `overview` text NOT NULL,
  `highlight1` varchar(100) DEFAULT NULL,
  `highlight2` varchar(100) DEFAULT NULL,
  `highlight3` varchar(100) DEFAULT NULL,
  `highlight4` varchar(100) DEFAULT NULL,
  `highlight5` varchar(100) DEFAULT NULL,
  `highlight6` varchar(100) DEFAULT NULL,
  `highlight7` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` varchar(50) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip_postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profilepic` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'inactive',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `phone_number`, `address`, `zip_postal_code`, `country`, `first_name`, `last_name`, `user_name`, `email`, `password`, `profilepic`, `status`, `reset_token`, `reset_expires`) VALUES
('user_67ee80c6e374b', '8989898989', 'Kathmandu', '45600', 'Nepal', 'Anjila', 'Tamang', 'anjila', 'anjila@gmail.com', '$2y$10$qmI0/BQfWub9oDv.C/4wO.ypHQm6EN3iLPlODEeV2hyYl3tlE044K', NULL, 'active', '1bfc87fad380f7bcf5f8b95f16ef3018cf5e994d737e86629d5bb64a7994d9fb', '2025-10-12 15:09:19');

-- --------------------------------------------------------

--
-- Structure for view `trip_details_view`
--
DROP TABLE IF EXISTS `trip_details_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `trip_details_view`  AS SELECT `t`.`tripid` AS `tripid`, `t`.`title` AS `title`, `t`.`price` AS `price`, `t`.`transportation` AS `transportation`, `t`.`accomodation` AS `accomodation`, `t`.`maximumaltitude` AS `maximumaltitude`, `t`.`departurefrom` AS `departurefrom`, `t`.`bestseason` AS `bestseason`, `t`.`triptype` AS `triptype`, `t`.`meals` AS `meals`, `t`.`language` AS `language`, `t`.`fitnesslevel` AS `fitnesslevel`, `t`.`groupsize` AS `groupsize`, `t`.`minimumage` AS `minimumage`, `t`.`maximumage` AS `maximumage`, `t`.`description` AS `description`, `t`.`location` AS `location`, `t`.`duration` AS `duration`, `i`.`main_image` AS `main_image`, `i`.`side_image1` AS `side_image1`, `i`.`side_image2` AS `side_image2` FROM (`trips` `t` left join `trip_images` `i` on(`t`.`tripid` = `i`.`tripid`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity`),
  ADD UNIQUE KEY `activity` (`activity`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `departure`
--
ALTER TABLE `departure`
  ADD PRIMARY KEY (`departure_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`distination`),
  ADD UNIQUE KEY `distination` (`distination`);

--
-- Indexes for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD PRIMARY KEY (`itinerary_id`),
  ADD UNIQUE KEY `tripid` (`tripid`,`day_number`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`teamid`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`tripid`),
  ADD KEY `fk_activity` (`activity`),
  ADD KEY `fk_location` (`location`),
  ADD KEY `fk_triptype` (`triptype`);

--
-- Indexes for table `triptypes`
--
ALTER TABLE `triptypes`
  ADD PRIMARY KEY (`triptype`),
  ADD UNIQUE KEY `triptype` (`triptype`),
  ADD UNIQUE KEY `unique_triptype` (`triptype`);

--
-- Indexes for table `trip_bookings`
--
ALTER TABLE `trip_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `trip_images`
--
ALTER TABLE `trip_images`
  ADD PRIMARY KEY (`imgid`),
  ADD KEY `tripid` (`tripid`);

--
-- Indexes for table `trip_overviews`
--
ALTER TABLE `trip_overviews`
  ADD PRIMARY KEY (`overviewid`),
  ADD KEY `tripid` (`tripid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departure`
--
ALTER TABLE `departure`
  MODIFY `departure_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `itinerary`
--
ALTER TABLE `itinerary`
  MODIFY `itinerary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `teamid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `tripid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `trip_bookings`
--
ALTER TABLE `trip_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `trip_images`
--
ALTER TABLE `trip_images`
  MODIFY `imgid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trip_overviews`
--
ALTER TABLE `trip_overviews`
  MODIFY `overviewid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departure`
--
ALTER TABLE `departure`
  ADD CONSTRAINT `departure_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`tripid`) ON DELETE CASCADE;

--
-- Constraints for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD CONSTRAINT `itinerary_ibfk_1` FOREIGN KEY (`tripid`) REFERENCES `trips` (`tripid`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `fk_activity` FOREIGN KEY (`activity`) REFERENCES `activities` (`activity`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_location_distination` FOREIGN KEY (`location`) REFERENCES `destinations` (`distination`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trip_bookings`
--
ALTER TABLE `trip_bookings`
  ADD CONSTRAINT `trip_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `trip_bookings_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`tripid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trip_images`
--
ALTER TABLE `trip_images`
  ADD CONSTRAINT `trip_images_ibfk_1` FOREIGN KEY (`tripid`) REFERENCES `trips` (`tripid`) ON DELETE CASCADE;

--
-- Constraints for table `trip_overviews`
--
ALTER TABLE `trip_overviews`
  ADD CONSTRAINT `trip_overviews_ibfk_1` FOREIGN KEY (`tripid`) REFERENCES `trips` (`tripid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
