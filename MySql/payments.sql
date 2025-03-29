-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el8
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Авг 28 2024 г., 14:28
-- Версия сервера: 10.6.18-MariaDB-cll-lve
-- Версия PHP: 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `x_u_12878_openai`
--

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `id` varchar(500) NOT NULL,
  `user_id` varchar(500) NOT NULL,
  `status` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `status`) VALUES
('8aa1ac2f65b1db0f52a1613487b85971', '6150504681', 'active'),
('d1473190e3835a0758213ce97796a149', '6150504681', 'active'),
('f62bbc602f772456af2d6006039a119d', '6150504681', 'active'),
('a40315d0b98c26d4ccf16532008cd1b6', '6150504681', 'active'),
('4737e35dce69a7066ab2f27b05b48f26', '6150504681', 'passive'),
('fd5467387f3e2bb0417fbb8438b9c381', '6150504681', 'passive'),
('672f2533ba272667d2697a1d42461c3f', '6150504681', 'passive'),
('9c48ac5d59e384228ffd5baae0cbe434', '6150504681', 'passive'),
('9a68448e86b358d8b1180103dfaaadb6', '6442226432', 'active'),
('9ba47d85487ec85c530f8db249fedc8f', '6150504681', 'active'),
('16da0ac2a0ae54be659e76bd967be98f', '6150504681', 'passive'),
('c7b86aae7ac9328f6aac1747786ee8dd', '6150504681', 'active'),
('dc50415dc3830a8e48a4714b9aa465cf', '6150504681', 'active'),
('a4069f193e957e0a080d5e5e7aa6c160', '6150504681', 'active'),
('628cbab95dfacf6ef35c438783a6c6d6', '6807725549', 'active'),
('128e531a000a2c4015955bfed8dea4c7', '5967185398', 'active'),
('baddc86349a71d70bd99ea1d76c85510', '6716835671', 'active'),
('08605e7398bcc1ccbed712758285019d', '5299898653', 'active'),
('d33550c53a898c5c0e64131a9c06a3ce', '6042093770', 'active'),
('5dfaf4993cecdb65dccf53931ad221f5', '6150504681', 'passive'),
('0818ecd960f9cff5d72174bc16882c40', '6102135037', 'active'),
('5498ecb0cefc5ca16dc9afccdc1fe84d', '6150504681', 'passive'),
('1148f3ae9d24eed3b48a2ba8e9e0765a', '420831885', 'active'),
('401edab76c624e85413dbea51c703e56', '420831885', 'active'),
('afe48949181babb8e02911edc58a3fb7', '6664681047', 'active'),
('d641c2080d1a038d427a3dbf50085212', '6150504681', 'passive'),
('a4b61f88c38f92deb4f506ae109e3b5d', '6212122130', 'active'),
('868ce38e019ea85ab486e21369d9b6f3', '5819317484', 'passive'),
('de6d40f271a949125ebf9043304f9fd6', '6943740050', 'active'),
('dd3d511c2fc9da1d71fc33ef454870c4', '1864492013', 'active'),
('6dc00bf7349e6eb4903611f2bf76b3bd', '1864492013', 'active'),
('e1144db3188a59cca6ec8a8777d7f524', '6036575017', 'active'),
('449805c9bacf78b502c8f396e2b82567', '5042821110', 'active'),
('f2c1a21bc29ca11778bb71047ac2bea4', '5963487361', 'active'),
('ac3653093de6c1f42ba00110567830ee', '1462254019', 'passive'),
('c5d1b65cdfd89f08b34e6902fc8a368b', '5740448431', 'active'),
('5dbb4b50103c65589b298860cc3968a2', '1055802801', 'active'),
('2a7e0b797853bcd57c89128691847916', '1055802801', 'active'),
('78537a39e873dfcabef5861f04066ff1', '6083199406', 'passive'),
('e70efafb7da81b8c1973ba96ca30802f', '6906883836', 'active'),
('5fc5a74f0d45e93f4eaeaa2bcb161d41', '6510450266', 'active'),
('24a6f92ba8cd08f64b8acbdbdafd6d1a', '7022456089', 'active'),
('dcc612b7ca3bad3b7ab277cfd7b69a30', '5622907745', 'active'),
('d1d68cbeed30618f2b57d1c625ece231', '6239071942', 'active'),
('3f71637c82676604486dca832cea3322', '6379197628', 'active'),
('fb2b6b34e9a6d467a09bde4d1a47b28e', '5958239714', 'active'),
('7ca3de56f2fee221cbb21f391bfd02b9', '6150504681', 'active'),
('4e58514af2fda4ac673c2d9f85746824', '6150504681', 'active'),
('f2bd2a89e56bdbb82ea0fd33702f4a53', '6840148263', 'active'),
('e83c704e38310bf84c5923b864de2b39', '5702937311', 'active'),
('c9a16e06560dd4d970c72047e88e2291', '6762110300', 'active'),
('a14c31da6ee6c4a006f5545a4f445c31', '6083199406', 'active'),
('7d2b532cfd45ce4939063c615b27b9cf', '6150504681', 'active'),
('a71960e2ed053569446e9cd1827c2d7d', '6150504681', 'passive'),
('cd932d4b1fd3b1d4c973d5606d717579', '6379197628', 'passive');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
