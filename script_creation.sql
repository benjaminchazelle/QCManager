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
	
CREATE TABLE USER
(
	user_id INTEGER AUTO_INCREMENT NOT NULL,
    user_firstname TEXT NOT NULL,
    user_lastname TEXT NOT NULL,
    user_email char(255) UNIQUE NOT NULL,
    user_photo_path varchar(255) NOT NULL,
    user_schoolname TEXT NOT NULL,
    user_password char(255) NOT NULL,
    PRIMARY KEY (user_id)
);

CREATE TABLE QUESTIONNAIRE
(
	questionnaire_id INTEGER AUTO_INCREMENT NOT NULL,
    questionnaire_user_id INTEGER NOT NULL,
    questionnaire_title TEXT NOT NULL,
    questionnaire_start_date INTEGER UNSIGNED NOT NULL,
    questionnaire_end_date INTEGER UNSIGNED NOT NULL,
    PRIMARY KEY (questionnaire_id),
    FOREIGN KEY (questionnaire_user_id) REFERENCES USER(user_id) ON DELETE CASCADE
);

CREATE TABLE QUESTION
(
	question_id INTEGER AUTO_INCREMENT NOT NULL,
    question_questionnaire_id INTEGER NOT NULL,
    question_num INTEGER NOT NULL,
    question_content varchar(255) NOT NULL,
    question_type enum('checkbox','radiobutton') NOT NULL,
	question_hint TEXT,
    question_weight float NOT NULL DEFAULT 1,
    PRIMARY KEY (question_id),
    FOREIGN KEY (question_questionnaire_id) REFERENCES QUESTIONNAIRE(questionnaire_id) ON DELETE CASCADE
);

CREATE TABLE CHOICE
(
	choice_id INTEGER AUTO_INCREMENT NOT NULL,
    choice_question_id INTEGER NOT NULL,
    choice_content varchar(255) NULL,
    choice_status BOOLEAN NOT NULL,
    PRIMARY KEY (choice_id),
    FOREIGN KEY (choice_question_id) REFERENCES QUESTION(question_id) ON DELETE CASCADE
);

CREATE TABLE ANSWER
(
	answer_id INTEGER AUTO_INCREMENT NOT NULL,
    answer_student_user_id INTEGER NOT NULL,
    answer_choice_id INTEGER NOT NULL,
    PRIMARY KEY (answer_id),
    FOREIGN KEY (answer_student_user_id) REFERENCES USER(user_id) ON DELETE CASCADE, 
    FOREIGN KEY (answer_choice_id) REFERENCES CHOICE(choice_id) ON DELETE CASCADE
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
CREATE FUNCTION QUESTIONNAIRE_WEIGHTING(questionnaire_id integer) returns float
BEGIN
	DECLARE weight integer DEFAULT 0;
    
    SELECT SUM(question_weight)
    INTO weight
    FROM QUESTION
    WHERE QUESTION.question_questionnaire_id=questionnaire_id;
    
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
	AND QUESTION.question_questionnaire_id=_questionnaire_id
    AND choice_status = true
    AND question_id NOT IN (SELECT question_id
							FROM ANSWER
							INNER JOIN CHOICE
							ON ANSWER.answer_choice_id=CHOICE.choice_id
							INNER JOIN QUESTION
							ON CHOICE.choice_question_id=QUESTION.question_id
							WHERE ANSWER.answer_student_user_id=_student_id
							AND QUESTION.question_questionnaire_id=_questionnaire_id
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
VALUES ("Jean", "Mirandau", "jean-mirandau@example.com", "C:\\\\a", "IUT LYON 1", "20fe3d143653ef86b6c32a85ed24774898e62808"),
	("Tom", "Edgerie", "tom.edgerie@example.com", "C:\\\\b", "IUT LYON 1", "b364a56350e8067999a9c67da232be483f605697"),
	("Maria", "Rodriguez", "maria.rodriguez@example.com", "C:\\\\c", "IUT LYON 1", "78eb459c70acb7b4c94cd653107b0ffe683095e1");
    
INSERT INTO QUESTIONNAIRE (questionnaire_user_id, questionnaire_title, questionnaire_start_date, questionnaire_end_date)
VALUES (1, "Questionnaire test 1", 1450339471, 1481961871 );

INSERT INTO QUESTION (question_questionnaire_id, question_num, question_content, question_type, question_hint, question_weight)
VALUES (1, 1, "Quel est l'aliment qui n'est pas un légume ?", "checkbox", "Il est rouge !", 2),
		(1, 2, "Quelles sont les couleurs possibles d'un cheval ?", "radiobutton", null, 1),
        (1, 3, "Quel mot est bien orthographé ?", "checkbox", null, 1),
        (1, 4, "La taille d'une molécule d'eau est de l'odre de : ", "checkbox", "Elle est toute petite !", 3);

INSERT INTO CHOICE (choice_question_id, choice_content, choice_status)
VALUES (1, "Tomate", true),(1, "Carotte", false),(1, "Potiron", false), (1, "Choux-fleur", false),
		(2, "Noir", true),(2, "Rose", false),(2, "Brun", true),(2, "Bleu", false),
        (3, "Ellocution", false),(3, "Elocution", true),(3, "Elocusion", false),(3, "Elokution", false),
        (4, "0.0001 mm", false),(4, "1mm", false),(4, "0.1 mm", false),(4, "0.000001mm ", true);

INSERT INTO ANSWER (answer_student_user_id, answer_choice_id)
VALUES (2, 1), (2, 7), (2, 16),
	(3, 1),(3, 5), (3, 7), (3, 10),(3,16);



-- PB with next functions
-- SELECT GET_RESULT_STUDENT_QUESTIONNAIRE(2, 1);
-- SELECT GET_RESULT_STUDENT_QUESTIONNAIRE(3, 1);
-- SELECT QUESTIONNAIRE_WEIGHTING(1);
    