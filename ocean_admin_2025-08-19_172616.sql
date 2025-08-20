-- MySQL dump 10.13  Distrib 8.0.41, for macos14.7 (arm64)
--
-- Host: 127.0.0.1    Database: ocean_admin
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `oc_attachment`
--

DROP TABLE IF EXISTS `oc_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_attachment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `storage_mode` smallint NOT NULL COMMENT '存储类型,1->本地,2->七牛云,3->阿里云,4->腾讯云，5->S3,6->minio',
  `origin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '原始文件名',
  `object_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '新文件名',
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件hash',
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件mime类型',
  `storage_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '存储路径',
  `suffix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件后缀',
  `size_byte` bigint NOT NULL COMMENT '字节数',
  `size_info` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件大小',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件url',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attachment_hash_unique` (`hash`),
  KEY `attachment_storage_path_index` (`storage_path`),
  KEY `attachment_storage_mode_index` (`storage_mode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='附件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_attachment`
--

/*!40000 ALTER TABLE `oc_attachment` DISABLE KEYS */;
INSERT INTO `oc_attachment` VALUES (3,1,'avatar.png','b276854f56af4b36384724d00779261d.png','b276854f56af4b36384724d00779261d','image/png','/storage/upload/image/b276854f56af4b36384724d00779261d.png','png',36099,'35.25 KB','http://127.0.0.1:9501/storage/upload/image/b276854f56af4b36384724d00779261d.png',NULL,'2025-07-24 12:23:55','2025-07-24 12:23:55'),(4,1,'logo.png','494471fe109aba215f194687c623d7a6.png','494471fe109aba215f194687c623d7a6','image/png','/storage/upload/image/494471fe109aba215f194687c623d7a6.png','png',1238251,'1.18 MB','http://127.0.0.1:9501/storage/upload/image/494471fe109aba215f194687c623d7a6.png',NULL,'2025-07-29 11:03:04','2025-07-29 11:03:04');
/*!40000 ALTER TABLE `oc_attachment` ENABLE KEYS */;

--
-- Table structure for table `oc_config`
--

DROP TABLE IF EXISTS `oc_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_config` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint unsigned DEFAULT NULL COMMENT '配置组ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置名称',
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置键',
  `value` text COLLATE utf8mb4_unicode_ci COMMENT '配置值',
  `input_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '数据输入类型',
  `config_select_data` text COLLATE utf8mb4_unicode_ci COMMENT '配置选项数据',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `config_group_id_index` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='参数配置信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_config`
--

/*!40000 ALTER TABLE `oc_config` DISABLE KEYS */;
INSERT INTO `oc_config` VALUES (3,1,'网站名称','site_name','oceanAdmin','input','','',100,'2025-07-30 17:20:21','2025-07-30 18:31:29');
/*!40000 ALTER TABLE `oc_config` ENABLE KEYS */;

--
-- Table structure for table `oc_config_group`
--

DROP TABLE IF EXISTS `oc_config_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_config_group` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置组名称',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置组编码',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置组备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_group_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='配置组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_config_group`
--

/*!40000 ALTER TABLE `oc_config_group` DISABLE KEYS */;
INSERT INTO `oc_config_group` VALUES (1,'站点配置','site_config','网站配置','2025-07-30 17:02:47','2025-07-30 18:31:42');
/*!40000 ALTER TABLE `oc_config_group` ENABLE KEYS */;

--
-- Table structure for table `oc_crontab`
--

DROP TABLE IF EXISTS `oc_crontab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_crontab` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '任务名称',
  `rule` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '执行规则',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '任务类型',
  `task_style` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '任务样式',
  `target` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '执行目标',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1->执行成功,2->执行失败',
  `is_on_one_server` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1->只在一台服务器执行,0->所有服务器执行',
  `is_singleton` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1->单例执行,0->多实例执行',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `crontab_name_unique` (`name`),
  KEY `crontab_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='定时任务表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_crontab`
--

/*!40000 ALTER TABLE `oc_crontab` DISABLE KEYS */;
INSERT INTO `oc_crontab` VALUES (2,'测试任务','0 */1 * * * *','class',4,'App\\Task\\DemoTask',2,1,1,'测试一下','2025-08-09 18:07:42','2025-08-12 11:06:39');
/*!40000 ALTER TABLE `oc_crontab` ENABLE KEYS */;

--
-- Table structure for table `oc_crontab_log`
--

DROP TABLE IF EXISTS `oc_crontab_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_crontab_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `crontab_id` bigint unsigned NOT NULL COMMENT '任务ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '任务名称',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1->执行成功,2->执行失败',
  `target` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '执行目标',
  `exception` text COLLATE utf8mb4_unicode_ci COMMENT '异常信息',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crontab_log_crontab_id_index` (`crontab_id`),
  KEY `crontab_log_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='定时任务日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_crontab_log`
--

/*!40000 ALTER TABLE `oc_crontab_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_crontab_log` ENABLE KEYS */;

--
-- Table structure for table `oc_dict_data`
--

DROP TABLE IF EXISTS `oc_dict_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_dict_data` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type_id` bigint unsigned NOT NULL COMMENT '字典类型ID',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典标签',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典值',
  `color` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '颜色',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典编码',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态,1->正常，2->禁用',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dict_data_status_index` (`status`),
  KEY `dict_data_type_id_index` (`type_id`),
  KEY `dict_data_code_index` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='字典数据';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_dict_data`
--

/*!40000 ALTER TABLE `oc_dict_data` DISABLE KEYS */;
INSERT INTO `oc_dict_data` VALUES (1,1,'本地存储','1','#00B42A','upload_mode',10,1,'local','2025-07-24 19:03:17','2025-07-24 19:11:53','2025-07-24 11:11:53'),(2,1,'本地存储','1','#00B42A','upload_mode',10,1,'local','2025-07-24 19:12:05','2025-07-24 19:42:20',NULL),(3,1,'阿里云OSS','2','#14C9C9','upload_mode',9,1,'oss','2025-07-24 19:26:30','2025-07-24 19:26:30',NULL),(4,1,'七牛云','3','#7CB342','upload_mode',8,1,'qiniu','2025-07-24 19:26:49','2025-07-24 19:26:49',NULL),(5,1,'腾讯云COS','4','#D91AD9','upload_mode',7,1,'cos','2025-07-24 19:27:36','2025-07-24 19:27:36',NULL),(6,1,'亚马逊S3','5','#165DFF','upload_mode',6,1,'s3','2025-07-24 19:27:56','2025-07-24 19:27:56',NULL),(7,3,'正常','1','#34C759','data_status',10,1,'','2025-07-24 19:29:34','2025-07-24 19:29:34',NULL),(8,3,'停用','2','#F53F3F','data_status',100,1,'停用','2025-07-24 19:29:51','2025-07-24 19:29:51',NULL),(9,4,'统计页面','statistics','#34C759','dashboard',10,1,'','2025-07-24 19:30:47','2025-07-24 19:30:47',NULL),(10,4,'工作台','work','#F77234','dashboard',9,1,'','2025-07-24 19:31:06','2025-07-24 19:31:06',NULL),(11,5,'男','1','#34C759','gender',100,1,'','2025-07-24 19:31:49','2025-07-24 19:31:49',NULL),(12,5,'女','2','#7CB342','gender',99,1,'','2025-07-24 19:32:02','2025-07-24 19:32:10',NULL),(13,5,'未知','3','#F53F3F','gender',98,1,'','2025-07-24 19:32:27','2025-07-24 19:32:27',NULL),(14,6,'图片','image','#00B42A','attachment_type',100,1,'','2025-07-24 19:33:18','2025-07-24 19:33:18',NULL),(15,6,'文档','text','#7CB342','attachment_type',99,1,'','2025-07-24 19:33:34','2025-07-24 19:33:34',NULL),(16,6,'音频','audio','#C0CA33','attachment_type',98,1,'','2025-07-24 19:33:48','2025-07-24 19:33:48',NULL),(17,6,'视频','video','#14C9C9','attachment_type',97,1,'','2025-07-24 19:34:01','2025-07-24 19:34:01',NULL),(18,6,'应用程序','application','#F53F3F','attachment_type',96,1,'','2025-07-24 19:34:20','2025-07-24 19:34:20',NULL),(19,7,'菜单','M','#34C759','menu_type',10,1,'','2025-07-24 19:35:18','2025-07-24 19:35:18',NULL),(20,7,'按钮','B','#C0CA33','menu_type',9,1,'','2025-07-24 19:35:31','2025-07-24 19:35:31',NULL),(21,7,'外链','L','#F77234','menu_type',8,1,'','2025-07-24 19:35:48','2025-07-24 19:35:48',NULL),(22,7,'IFrame','I','#14C9C9','menu_type',7,1,'','2025-07-24 19:36:04','2025-07-24 19:36:04',NULL),(23,2,'是','1','#34C759','yes_or_no',10,1,'','2025-07-24 19:37:42','2025-07-24 19:37:42',NULL),(24,2,'否','2','#F53F3F','yes_or_no',9,1,'','2025-07-24 19:37:52','2025-07-24 19:37:52',NULL),(25,7,'测试','123','','menu_type',100,1,'','2025-07-24 19:38:55','2025-07-24 19:38:58','2025-07-24 11:38:58'),(26,1,'minio','6','#3491FA','upload_mode',5,1,'minio','2025-07-24 19:43:08','2025-07-24 19:43:18',NULL);
/*!40000 ALTER TABLE `oc_dict_data` ENABLE KEYS */;

--
-- Table structure for table `oc_dict_type`
--

DROP TABLE IF EXISTS `oc_dict_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_dict_type` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典名称',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典编码',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备注',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态,1->正常，2->禁用',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dict_type_code_index` (`code`),
  KEY `dict_type_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='字典类型';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_dict_type`
--

/*!40000 ALTER TABLE `oc_dict_type` DISABLE KEYS */;
INSERT INTO `oc_dict_type` VALUES (1,'存储模式','upload_mode','存储模式',1,'2025-07-24 18:28:20','2025-07-24 19:43:23',NULL),(2,'是否','yes_or_no','状态是否',1,'2025-07-24 18:29:47','2025-07-24 18:32:29',NULL),(3,'数据状态','data_status','1->正常,2->禁用',1,'2025-07-24 19:28:55','2025-07-24 19:28:55',NULL),(4,'后台首页','dashboard','',1,'2025-07-24 19:30:28','2025-07-24 19:30:28',NULL),(5,'性别','gender','',1,'2025-07-24 19:31:30','2025-07-24 19:31:30',NULL),(6,'附件类型','attachment_type','',1,'2025-07-24 19:32:59','2025-07-24 19:32:59',NULL),(7,'菜单类型','menu_type','',1,'2025-07-24 19:34:55','2025-07-24 19:34:55',NULL);
/*!40000 ALTER TABLE `oc_dict_type` ENABLE KEYS */;

--
-- Table structure for table `oc_generate_table`
--

DROP TABLE IF EXISTS `oc_generate_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_generate_table` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '表名称',
  `table_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '表注释',
  `namespace` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '命名空间',
  `package_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '包名称',
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '业务名称',
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类名称',
  `menu_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '菜单名称',
  `parent_menu_id` int unsigned NOT NULL DEFAULT '0' COMMENT '父菜单ID',
  `tpl_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '模版类型',
  `generate_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '前端生成路径',
  `generate_model` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '1->软删除,2->非软删除',
  `generate_menus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '生成菜单列表',
  `build_menu` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否生成菜单',
  `component_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '组件类型 1->模态框,2->抽屉',
  `options` varchar(1500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '其他选项',
  `form_width` int NOT NULL DEFAULT '0' COMMENT '表单宽度',
  `is_full` tinyint unsigned NOT NULL DEFAULT '2' COMMENT '是否全屏 1->是,2->否',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `source` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '数据源',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='代码生成表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_generate_table`
--

/*!40000 ALTER TABLE `oc_generate_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_generate_table` ENABLE KEYS */;

--
-- Table structure for table `oc_login_log`
--

DROP TABLE IF EXISTS `oc_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_login_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户名',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址',
  `os` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '操作系统',
  `ip_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址位置',
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '浏览器',
  `status` tinyint unsigned DEFAULT NULL COMMENT '状态,1->登录成功,2->登录失败',
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '消息',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_log_username_index` (`username`),
  KEY `login_log_ip_index` (`ip`),
  KEY `login_log_status_index` (`status`),
  KEY `login_log_login_time_index` (`login_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='登录日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_login_log`
--

/*!40000 ALTER TABLE `oc_login_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_login_log` ENABLE KEYS */;

--
-- Table structure for table `oc_menu`
--

DROP TABLE IF EXISTS `oc_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_menu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '菜单名称',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '菜单编码',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '菜单图标',
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路由',
  `component` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '组件',
  `redirect` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '跳转地址',
  `is_hidden` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1是,2否',
  `is_layout` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1是,2否',
  `type` char(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类型,M:菜单,B:按钮,L:链接,I:iframe',
  `generate_id` int unsigned NOT NULL DEFAULT '0' COMMENT '生成ID',
  `generate_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '生成KEY',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 1:启用 2:禁用',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_code_unique` (`code`),
  KEY `menu_parent_id_index` (`parent_id`),
  KEY `menu_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_menu`
--

/*!40000 ALTER TABLE `oc_menu` DISABLE KEYS */;
INSERT INTO `oc_menu` VALUES (1,0,'权限','permission','IconSafe','permission','',NULL,2,1,'M',0,NULL,1,0,'','2025-07-26 16:52:17','2025-07-26 21:00:22',NULL),(2,1,'用户管理','permission:user','IconUserGroup','permission/user','system/user/index',NULL,2,1,'M',0,NULL,1,0,'用户列表','2025-07-26 16:54:07','2025-07-26 21:00:35',NULL),(3,2,'用户列表','permission:user:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-26 21:13:04','2025-07-26 21:13:04',NULL),(4,2,'添加用户','permission:user:store','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-26 21:13:58','2025-07-26 21:13:58',NULL),(5,2,'更新用户','permission:user:update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-26 21:14:14','2025-07-26 21:14:14',NULL),(6,2,'删除用户','permission:user:delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-26 21:14:38','2025-07-26 21:14:38',NULL),(7,2,'批量删除用户','permission:user:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-26 21:14:58','2025-07-26 21:14:58',NULL),(9,1,'菜单管理','permission:menu','IconMenu','permission/menu','system/menu/index',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 15:39:14','2025-07-29 15:39:14',NULL),(10,9,'菜单列表','permission:menu:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:40:15','2025-07-29 15:40:15',NULL),(11,9,'添加菜单','permission:menu:store','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:42:36','2025-07-29 15:42:36',NULL),(12,9,'更新菜单','permission:menu:update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:43:19','2025-07-29 15:43:19',NULL),(13,9,'删除菜单','permission:menu:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:43:44','2025-07-29 15:43:44',NULL),(14,1,'角色管理','permission:role','IconLock','permission/role','system/role/index',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 15:47:46','2025-07-29 15:47:46',NULL),(15,14,'角色列表','permission:role:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:48:28','2025-07-29 15:48:28',NULL),(16,14,'添加角色','permission:role:store','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:48:56','2025-07-29 15:48:56',NULL),(17,14,'更新角色','permission:role:update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:49:11','2025-07-29 15:49:11',NULL),(18,14,'删除角色','permission:role:delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:49:35','2025-07-29 15:49:35',NULL),(19,14,'批量删除角色','permission:role:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:49:54','2025-07-29 15:49:54',NULL),(20,14,'获取角色菜单','permission:role:role-menus','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:50:27','2025-07-29 15:50:27',NULL),(21,14,'获取菜单树状下拉','permission:role:menu-tree','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:51:05','2025-07-29 15:51:05',NULL),(22,14,'角色分配菜单','permission:role:assign-menus','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:51:29','2025-07-29 15:51:29',NULL),(23,2,'获取角色下拉列表','permission:user:role-select','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:53:50','2025-07-29 15:53:50',NULL),(24,2,'获取用户角色下拉','permission:user:user-roles','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:54:15','2025-07-29 15:54:15',NULL),(25,2,'修改用户状态','permission:user:change-status','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:54:34','2025-07-29 15:54:34',NULL),(26,2,'更新用户缓存','permission:user:update-cache','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:54:52','2025-07-29 15:54:52',NULL),(27,2,'初始化用户密码','permission:user:init-password','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 15:55:13','2025-07-29 15:55:13',NULL),(28,0,'数据','data','IconStorage','data','',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 16:00:19','2025-07-29 16:00:19',NULL),(29,28,'数据字典','data:dict','IconBook','data/dict','system/dict/index',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 16:01:27','2025-07-29 16:01:27',NULL),(30,29,'获取字典类型列表','data:dict-type:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:02:48','2025-07-29 16:02:48',NULL),(31,29,'添加字典类型','data:dict-type:store','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:03:08','2025-07-29 16:05:29',NULL),(32,29,'更新字典类型','data:dict-type:update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:03:25','2025-07-29 16:03:25',NULL),(33,29,'删除字典类型','data:dict-type:delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:03:42','2025-07-29 16:03:42',NULL),(34,29,'批量删除字典类型','data:dict-type:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:03:57','2025-07-29 16:03:57',NULL),(35,29,'更改字典类型状态','data:dict-type:change-status','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:04:19','2025-07-29 16:04:19',NULL),(36,29,'获取字典列表','data:dict:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:04:50','2025-07-29 16:04:50',NULL),(37,29,'添加字典','data:dict:store','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:05:06','2025-07-29 16:05:23',NULL),(38,29,'更新字典','data:dict:update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:05:47','2025-07-29 16:05:47',NULL),(39,29,'删除字典','data:dict:delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:06:02','2025-07-29 16:06:02',NULL),(40,29,'批量删除字典','data:dict:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:06:18','2025-07-29 16:06:18',NULL),(41,29,'更新字典状态','data:dict:change-status','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:06:37','2025-07-29 16:06:37',NULL),(42,28,'附件管理','data:attachment','IconAttachment','data/attachment','system/attachment/index',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 16:08:05','2025-07-29 16:08:05',NULL),(43,42,'附件列表','data:attachment:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:09:22','2025-07-29 16:09:22',NULL),(44,42,'删除附件','data:attachment:delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:09:43','2025-07-29 16:09:43',NULL),(45,42,'批量删除附件','data:attachment:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:10:01','2025-07-29 16:10:01',NULL),(46,42,'下载附件','data:attachment:download','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:10:17','2025-07-29 16:10:17',NULL),(47,0,'监控','monitor','IconComputer','monitor','',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 16:12:23','2025-07-29 16:12:23',NULL),(48,47,'服务监控','monitor:server','IconDashboard','monitor/server','system/monitor/server/index',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 16:13:05','2025-07-29 16:13:05',NULL),(49,48,'服务监控信息','monitor:server:info','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:13:49','2025-07-29 16:13:49',NULL),(50,47,'日志监控','monitor:logs','IconRobot','monitor/logs','',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 16:14:49','2025-07-29 16:14:49',NULL),(51,50,'登录日志','monitor:logs:loginLog','IconImport','monitor/logs/loginLog','system/logs/loginLog',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 16:15:43','2025-07-29 16:15:43',NULL),(52,51,'登录日志列表','monitor:login-log:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:16:24','2025-07-29 16:16:24',NULL),(53,50,'操作日志','monitor:logs:operLog','IconInfoCircle','monitor/logs/operLog','system/logs/operLog',NULL,2,1,'M',0,NULL,1,0,'','2025-07-29 16:17:20','2025-07-29 16:17:20',NULL),(54,53,'操作日志列表','monitor:operate-log:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-29 16:17:42','2025-07-29 16:17:42',NULL),(55,0,'系统设置','config','IconSettings','config','system/config/index',NULL,2,1,'M',0,NULL,1,1,'','2025-07-30 16:39:54','2025-08-11 18:21:04',NULL),(56,55,'配置组列表','config-group:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:56:14','2025-07-30 17:56:14',NULL),(57,55,'添加配置组','config-group:store','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:56:33','2025-07-30 17:56:33',NULL),(58,55,'更新配置组','config-group:update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:56:50','2025-07-30 17:56:50',NULL),(59,55,'删除配置组','config-group:delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:57:07','2025-07-30 17:57:07',NULL),(60,55,'批量删除配置组','config-group:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:57:25','2025-07-30 17:57:25',NULL),(61,55,'配置列表','config:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:57:50','2025-07-30 17:57:50',NULL),(62,55,'添加配置','config:store','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:58:06','2025-07-30 17:58:06',NULL),(63,55,'更新配置','config:update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:58:23','2025-07-30 17:58:23',NULL),(64,55,'删除配置','config:delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:58:43','2025-07-30 17:58:43',NULL),(65,55,'批量删除配置','config:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:59:01','2025-07-30 17:59:01',NULL),(66,55,'批量更新配置','config:batch-update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 17:59:24','2025-07-30 17:59:24',NULL),(67,51,'批量删除登录日志','monitor:login-log:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 20:57:06','2025-07-30 20:57:06',NULL),(68,53,'批量删除操作日志','monitor:operate-log:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-07-30 20:57:48','2025-07-30 20:57:48',NULL),(69,0,'工具','tool','IconTool','tool','',NULL,2,1,'M',0,NULL,1,0,'','2025-08-04 11:01:53','2025-08-04 11:01:53',NULL),(70,69,'定时任务','tool:crontab','IconSchedule','tool/crontab','tool/crontab/index',NULL,2,1,'M',0,NULL,1,0,'','2025-08-04 11:02:35','2025-08-04 11:02:35',NULL),(71,70,'定时任务列表','tool:crontab:list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:04:44','2025-08-11 17:04:44',NULL),(72,70,'创建定时任务','tool:crontab:store','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:05:09','2025-08-11 17:05:09',NULL),(73,70,'更新定时任务','tool:crontab:update','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:05:27','2025-08-11 17:05:27',NULL),(74,70,'删除定时任务','tool:crontab:delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:05:44','2025-08-11 17:05:44',NULL),(75,70,'批量删除定时任务','tool:crontab:batch-delete','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:06:03','2025-08-11 17:06:03',NULL),(76,70,'修改定时任务状态','tool:crontab:change-status','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:06:25','2025-08-11 17:07:24',NULL),(77,70,'定时任务执行日志列表','tool:crontab:log-list','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:06:41','2025-08-11 17:06:41',NULL),(78,70,'删除定时任务执行日志','tool:crontab:batch-delete-logs','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:07:04','2025-08-11 17:07:04',NULL),(79,70,'执行定时任务','tool:crontab:run\'','','','',NULL,2,1,'B',0,NULL,1,0,'','2025-08-11 17:07:40','2025-08-11 17:07:40',NULL);
/*!40000 ALTER TABLE `oc_menu` ENABLE KEYS */;

--
-- Table structure for table `oc_migrations`
--

DROP TABLE IF EXISTS `oc_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_migrations`
--

/*!40000 ALTER TABLE `oc_migrations` DISABLE KEYS */;
INSERT INTO `oc_migrations` VALUES (1,'2025_07_22_184422_create_system_user_table',1),(2,'2025_07_23_202207_create_attachment_table',2),(5,'2025_07_24_162727_create_dict_type_table',3),(6,'2025_07_24_162804_create_dict_data_table',3),(7,'2025_07_25_172121_create_role_table',4),(8,'2025_07_25_172910_create_user_role_table',5),(9,'2025_07_25_172924_create_role_menu_table',5),(10,'2025_07_25_173039_create_menu_table',6),(11,'2025_07_30_163100_create_config_group_table',7),(12,'2025_07_30_163112_create_config_table',7),(13,'2025_07_30_193020_create_login_log_table',8),(14,'2025_07_30_193049_create_operate_log_table',8),(19,'2025_08_06_110603_create_crontab_table',9),(20,'2025_08_06_110612_create_crontab_log_table',9),(21,'2025_08_08_111317_add_task_style_to_crontab',10),(22,'2025_08_12_110735_create_generate_table',11);
/*!40000 ALTER TABLE `oc_migrations` ENABLE KEYS */;

--
-- Table structure for table `oc_operate_log`
--

DROP TABLE IF EXISTS `oc_operate_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_operate_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户名',
  `method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '请求方法',
  `router` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '请求路由',
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '业务名称',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址',
  `ip_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址位置',
  `request_data` text COLLATE utf8mb4_unicode_ci COMMENT '请求数据',
  `remark` text COLLATE utf8mb4_unicode_ci COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operate_log_username_index` (`username`),
  KEY `operate_log_ip_index` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_operate_log`
--

/*!40000 ALTER TABLE `oc_operate_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_operate_log` ENABLE KEYS */;

--
-- Table structure for table `oc_role`
--

DROP TABLE IF EXISTS `oc_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色编码',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 1:启用 2:禁用',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_role`
--

/*!40000 ALTER TABLE `oc_role` DISABLE KEYS */;
INSERT INTO `oc_role` VALUES (1,'超级管理员','superAdmin',1,100,'拥有一切权限','2025-07-26 09:06:37','2025-07-26 09:06:37',NULL),(2,'管理员','baseAdmin',1,98,'权限也很大','2025-07-26 09:07:58','2025-07-28 11:35:46',NULL);
/*!40000 ALTER TABLE `oc_role` ENABLE KEYS */;

--
-- Table structure for table `oc_role_menu`
--

DROP TABLE IF EXISTS `oc_role_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_role_menu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL COMMENT '角色ID',
  `menu_id` bigint unsigned NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`id`),
  KEY `role_menu_role_id_index` (`role_id`),
  KEY `role_menu_menu_id_index` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色菜单关联表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_role_menu`
--

/*!40000 ALTER TABLE `oc_role_menu` DISABLE KEYS */;
INSERT INTO `oc_role_menu` VALUES (2,2,2),(3,2,3),(4,2,4),(5,2,5),(8,2,14),(9,2,28),(10,2,47),(11,2,6),(12,2,7),(13,2,23),(14,2,24),(15,2,25),(16,2,26),(17,2,27),(18,2,15),(19,2,16),(20,2,17),(21,2,18),(22,2,19),(23,2,20),(24,2,21),(25,2,22),(26,2,29),(27,2,30),(28,2,31),(29,2,32),(30,2,33),(31,2,34),(32,2,35),(33,2,36),(34,2,37),(35,2,38),(36,2,39),(37,2,40),(38,2,41),(39,2,42),(40,2,43),(41,2,44),(42,2,45),(43,2,46),(44,2,48),(45,2,49),(46,2,50),(47,2,51),(48,2,52),(49,2,53),(50,2,54),(51,2,1),(52,2,55),(53,2,56),(54,2,57),(55,2,58),(56,2,59),(57,2,60),(58,2,61),(59,2,62),(60,2,63),(61,2,64),(62,2,65),(63,2,66),(64,2,67),(65,2,68);
/*!40000 ALTER TABLE `oc_role_menu` ENABLE KEYS */;

--
-- Table structure for table `oc_system_user`
--

DROP TABLE IF EXISTS `oc_system_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_system_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '昵称',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态,1->正常,2->禁用',
  `signed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '签名',
  `dashboard` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '后台首页类型',
  `backend_setting` json DEFAULT NULL COMMENT '后台设置',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `login_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '登录IP',
  `login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_user_username_unique` (`username`),
  UNIQUE KEY `system_user_email_unique` (`email`),
  KEY `system_user_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统用户';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_system_user`
--

/*!40000 ALTER TABLE `oc_system_user` DISABLE KEYS */;
INSERT INTO `oc_system_user` VALUES (1,'admin','$2y$10$VEvAeOwawLH8DBeDjj5xYuEvlmR.DbhyK399UeCdVD0hsE8a85AYS','Administrator','http://127.0.0.1:9501/storage/upload/image/b276854f56af4b36384724d00779261d.png','admin@admin.com','18912030012','1','你今天的努力，是为了明天能看到更好的自己。\n别着急，别焦虑，每个人都有属于自己的时区和节奏。慢慢来，只要不停止前进的脚步，生活总会在某个时刻给你回报和惊喜。','statistics','{\"ws\": false, \"tag\": true, \"i18n\": false, \"mode\": \"light\", \"skin\": \"mine\", \"color\": \"#7166F0\", \"layout\": \"classic\", \"language\": \"zh_CN\", \"animation\": \"ma-slide-down\", \"menuWidth\": 230, \"roundOpen\": true, \"waterMark\": false, \"menuCollapse\": false, \"waterContent\": \"saiadmin\"}',NULL,'127.0.0.1','2025-08-14 15:52:11','2025-07-22 21:01:01','2025-08-14 15:52:11',NULL);
/*!40000 ALTER TABLE `oc_system_user` ENABLE KEYS */;

--
-- Table structure for table `oc_user_role`
--

DROP TABLE IF EXISTS `oc_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_user_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `system_user_id` bigint unsigned NOT NULL COMMENT '用户ID',
  `role_id` bigint unsigned NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`),
  KEY `user_role_system_user_id_index` (`system_user_id`),
  KEY `user_role_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户角色关联表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_user_role`
--

/*!40000 ALTER TABLE `oc_user_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_user_role` ENABLE KEYS */;

--
-- Dumping routines for database 'ocean_admin'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-19 17:26:17
