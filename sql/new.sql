
CREATE TABLE user_password (
    id_user int(11) NOT NULL,
    password varchar(255) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id)
);

CREATE TABLE user_admin (
    id_user int(11) NOT NULL,
    admin int(11) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id)
)