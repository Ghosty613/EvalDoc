CREATE DATABASE eval_doc;
USE eval_doc;
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('alumno', 'docente', 'administrador') NOT NULL
);
CREATE TABLE evaluacion (
    idEval INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idUser INT NOT NULL FOREIGN KEY (idUser) REFERENCES users(id),
    score DOUBLE NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    periodo VARCHAR(50) NOT NULL,
);
DROP TABLE users;
--DROP TABLE evalacion;
-- Usuario de prueba
SELECT *
FROM users;

DELETE FROM users WHERE id > 0;