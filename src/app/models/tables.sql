CREATE TABLE user_role (
    id INT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    role_id INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (role_id) REFERENCES user_role(id)
);
