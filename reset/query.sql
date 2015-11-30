-- DATABASE

/*
DROP DATABASE IF EXISTS QUESTIONNAIRE;
CREATE DATABASE QUESTIONNAIRE;
*/

-- TABLES


DROP TABLE IF EXISTS USER;
DROP TABLE IF EXISTS QUESTIONNAIRE;
DROP TABLE IF EXISTS QUESTION;
DROP TABLE IF EXISTS CHOICE;
DROP TABLE IF EXISTS ANSWER;


-- Insert unhashed password, and no salt
CREATE TABLE USER
(
	user_id INTEGER AUTO_INCREMENT NOT NULL,
    user_firstname TEXT NOT NULL,
    user_lastname TEXT NOT NULL,
    user_email char(255) UNIQUE NOT NULL,
    user_schoolname TEXT NOT NULL,
    user_password char(255) NOT NULL,
    user_salt char(5),
    user_rank enum('professor','student') NOT NULL,
    PRIMARY KEY (user_id)
);

CREATE TABLE QUESTIONNAIRE
(
	questionnaire_id INTEGER AUTO_INCREMENT NOT NULL,
    questionnaire_professor_user_id INTEGER NOT NULL,
    questionnaire_title TEXT NOT NULL,
    questionnaire_start_date datetime NOT NULL,
    questionnaire_end_date datetime NOT NULL,
    PRIMARY KEY (questionnaire_id),
    FOREIGN KEY (questionnaire_professor_user_id) REFERENCES USER(user_id)
);

CREATE TABLE QUESTION
(
	question_id INTEGER AUTO_INCREMENT NOT NULL,
    questionnaire_id INTEGER NOT NULL,
    question_content varchar(255) NOT NULL,
    question_type enum('checkbox','radiobutton') NOT NULL,
    question_weight float NOT NULL DEFAULT 1,
    PRIMARY KEY (question_id),
    FOREIGN KEY (questionnaire_id) REFERENCES QUESTIONNAIRE(questionnaire_id),
    CONSTRAINT unique_question UNIQUE(questionnaire_id, question_content)
);

CREATE TABLE CHOICE
(
	choice_id INTEGER AUTO_INCREMENT NOT NULL,
    choice_question_id INTEGER NOT NULL,
    choice_content varchar(255) NULL,
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
CREATE FUNCTION GENERATE_RANDOM_CHAR() returns char(1)
BEGIN
	DECLARE random_number integer;
    SELECT TRUNCATE(rand()*62, 0) INTO random_number; -- 26+26+10
	IF(random_number < 26) THEN
		return CHAR(random_number+97); -- 'a'
    ELSEIF(random_number < 52) THEN
		return CHAR(random_number+39); -- 'A'
    END IF;
	return CHAR(random_number-4); -- 'O'
END// 
delimiter ;

delimiter // 
CREATE FUNCTION GENERATE_RANDOM_WORD(word_size integer) returns varchar(255)
BEGIN
	DECLARE word VARCHAR(255) DEFAULT '';
    
    label_for: LOOP
        IF word_size < 1 THEN
			LEAVE label_for;
		END IF;
        SET word_size = word_size - 1;
        SET word = CONCAT(word, GENERATE_RANDOM_CHAR());
    END LOOP label_for;

    return word;   
END// 
delimiter ;

delimiter // 
CREATE FUNCTION CREATE_HASHED_PASSWORD(original_password char(255), salt char(5)) returns char(255)
BEGIN
	-- return SHA2(CONCAT(original_password, salt), 224);
    return PASSWORD(CONCAT(original_password, salt));
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
	AND QUESTION.questionnaire_id=_questionnaire_id;
    
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


-- TRIGGERS


DROP TRIGGER IF EXISTS SALT_TRIGGER;


delimiter // 
CREATE TRIGGER SALT_TRIGGER 
BEFORE INSERT ON USER
FOR EACH ROW
BEGIN
	SET new.user_salt = GENERATE_RANDOM_WORD(5);
	SET new.user_password = CREATE_HASHED_PASSWORD(new.user_password, new.user_salt);
END//
delimiter ;


-- TEST VALUES


INSERT INTO USER (user_firstname, user_lastname, user_email, user_schoolname, user_password, user_rank)
VALUES ("prof1_f", "prof1_l", "prof@example.com", "IUT LYON 1", "mdp1", "professor"),
	("elev1_f", "elev1_l", "eleve1@example.com", "IUT LYON 1", "mdp2", "student"),
	("elev2_f", "elev2_l", "eleve2@example.com", "IUT LYON 1", "mdp3", "student");
    
INSERT INTO QUESTIONNAIRE (questionnaire_professor_user_id, questionnaire_title, questionnaire_start_date, questionnaire_end_date)
VALUES (1, "Questionnaire test 1", NOW(), ADDDATE(NOW(), INTERVAL 3 DAY));

INSERT INTO QUESTION (questionnaire_id, question_content, question_type, question_weight)
VALUES (1, "Couleur du cheval blanc d'Henri IV", "checkbox", 1),
	(1, "Couleur du cheval noir d'Henri V", "checkbox", 1),
	(1, "Couleur du cheval rouge d'Henri VI", "checkbox", 1),
    (1, "Couleur possible d'un cheval", "radiobutton", 1),
    (1, "Couleur d'une pastèque", "radiobutton", 1);

INSERT INTO CHOICE (choice_question_id, choice_content, choice_status)
VALUES (1, "Noir", false),(1, "Blanc", true),(1, "Rouge", false),
	(2, "Noir", true),(2, "Bleu", false),(2, "Violet", false),
    (3, "Noir", false),(3, "Blanc", false),(3, "Rouge", true),
    (4, "Jaune", true),(4, "Blanc", true),(4, "Rouge", false),
    (5, "Noir", false),(5, "Blanc", false),(5, "Rouge", true),(5, "Vert", true);

INSERT INTO ANSWER (answer_student_user_id, answer_choice_id)
VALUES (2, 4), (2, 10), (2,11), (2,15),(2,16),
	(3, 2),(3, 4), (3, 10), (3,11),(3,16);


SELECT GET_RESULT_STUDENT_QUESTIONNAIRE(2, 1);
CALL ADD_ANSWER(2, 2, "Noir", true);
SELECT GET_RESULT_STUDENT_QUESTIONNAIRE(2, 1);