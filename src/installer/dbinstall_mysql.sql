SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `DBPREFIXconfig`;
CREATE TABLE `DBPREFIXconfig` (
  `identifier` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'STRING' COMMENT 'STRING, INT, FLOAT, BOOL, JSON',
  `value` text COLLATE utf8mb4_unicode_ci,
  `user_editable` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `DBPREFIXconfig` (`identifier`, `type`, `value`, `user_editable`) VALUES
('cache_servericons', 'INT', '600', 1),
('onlinerecord_value', 'INT', '0', 0),
('onlinerecord_date', 'INT', '0', 0),
('usingcloudflare', 'BOOL', 'false', 1),
('loginpokeclient', 'BOOL', 'true', 1),
('cache_logincode', 'INT', '120', 1),
('cache_adminstatus', 'INT', '60', 1),
('cache_languages', 'INT', '300', 1),
('adminstatus_groups', 'JSON', '[]', 1),
('adminstatus_mode', 'INT', '2', 1),
('adminstatus_enabled', 'BOOL', 'true', 1),
('adminstatus_hideoffline', 'BOOL', 'false', 1),
('adminstatus_ignoredusers', 'JSON', '[]', 1),
('assignerconfig', 'JSON', '[]', 1),
('query_nickname', 'STRING', 'TS-website', 1),
('cache_serverinfo', 'INT', '10', 1),
('cache_banlist', 'INT', '60', 1),
('cache_clientlist', 'INT', '15', 1),
('cache_channelist', 'INT', '60', 1),
('cache_servergroups', 'INT', '60', 1),
('cache_channelgroups', 'INT', '60', 1),
('adminstatus_offlinehiddenbydefault', 'BOOL', 'false', 1),
('imprint_enabled', 'BOOL', 'false', 1),
('imprint_url', 'STRING', 'imprint.php', 1),
('assigner_cooldown_seconds', 'INT', '0', 1),
('assigner_required_sgids', 'JSON', '[]', 1),
('viewer_hidden_channel_ids', 'JSON', '[]', 1);

DROP TABLE IF EXISTS `DBPREFIXfaq`;
CREATE TABLE `DBPREFIXfaq` (
  `faqid` int(11) NOT NULL,
  `langid` int(11) NOT NULL DEFAULT '1',
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastmodify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `DBPREFIXfaq` (`faqid`, `langid`, `question`, `answer`, `lastmodify`) VALUES
(1, 1, 'What is the FAQ?', '<b>FAQ</b> section allows you to show frequently asked questions and answers to them.', '2018-12-26 13:10:32'),
(2, 1, 'How can I configure the FAQ?', 'An administrator can add, edit and remove questions in <a href=\"admin\">admin panel</a>.', '2018-12-26 12:33:18'),
(3, 1, 'Question 3', 'Answer 3 in <b>HTML</b>', '2018-12-26 13:10:32');

DROP TABLE IF EXISTS `DBPREFIXnews`;
CREATE TABLE `DBPREFIXnews` (
  `newsid` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `langid` int(11) NOT NULL DEFAULT '1',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited` timestamp NULL DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `DBPREFIXnews` (`newsid`, `title`, `langid`, `added`, `edited`, `content`) VALUES
(1, 'Welcome to ts-website!', 1, '2018-12-26 03:10:32', NULL, '<b>Hi there!</b> If you are reading this, it means that TS-website has been installed successfully.<br>\r\n<!-- You can login to your <a href=\"admin\">ACP</a> to configure many parts of it.<br> -->\r\nNeed help? Join our <a href=\"https://t.me/tswebsite\" target=\"_blank\">Telegram group</a> for support.\r\nHave a good day!');


ALTER TABLE `DBPREFIXconfig`
  ADD UNIQUE KEY `param` (`identifier`);

ALTER TABLE `DBPREFIXfaq`
  ADD PRIMARY KEY (`faqid`);

ALTER TABLE `DBPREFIXnews`
  ADD PRIMARY KEY (`newsid`);


ALTER TABLE `DBPREFIXfaq`
  MODIFY `faqid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `DBPREFIXnews`
  MODIFY `newsid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
