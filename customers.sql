-- Query for creating customers table
CREATE TABLE customers (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(256) NOT NULL,
    balance decimal(10,2) NOT NULL,
    email varchar(250) NOT NULL,
    password char(16) NOT NULL,
    status int(10) unsigned NOT NULL DEFAULT 0,
    security_question varchar(250) DEFAULT NULL,
    confirm_code varchar(32) DEFAULT NULL,
    profile_id int(11) DEFAULT NULL,
    level char(3) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (email)
);

-- Query for inserting data into customer table
INSERT INTO customers (name, balance, email, password, status, security_question, confirm_code, profile_id, level)
VALUES ('John Doe', '100.30', 'johndoe@gmail.com', 'johndoepass', '1', 'forgot the question', 'X0X0X0X0', '1', 'XOD');