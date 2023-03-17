delimiter //
CREATE TRIGGER gerente_limit
BEFORE INSERT ON user
FOR EACH ROW
BEGIN
    IF NEW.role_id = 1 AND (SELECT COUNT(*) FROM user WHERE role_id = 1) >= 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo se permite un gerente.';
    END IF;
END;//
delimiter ;

delimiter //
CREATE TRIGGER supervisor_limit
BEFORE INSERT ON user
FOR EACH ROW
BEGIN
    IF NEW.role_id = 2 AND (SELECT COUNT(*) FROM user WHERE role_id = 2) >= 3 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo se permite un máximo de 3 supervisores.';
    END IF;
END;//
delimiter ;
