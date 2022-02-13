CREATE DATABASE IF NOT EXISTS `slides_editor`;

CREATE TABLE IF NOT EXISTS `Files` (
  `FileName` text NOT NULL,
  `Content` text NOT NULL);

CREATE TABLE IF NOT EXISTS `Templates` (
  `TemplateName` text NOT NULL,
  `Slide` text NOT NULL);
