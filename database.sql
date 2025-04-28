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

CREATE TABLE alumno_docente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    docente_id INT NOT NULL,
    UNIQUE KEY (alumno_id, docente_id),
    FOREIGN KEY (alumno_id) REFERENCES users(id),
    FOREIGN KEY (docente_id) REFERENCES docentes(id)
);

CREATE TABLE evaluaciones (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_docente INT NOT NULL,
    periodo VARCHAR(50),
    calificacion DOUBLE NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_alumno) REFERENCES users(id),
    FOREIGN KEY (id_docente) REFERENCES docentes(id)
);

--DROP TABLE users;
--DROP TABLE evalacion;
DROP TABLE docentes;
DROP TABLE alumno_docente;

SELECT *
FROM users;

SELECT *
FROM docentes;

SELECT * FROM periodos;

SELECT * FROM alumno_docente;

SELECT * FROM evaluaciones;

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

INSERT INTO docentes (nombre, materia, token)
VALUES (
    'Juan Pérez',
    'Estructura de Datos',
    'aB3@x!2KdR#7LpQ^uT0vZ9mEwY$'
);