

-- TABLES

DROP TABLE IF EXISTS answer;
DROP TABLE IF EXISTS choice;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS questionnaire;
DROP TABLE IF EXISTS user;

-- reparer
	
CREATE TABLE user
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

CREATE TABLE questionnaire
(
	questionnaire_id INTEGER AUTO_INCREMENT NOT NULL,
    questionnaire_user_id INTEGER NOT NULL,
    questionnaire_title TEXT NOT NULL,
    questionnaire_description TEXT NOT NULL,
    questionnaire_start_date INTEGER UNSIGNED NOT NULL,
    questionnaire_end_date INTEGER UNSIGNED NOT NULL,
	questionnaire_notation_rule INTEGER UNSIGNED NOT NULL,
    PRIMARY KEY (questionnaire_id),
    FOREIGN KEY (questionnaire_user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

CREATE TABLE question
(
	question_id INTEGER AUTO_INCREMENT NOT NULL,
    question_questionnaire_id INTEGER NOT NULL,
    question_num INTEGER NOT NULL,
    question_content varchar(255) NOT NULL,
    question_type enum('checkbox','radio') NOT NULL,
	question_hint TEXT,
    question_weight enum('1', '2', '3', '4', '5') NOT NULL DEFAULT 1,
    PRIMARY KEY (question_id),
    FOREIGN KEY (question_questionnaire_id) REFERENCES questionnaire(questionnaire_id) ON DELETE CASCADE
);

CREATE TABLE choice
(
	choice_id INTEGER AUTO_INCREMENT NOT NULL,
    choice_question_id INTEGER NOT NULL,
    choice_content varchar(255) NULL,
    choice_status BOOLEAN NOT NULL,
    PRIMARY KEY (choice_id),
    FOREIGN KEY (choice_question_id) REFERENCES question(question_id) ON DELETE CASCADE
);

CREATE TABLE answer
(
	answer_id INTEGER AUTO_INCREMENT NOT NULL,
    answer_student_user_id INTEGER NOT NULL,
    answer_choice_id INTEGER NOT NULL,
    PRIMARY KEY (answer_id),
    FOREIGN KEY (answer_student_user_id) REFERENCES user(user_id) ON DELETE CASCADE, 
    FOREIGN KEY (answer_choice_id) REFERENCES choice(choice_id) ON DELETE CASCADE
);


-- INDEX


CREATE INDEX user_EMAIL_INDEX ON user(user_email);
CREATE INDEX user_PASSWORD_INDEX ON user(user_password);



INSERT INTO user (user_firstname, user_lastname, user_email, user_photo_path, user_schoolname, user_password)
VALUES ("Jean", "Mirandau", "jean-mirandau@example.com", "C:\\\\a", "IUT LYON 1", "20fe3d143653ef86b6c32a85ed24774898e62808"),
	("Tom", "Edgerie", "tom.edgerie@example.com", "C:\\\\b", "IUT LYON 1", "b364a56350e8067999a9c67da232be483f605697"),
	("Maria", "Rodriguez", "maria.rodriguez@example.com", "C:\\\\c", "IUT LYON 1", "78eb459c70acb7b4c94cd653107b0ffe683095e1");
    
INSERT INTO questionnaire (questionnaire_user_id, questionnaire_title, questionnaire_start_date, questionnaire_end_date)
VALUES (1, "Questionnaire test 1", 1450339471, 1481961871 );

INSERT INTO question (question_questionnaire_id, question_num, question_content, question_type, question_hint, question_weight)
VALUES (1, 1, "Quel est l'aliment qui n'est pas un légume ?", "checkbox", "Il est rouge !", 2),
		(1, 2, "Quelles sont les couleurs possibles d'un cheval ?", "radio", null, 1),
        (1, 3, "Quel mot est bien orthographé ?", "checkbox", null, 1),
        (1, 4, "La taille d'une molécule d'eau est de l'odre de : ", "checkbox", "Elle est toute petite !", 3);

INSERT INTO choice (choice_question_id, choice_content, choice_status)
VALUES (1, "Tomate", true),(1, "Carotte", false),(1, "Potiron", false), (1, "Choux-fleur", false),
		(2, "Noir", true),(2, "Rose", false),(2, "Brun", true),(2, "Bleu", false),
        (3, "Ellocution", false),(3, "Elocution", true),(3, "Elocusion", false),(3, "Elokution", false),
        (4, "0.0001 mm", false),(4, "1mm", false),(4, "0.1 mm", false),(4, "0.000001mm ", true);

INSERT INTO answer (answer_student_user_id, answer_choice_id)
VALUES (2, 1), (2, 7), (2, 16),
	(3, 1),(3, 5), (3, 7), (3, 10),(3,16);

