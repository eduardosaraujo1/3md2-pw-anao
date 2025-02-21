INSERT INTO users(login, password) VALUES
('eduardosaraujo1', '$2a$12$GdSWgz..u4K9ICDEJ5i8neKGsflW/2KGLjMFsHEPqeSKk1A3E6cfa'), -- admin01
('dwalin_the_brave', '$2a$12$rCwQbXYUFfhccQNw4Bk0B.kkq49mDbJe2xz8gtN5nq0CfLTgfwHi.'), -- Password
('thorin_oakenshield', '$2a$12$XzCRUwbnlQNfS0WTFrzZE.Up.mE6RQxYYqwc/ZpuxVoOwmybgNjEO'), -- johnny
('bofur_the_miner', '$2a$12$ok9.G7CO3agiw18V.IDH0uSL/IakjgqVMWAmx4ImKpAN6DXT6M9de'), -- A$$word
('balin_the_wise', '$2a$12$oxH7iZCNj1b9UYRSm8GqGeU99R32NGBcQoqwUDwibmeOGX1tkK2iS'); -- wisdomforever999

INSERT INTO anao(name, age, race, height, is_gay) VALUES
('Gimli', 139, 0, 1.35, 1),
('Dwalin', 150, 1, 1.40, 0),
('Thorin', 195, 3, 1.38, 1),
('Balin', 178, 2, 1.37, 1),
('Bofur', 160, 5, 1.36, 0);

INSERT INTO parceiro(name, contact, is_gay, is_anao, id_anao) VALUES
('Legolas', 'legolas@rivendell.com', 1, 0, 1),
('Balin', 'balin@erebor.com', 0, 1, 2),
('Bilbo Baggins', 'bilbo@thehobbiton.com', 1, 1, 3),
('Thranduil', 'thranduil@woodlandrealm.com', 0, 0, 1),
('Dáin Ironfoot', 'dain@ironhills.com', 1, 1, 3),
('Gloin', 'gloin@erebor.com', 1, 0, 4),
('Fili', 'fili@erebor.com', 0, 1, 5),
('Kili', 'kili@erebor.com', 1, 1, 5),
('Beorn', 'beorn@carrock.com', 0, 1, 2),
('Radagast', 'radagast@mirwood.com', 1, 0, 4);