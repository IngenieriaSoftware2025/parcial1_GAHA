create TABLE libros (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255),
    fecha_registro DATETIME YEAR TO SECOND,
    situacion SMALLINT DEFAULT 1
);

create TABLE estados_prestamo (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion LVARCHAR(100),
    situacion SMALLINT DEFAULT 1
);

create TABLE prestamos (
    id SERIAL PRIMARY KEY,
    id_libro INTEGER NOT NULL,
    id_estado INTEGER NOT NULL,
    fecha_prestamo DATETIME YEAR TO SECOND,
    fecha_devolucion DATETIME YEAR TO SECOND,
    observaciones VARCHAR(255),
    situacion SMALLINT DEFAULT 1, 
    FOREIGN KEY (id_libro) REFERENCES libros(id),
    FOREIGN KEY (id_estado) REFERENCES estados_prestamo(id)
);

INSERT INTO estados_prestamo (id, nombre, descripcion, situacion) VALUES 
(1, 'PRESTADO', 'Libro prestado y pendiente de devolución', 1);
INSERT INTO estados_prestamo (id, nombre, descripcion, situacion) VALUES (2, 'DEVUELTO', 'Libro devuelto correctamente', 1);