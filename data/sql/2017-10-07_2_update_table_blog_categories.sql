UPDATE `blog_categories` SET `name` = 'Новини' WHERE `blog_categories`.`name` = 'Дизайн';
UPDATE `blog_categories` SET `name` = 'Лукс' WHERE `blog_categories`.`name` = 'Изкуство';
UPDATE `blog_categories` SET `name` = 'Фотографски истории' WHERE `blog_categories`.`name` = 'Иновации';

DELETE FROM `blog_categories` WHERE `blog_categories`.`name` = 'Полезно за потребителя';