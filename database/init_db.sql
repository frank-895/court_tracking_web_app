DROP DATABASE IF EXISTS court_tracking_system;
CREATE DATABASE court_tracking_system;
USE court_tracking_system;

DROP TABLE IF EXISTS charge;
DROP TABLE IF EXISTS caserecord;
DROP TABLE IF EXISTS defendant;
DROP TABLE IF EXISTS lawyer;
DROP TABLE IF EXISTS case_lawyer;
DROP TABLE IF EXISTS court_event;

CREATE TABLE defendant (
    defendant_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Date_of_Birth DATE NOT NULL,
    Address TEXT,
    Ethnicity VARCHAR(255),
    Phone_Number VARCHAR(15),
    Email VARCHAR(255),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE caserecord (
    case_ID INT AUTO_INCREMENT PRIMARY KEY,
    defendant_ID INT NOT NULL,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (defendant_ID) REFERENCES defendant(defendant_ID) ON DELETE CASCADE
);

CREATE TABLE charge (
    charge_ID INT AUTO_INCREMENT PRIMARY KEY,
    case_ID INT NOT NULL,
    Description TEXT,
    Status VARCHAR(50),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (case_ID) REFERENCES caserecord(case_ID) ON DELETE CASCADE
);

CREATE TABLE lawyer (
    lawyer_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255),
    Phone_Number VARCHAR(15),
    Firm VARCHAR(255),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE case_lawyer (
    case_ID INT NOT NULL,
    lawyer_ID INT NOT NULL,
    PRIMARY KEY (case_ID, lawyer_ID),
    FOREIGN KEY (case_ID) REFERENCES caserecord(case_ID) ON DELETE CASCADE,
    FOREIGN KEY (lawyer_ID) REFERENCES lawyer(lawyer_ID) ON DELETE CASCADE
);

CREATE TABLE court_event (
    Event_ID INT AUTO_INCREMENT PRIMARY KEY,
    case_ID INT NOT NULL,
    Location VARCHAR(255) NOT NULL,
    Description VARCHAR(255) NOT NULL,
    Date DATE NOT NULL,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (case_ID) REFERENCES caserecord(case_ID) ON DELETE CASCADE
);

-- authentication

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

-- logs
CREATE TABLE logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);