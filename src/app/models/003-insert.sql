-- Creates the roles.
INSERT INTO user_role (role) VALUES ('gerente'), ('supervisor'), ('cliente');

-- Creates the manager.
INSERT INTO user (role_id, username, phone, email, password)
VALUES ((SELECT user_role.id FROM user_role WHERE user_role.role = 'gerente'), 'gerente', '12345', 'gerente@gerente.com', 'gerente');