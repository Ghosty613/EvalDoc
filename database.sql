CREATE DATABASE eval_doc;
USE eval_doc;

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('alumno', 'docente', 'administrador') NOT NULL
);

-- token para el input en docentes.html
CREATE TABLE docentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    materia VARCHAR(255),
    token VARCHAR(50)
);

CREATE TABLE asignaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_docente INT NOT NULL,
    id_periodo INT NOT NULL,
    FOREIGN KEY (id_alumno) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_docente) REFERENCES docentes(id) ON DELETE CASCADE,
    FOREIGN KEY (id_periodo) REFERENCES periodos(id) ON DELETE CASCADE
);

CREATE TABLE evaluacion (
    idEval INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idUser INT NOT NULL FOREIGN KEY (idUser) REFERENCES users(id),
    periodo VARCHAR(50),
    puntaje DOUBLE NOT NULL,
);

CREATE TABLE periodos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE
);

--DROP TABLE users;
--DROP TABLE evalacion;

SELECT *
FROM users;

SELECT *
FROM docentes;

TRUNCATE TABLE users;

DELETE FROM users
WHERE id > 0;

INSERT INTO users (usuario, email, password, tipo_usuario)
VALUES (
        'docente',
        'docente@itcv.com',
        '4Au*Y0R1${3W',
        'docente'
    ),
    (
        'admin',
        'admin@itcv.com',
        ':a9Iu#!+2706',
        'administrador'
    );

INSERT INTO docentes (nombre, materia, token)
VALUES (
        'Doria Gallegos Dora Emma',
        'Ing. de Software',
        '>}dsJI@3ZwO4VD=A-0YP6hjDnqe&nt'
    );