-- DATABASE


DROP DATABASE IF EXISTS QUESTIONNAIRE;
CREATE DATABASE QUESTIONNAIRE;
USE QUESTIONNAIRE;


-- TABLES


DROP TABLE IF EXISTS USER;
DROP TABLE IF EXISTS QUESTIONNAIRE;
DROP TABLE IF EXISTS QUESTION;
DROP TABLE IF EXISTS CHOICE;
DROP TABLE IF EXISTS ANSWER;

-- reparer

-- Insert unhashed password, and no salt
CREATE TABLE USER
(
	user_id INTEGER AUTO_INCREMENT NOT NULL,
    user_firstname TEXT NOT NULL,
    user_lastname TEXT NOT NULL,
    user_email char(255) UNIQUE NOT NULL,
    user_photo_path varchar(255) UNIQUE NOT NULL,
    user_schoolname TEXT NOT NULL,
    user_password char(255) NOT NULL,
    user_salt char(5),
    PRIMARY KEY (user_id)
);

CREATE TABLE QUESTIONNAIRE
(
	questionnaire_id INTEGER AUTO_INCREMENT NOT NULL,
    questionnaire_professor_user_id INTEGER NOT NULL,
    questionnaire_title TEXT NOT NULL,
    questionnaire_start_date INTEGER UNSIGNED NOT NULL,
    questionnaire_end_date INTEGER UNSIGNED NOT NULL,
    PRIMARY KEY (questionnaire_id),
    FOREIGN KEY (questionnaire_professor_user_id) REFERENCES USER(user_id)
);

CREATE TABLE QUESTION
(
	question_id INTEGER AUTO_INCREMENT NOT NULL,
    questionnaire_id INTEGER NOT NULL,
    question_num INTEGER NOT NULL,
    question_content varchar(255) NOT NULL,
    question_type enum('checkbox','radiobutton') NOT NULL,
    question_weight float NOT NULL DEFAULT 1,
    PRIMARY KEY (question_id),
    FOREIGN KEY (questionnaire_id) REFERENCES QUESTIONNAIRE(questionnaire_id),
    CONSTRAINT unique_question UNIQUE(questionnaire_id, question_content),
	CONSTRAINT unique_question_num UNIQUE(questionnaire_id, question_num)
);

CREATE TABLE CHOICE
(
	choice_id INTEGER AUTO_INCREMENT NOT NULL,
    choice_question_id INTEGER NOT NULL,
    choice_content varchar(255) NULL,
    choice_hint TEXT,
    choice_status BOOLEAN NOT NULL,
    PRIMARY KEY (choice_id),
    FOREIGN KEY (choice_question_id) REFERENCES QUESTION(question_id),
    CONSTRAINT unique_answer_question UNIQUE(choice_question_id, choice_content)
);

CREATE TABLE ANSWER
(
	answer_id INTEGER AUTO_INCREMENT NOT NULL,
    answer_student_user_id INTEGER NOT NULL,
    answer_choice_id INTEGER NOT NULL,
    PRIMARY KEY (answer_id),
    FOREIGN KEY (answer_student_user_id) REFERENCES USER(user_id),
    FOREIGN KEY (answer_choice_id) REFERENCES CHOICE(choice_id),
    CONSTRAINT unique_answer_student UNIQUE(answer_student_user_id, answer_choice_id)
);


-- INDEX


CREATE INDEX USER_EMAIL_INDEX ON USER(user_email);
CREATE INDEX USER_PASSWORD_INDEX ON USER(user_password);


-- FUNCTIONS


DROP FUNCTION IF EXISTS GENERATE_RANDOM_CHAR;
DROP FUNCTION IF EXISTS GENERATE_RANDOM_WORD;
DROP FUNCTION IF EXISTS CREATE_HASHED_PASSWORD;
DROP FUNCTION IF EXISTS USER_EXIST;
DROP FUNCTION IF EXISTS QUESTIONNAIRE_WEIGHTING;
DROP FUNCTION IF EXISTS GET_POINTS_PER_TRUE_ANSWER_QUESTION;
DROP FUNCTION IF EXISTS GET_RESULT_STUDENT_QUESTIONNAIRE;
DROP PROCEDURE IF EXISTS ADD_ANSWER;

delimiter // 
CREATE FUNCTION CREATE_HASHED_PASSWORD(original_password char(255), salt char(5)) returns char(255)
BEGIN
	-- return SHA2(CONCAT(original_password, salt), 224);
    -- return PASSWORD(CONCAT(original_password, salt));
    return SHA1(CONCAT(original_password, salt));
END// 
delimiter ;

-- Return -1 if user couln't be found, his id otherwise
delimiter // 
CREATE FUNCTION USER_EXIST(email char(255), password char(255)) returns integer
BEGIN
	DECLARE id_user integer DEFAULT -1;
    
    SELECT user_id
    INTO id_user
    FROM USER
    WHERE USER.user_email=email
    AND USER.user_password = CREATE_HASHED_PASSWORD(password, USER.user_salt);
    
    return id_user;
END// 
delimiter ;

delimiter // 
CREATE FUNCTION QUESTIONNAIRE_WEIGHTING(questionnaire_id integer) returns float
BEGIN
	DECLARE weight integer DEFAULT 0;
    
    SELECT SUM(question_weight)
    INTO weight
    FROM QUESTION
    WHERE QUESTION.questionnaire_id=questionnaire_id;
    
    IF(weight IS NULL) THEN
		return 0;
	END IF;
    
    return weight;
END// 
delimiter ;

delimiter // 
CREATE FUNCTION GET_POINTS_PER_TRUE_ANSWER_QUESTION(_question_id integer) returns float
BEGIN
	DECLARE points float DEFAULT 0;
    
    SELECT question_weight/count(*)
    INTO points
	FROM QUESTION
	INNER JOIN CHOICE
	ON QUESTION.question_id=CHOICE.choice_question_id
	WHERE choice_status=true
	AND question_id=_question_id
	GROUP BY question_id;
    
    IF(points IS NULL) THEN
		return 0; -- SHOULD'T ARRIVE
	END IF;
   
    return points;
END// 
delimiter ;

delimiter // 
CREATE FUNCTION GET_RESULT_STUDENT_QUESTIONNAIRE(_student_id integer, _questionnaire_id integer) returns float
BEGIN
	DECLARE result float DEFAULT 0;
    
    SELECT SUM(question_weight * GET_POINTS_PER_TRUE_ANSWER_QUESTION(QUESTION.question_id))
    INTO result
    FROM ANSWER
	INNER JOIN CHOICE
	ON ANSWER.answer_choice_id=CHOICE.choice_id
	INNER JOIN QUESTION
	ON CHOICE.choice_question_id=QUESTION.question_id
	WHERE ANSWER.answer_student_user_id=_student_id
	AND QUESTION.questionnaire_id=_questionnaire_id
    AND choice_status = true
    AND question_id NOT IN (SELECT question_id
							FROM ANSWER
							INNER JOIN CHOICE
							ON ANSWER.answer_choice_id=CHOICE.choice_id
							INNER JOIN QUESTION
							ON CHOICE.choice_question_id=QUESTION.question_id
							WHERE ANSWER.answer_student_user_id=_student_id
							AND QUESTION.questionnaire_id=_questionnaire_id
							AND choice_status = false);
    
    IF(result IS NULL) THEN
		return 0; -- SHOULD'T ARRIVE
	END IF;
   
    return result;
END// 
delimiter ;

delimiter // 
CREATE PROCEDURE ADD_ANSWER(IN _student_id integer, IN question_id integer, IN choice_content text, IN answer boolean)
BEGIN
	DECLARE result BOOLEAN DEFAULT 0;
    
    IF (answer = true) THEN
		SELECT c.choice_status
        INTO result
		FROM CHOICE c
		WHERE c.choice_question_id=question_id
		AND c.choice_content=choice_content;
        IF (result = true) THEN
			INSERT INTO ANSWER (answer_student_user_id, answer_choice_id)
            VALUES (_student_id, question_id);
		END IF;
    END IF;
    
END// 
delimiter ;

-- TEST VALUES


INSERT INTO USER (user_firstname, user_lastname, user_email, user_photo_path, user_schoolname, user_password)
VALUES ("prof1_f", "prof1_l", "prof@example.com", "C:\\\\a", "IUT LYON 1", "mdp1"),
	("elev1_f", "elev1_l", "eleve1@example.com", "C:\\\\b", "IUT LYON 1", "mdp2"),
	("elev2_f", "elev2_l", "eleve2@example.com", "C:\\\\c", "IUT LYON 1", "mdp3");
    
INSERT INTO QUESTIONNAIRE (questionnaire_professor_user_id, questionnaire_title, questionnaire_start_date, questionnaire_end_date)
VALUES (1, "Questionnaire test 1", 1448459754, 1448499754);

INSERT INTO QUESTION (questionnaire_id, question_num, question_content, question_type, question_weight)
VALUES (1, 1, "Couleur du cheval blanc d'Henri IV", "checkbox", 1),
	(1, 2, "Couleur du cheval noir d'Henri V", "checkbox", 1),
	(1, 3, "Couleur du cheval rouge d'Henri VI", "checkbox", 1),
    (1, 4, "Couleur possible d'un cheval", "radiobutton", 1),
    (1, 5, "Couleur d'une pastèque", "radiobutton", 1);

INSERT INTO CHOICE (choice_question_id, choice_content, choice_hint, choice_status)
VALUES (1, "Noir", null ,false),(1, "Blanc", null, true),(1, "Rouge", null, false),
	(2, "Noir", null, true),(2, "Bleu", null, false),(2, "Violet", null, false),
    (3, "Noir", null, false),(3, "Blanc", null, false),(3, "Rouge", null, true),
    (4, "Jaune", null, true),(4, "Blanc", null, true),(4, "Rouge", null, false),
    (5, "Noir", null, false),(5, "Blanc", null, false),(5, "Rouge", null, true),(5, "Vert", null, true);

INSERT INTO ANSWER (answer_student_user_id, answer_choice_id)
VALUES (2, 4), (2, 5), (2, 10), (2,11), (2,15),(2,16),
	(3, 2),(3, 4), (3, 10), (3,11),(3,16);


SELECT GET_RESULT_STUDENT_QUESTIONNAIRE(2, 1);
CALL ADD_ANSWER(2, 2, "Noir", true);
SELECT GET_RESULT_STUDENT_QUESTIONNAIRE(2, 1);

    
    
    
    