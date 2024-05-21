Before running anything, create a database called "iot" and run the following code:

CREATE TABLE IF NOT EXISTS modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL,
    value INT NOT NULL,
    operating_time INT NOT NULL
);

CREATE TABLE IF NOT EXISTS module_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    value INT NOT NULL,
    timestamp DATETIME NOT NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id)
 );
