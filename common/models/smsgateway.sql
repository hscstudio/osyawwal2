DELIMITER //

CREATE PROCEDURE getStudentTrainingInfo(
	IN pTrainingId INT, IN pPhoneNumber VARCHAR(20),
	OUT IN nama VARCHAR(50),
	OUT IN diklat VARCHAR(50),
	OUT IN nilai VARCHAR(10),
	OUT IN lulus VARCHAR(10),
)
BEGIN

	DECLARE vPhoneNumber VARCHAR(20) ;
	SET vPhoneNumber=substring(pPhoneNumber,4,16);
	
	SELECT 
	`person`.`name` as nama,
	`activity`.`name` as diklat,
	`test` as nilai,
	(
		SELECT count(training_class_student_id) FROM `training_class_student_certificate`
		WHERE
		(
			`training_class_student_id` = `training_class_student`.`id`
			AND
			`status`=1
		)
	)
	as lulus

	FROM `training_class_student` 
	LEFT JOIN `training_student` ON `training_class_student`.`training_student_id`=`training_student`.`id`
	LEFT JOIN `student` ON `training_student`.`student_id` = `student`.`person_id`
	LEFT JOIN `person` ON `student`.`person_id` = `person`.`id`

	LEFT JOIN `training` ON `training_class_student`.`training_id`=`training`.`activity_id`
	LEFT JOIN `activity` ON `training`.`activity_id` = `activity`.`id`

	WHERE 
		`training_class_student`.`training_id`=pTrainingId
		AND
		`person`.`phone` LIKE CONCAT('%', vPhoneNumber, '%');
END
//
DELIMITER ;

CALL getStudentTrainingInfo(14,'+6281559915720',@nama, @diklat, @nilai, @lulus);

AND
		`person`.`phone` LIKE CONCAT('%', vPhoneNumber, '%') COLLATE utf8_unicode_ci;
		
		
DROP PROCEDURE `getStudentTrainingInfo`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getStudentTrainingInfo`(IN `pTrainingId` INT, IN `pPhoneNumber` VARCHAR(20) CHARSET utf8) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN

	DECLARE vPhoneNumber VARCHAR(20) ;
	SET vPhoneNumber=substring(pPhoneNumber,4,16);
	
	SELECT 
	`person`.`name` as nama,
	`activity`.`name` as diklat,
	`test` as nilai,
	(
		SELECT count(training_class_student_id) FROM `training_class_student_certificate`
		WHERE
		(
			`training_class_student_id` = `training_class_student`.`id`
			AND
			`status`=1
		)
	)
	as lulus

	FROM `training_class_student` 
	LEFT JOIN `training_student` ON `training_class_student`.`training_student_id`=`training_student`.`id`
	LEFT JOIN `student` ON `training_student`.`student_id` = `student`.`person_id`
	LEFT JOIN `person` ON `student`.`person_id` = `person`.`id`

	LEFT JOIN `training` ON `training_class_student`.`training_id`=`training`.`activity_id`
	LEFT JOIN `activity` ON `training`.`activity_id` = `activity`.`id`

	WHERE 
		`training_class_student`.`training_id`=pTrainingId
		AND
		`person`.`phone` LIKE CONCAT('%', vPhoneNumber, '%');
END
