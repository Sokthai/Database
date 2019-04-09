INSERT INTO `courses` (`c_id`, `title`, `description`, `mentor_grade_req`, `mentee_grade_req`) VALUES 
('1', 'Music', 'Retail Trainee', '9', '4'), 
('2', 'Mathematics', 'HR Specialist', '8', '6'),
('3', 'Grammar', 'CNC Operato', '6', '4'), 
('4', 'Science', 'Associate Professort', '9', '6'),
('5', 'American Literature', 'Doctor', '9', '8'), 
('6', 'Speech', 'IT Support Staff', '8', '3'),
('7', 'Ecology', 'Retail Trainee', '9', '4'), 
('8', 'Language Arts', 'Project Manager', '11', '5'),
('9', 'Modern Literature', 'HR Coordinator', '5', '4'), 
('10', 'Handwriting', 'Stockbroker', '8', '3'),
('11', 'Ancient Civilizations', 'Chef Manager', '9', '6'), 
('12', 'Latin', 'Assistant Buyer', '10', '3');

INSERT INTO `time_slot` (`time_slot_id`, `day_of_the_week`, `start_time`, `end_time`) VALUES 
('1', 't, th', '02:00:00', '03:030:00'), 
('2', 'm, w, f', '05:00:00', '06:00:00'),
('3', 't, th', '09:00:00', '10:045:00'), 
('4', 't, th', '05:00:00', '07:00:00'),
('5', 't, th', '12:00:00', '01:45:00'), 
('6', 'm, w, f', '10:00:00', '10:50:00'),
('7', 't, th', '01:00:00', '02:15:00'), 
('8', 'm, w, f', '04:00:00', '04:45:00'),
('9', 't, th', '3:30:00', '04:45:00'), 
('10', 'm, w, f', '04:00:00', '04:50:00'),
('11', 't, th', '07:00:00', '08:15:00'), 
('12', 'm, w, f', '06:00:00', '06:45:00');


INSERT INTO `sections` (`sec_id`, `sec_name`, `start_date`, `end_date`, `time_slot_id`, `capacity`, `c_id`) VALUES 
('1', '210', '2019-03-30', '2019-06-30', '2', '20', '9'), 
('2', '300', '2019-03-01', '2019-05-31', '4', '15', '12'),
('3', '350', '2019-03-13', '2019-04-26', '3', '25', '10'), 
('4', '400', '2019-05-16', '2019-06-21', '10', '12', '7'),
('5', '450', '2019-03-29', '2019-04-27', '6', '32', '5'), 
('6', '500', '2019-04-19', '2019-05-31', '11', '15', '4'),
('7', '500', '2019-03-10', '2019-03-29', '1', '24', '9'), 
('8', '590', '2019-03-01', '2019-03-23', '7', '20', '6'),
('9', '600', '2019-03-24', '2019-04-11', '6', '15', '6'), 
('10', '620', '2019-03-13', '2019-03-30', '9', '18', '11'), 
('11', '650', '2019-03-20', '2019-06-29', '11', '16', '8'), 
('12', '380', '2019-03-30', '2019-04-30', '2', '24', '11');


INSERT INTO `sessions` (`ses_id`, `sec_id`, `ses_name`, `date`, `announcement`) VALUES 
('1', '2', 'Morning class', '2019-03-27', 'Quiz on chapter 8'), 
('2', '6', 'noon class', '2019-03-13', 'Homework 2 due'), 
('3', '12', 'weekend hour', '2019-03-30', 'read chapter 3'), 
('4', '6', 'after class', '2019-03-01', 'prepare for exam'), 
('5', '9', 'acceleration class', '2019-04-19', 'final is on chapter 9 to 15'), 
('6', '10', 'weekend hour', '2019-04-30', 'class discussion'), 
('7', '2', 'weekly class', '2019-05-31', 'group activity'), 
('8', '12', 'noon class', '2019-03-29', 'only survey '), 
('9', '5', 'evening class', '2019-03-31', 'exam on chapter 2'), 
('10', '8', 'afternoon class', '2019-04-11', 'read chapter 3 and there is a quiz on chapter 2'), 
('11', '10', 'daily class', '2019-04-22', 'final review'), 
('12', '4', 'night class', '2019-05-22', 'group exam');

INSERT INTO `users` (`id`, `email`, `password`, `name`, `phone`, `city`, `state`) VALUES 
('1', 'test@gmail.com', '', 'Shay Rogan', '6-085-531-4018', 'San Diego', 'Wisconsin'),
('2', 'test2@gmail.com', '', 'Sebastian Eaton', '1-332-531-4018', 'Louisville', 'california'),
('3', 'test3@gmail.com', '', 'Chester Wood', '9-085-531-4018', 'Garland', 'Oklahoma'),
('4', 'test4@gmail.com', '', 'Ramon Ellison', '2-085-321-4018', 'Omaha', 'Rhode Island'),
('5', 'test5@gmail.com', '', 'Gina Faulkner', '6-085-531-4018', 'Louisville', 'New Hampshire'),
('6', 'test6@gmail.com', '', 'Lucas Campbell', '7-085-531-4018', 'Honolulu', 'Utah'),
('7', 'test7@gmail.com', '', 'Helen Chapman', '5-085-531-4018', 'Otawa', 'West Virginia'),
('8', 'test8@gmail.com', '', 'Wendy Khan', '3-085-531-4018', 'San Diego8', 'Vermont'),
('9', 'test9@gmail.com', '', 'Shay Rogan9', '4-085-531-4018', 'Otawa', 'Minnesota'),
('10', 'test10@gmail.com', '', 'Angela Franks', '2-085-531-4018', 'Indianapolis', 'Hawaii'),
('11', 'test11@gmail.com', '', 'Ethan Eaton', '91-85-531-4018', 'Sacramento', 'Florida'),
('12', 'test12@gmail.com', '', 'Nicholas Allington', '60-085-531-4018', 'Indianapolis', 'Utah'),
('13', 'test13@gmail.com', '', 'Felicity Cassidy', '6-085-531-9834', 'Saint Paul', 'South Carolina'),
('14', 'test14@gmail.com', '', 'Livia Larkin', '07-85-531-4018', 'Prague', 'Tennessee'),
('15', 'test15@gmail.com', '', 'Caleb Holt', '25-85-531-4018', 'Bridgeport', 'Rhode Island'),
('16', 'test16@gmail.com', '', 'Leanne Watson', '53-05-531-4018', 'San Diego', 'california'),
('17', 'test17@gmail.com', '', 'Cynthia Truscott', '54-85-531-4018', 'Sacramento', 'Minnesota'),
('18', 'test18@gmail.com', '', 'Jimmy Jonh', '72-05-531-4018', 'Bridgeport', 'Rhode Island'),
('19', 'test19@gmail.com', '', 'Marry Kondo', '21-805-531-4018', 'Minneapolis', 'Wisconsin'),
('20', 'test20@gmail.com', '', 'Jerry jey', '62-405-531-4018', 'Minneapolis', 'california');


INSERT INTO `parents` (`parent_id`) VALUES ('3'), ('5'), ('13'), ('14'), ('7'), ('16'), ('2'), ('11');

INSERT INTO `moderators` (`moderator_id`) VALUES ('5'), ('2'), ('3'), ('14'), ('11');

INSERT INTO `students` (`student_id`, `grade`) VALUES ('4', '8'), ('6', '10'), ('8', '5'), ('9', '3'), ('10', '12'), ('15', '13');

INSERT INTO `parenting` (`parent_id`, `student_id`) VALUES ('2', '4'), ('2', '6'), ('2', '8'), ('3', '9'), ('14', '10'), ('11', '15');

INSERT INTO `mentors` (`mentor_id`) VALUES ('4'), ('6'), ('8'), ('9'), ('10');

INSERT INTO `mentees` (`mentee_id`) VALUES ('4'), ('6'), ('8');

INSERT INTO `moderators` (`moderator_id`) VALUES ('16'), ('7'), ('5'), ('2');

INSERT INTO `records` (`student_id`, `grade`, `sec_id`) VALUES ('22', '9', '8'), ('22', '7', '5');