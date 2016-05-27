DROP TABLE QuestionFactTable;
DROP TABLE AnswerTable;
DROP TABLE InfoRequesterTable;
DROP TABLE CategoryTable;
DROP TABLE DataTable;

CREATE TABLE QuestionFactTable(
	Question_ID INTEGER NOT NULL AUTO_INCREMENT,
	Question_Wavefile VARCHAR(50),
	Date_ID INTEGER,
	Answer_ID INTEGER,
	Category_ID INTEGER,
	Info_req_ID INTEGER,
    PRIMARY KEY (Question_ID),
    FOREIGN KEY (Answer_ID) REFERENCES AnswerTable(Answer_ID),
    FOREIGN KEY (Date_ID) REFERENCES DataTable(Date_ID),
    FOREIGN KEY (Category_ID) REFERENCES CategoryTable(Category_ID),
    FOREIGN KEY (Info_req_ID) REFERENCES InfoRequesterTable(Info_req_ID)
);

CREATE TABLE AnswerTable(
	Answer_ID INTEGER NOT NULL AUTO_INCREMENT,
	Answer_Wavefile VARCHAR(50)
);

CREATE TABLE DataTable(
	Date_ID INTEGER NOT NULL AUTO_INCREMENT,
    Years INTEGER,
	Months INTEGER,
    Weeks INTEGER,
    Days INTEGER,
    startDate DATE,
    endDate DATE,
	PRIMARY KEY (Date_ID)
);

CREATE TABLE CategoryTable(
	Category_ID INTEGER,  -- Values of 1, 2, 3, 4, 5, 6 Rain, weather, harv, seed, animal, others
    Category_wavefile_ID VARCHAR(50),
    PRIMARY KEY (Category_wavefile_ID)
);

CREATE TABLE InfoRequesterTable(
	info_req_ID INTEGER NOT NULL AUTO_INCREMENT,
	Requester_teleponenr VARCHAR(50), -- Don't change to Integer, Int size (-2,147,483,648) to (2,147,483,647) phonnr is over
    PRIMARY KEY (info_req_ID)
);

SELECT * FROM QuestionFactTable;
SELECT * FROM AnswerTable;
SELECT * FROM DataTable;
SELECT * FROM CategoryTable;
SELECT * FROM InfoRequesterTable;

