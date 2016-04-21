-- ---
-- Table 'chat'
-- 
-- ---

DROP TABLE IF EXISTS `chat`;
		
CREATE TABLE `chat` (
  `id` INTEGER,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `telphone` VARCHAR(11) NOT NULL,
  `channel` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `started_at` DATETIME NULL,
  `finished_at` DATETIME NULL,
  `op_email` VARCHAR(255) NULL,
  `op_telphone` VARCHAR(255) NULL,
  `op_name` VARCHAR(255) NULL,
  `status` VARCHAR(255) NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'chat_messages'
-- 
-- ---

DROP TABLE IF EXISTS `chat_messages`;
		
CREATE TABLE `chat_messages` (
  `id` INTEGER,
  `chat_id` INTEGER NOT NULL,
  `source` VARCHAR(20) NOT NULL,
  `message` MEDIUMTEXT(500) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
);

