CREATE DATABASE eval_doc;
USE eval_doc;

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE stats(
idUser INT NOT NULL,
FOREIGN KEY (idUser) REFERENCES users(id),
score DOUBLE NOT NULL
);

DROP TABLE users;
DROP TABLE evalacion;

INSERT INTO users (usuario, email, password) VALUES ("ejemplo", "ejemplo@email.com", PASSWORD("123456"));
DELETE FROM users WHERE id = 1;
SELECT * FROM users;