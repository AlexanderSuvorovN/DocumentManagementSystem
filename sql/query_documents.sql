USE `sam`;
SELECT 
	`d`.`id` AS `id`,
    `d`.`name` AS `name`,
    (
		SELECT
			GROUP_CONCAT(`st`.`text`)
		FROM
			`dms_tags` AS `st`
            JOIN `dms_documents_to_tags` `sd2t` ON (`st`.`id` = `sd2t`.`tag_id`)
		WHERE
			`sd2t`.`document_id` = `d`.`id`
	) AS `tags`,
    `f`.`label_text` AS `folder_label_text`,
    `f`.`label_image` AS `folder_label_image`,
    (
		SELECT
			GROUP_CONCAT(`ss`.`filename`)
		FROM
			`dms_documents_scans` AS `ss`
		WHERE
			`ss`.`document_id` = `d`.`id`
	) AS `scans`,
    `d`.`description` AS `description`
FROM
	`dms_documents` AS `d` 
    LEFT JOIN `dms_folders` AS `f` ON (`d`.`folder_id` = `f`.`id`)
ORDER BY
	`d`.`id` ASC