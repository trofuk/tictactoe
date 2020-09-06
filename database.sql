 CREATE TABLE game (
   uuid varchar(100) NOT NULL,
   board varchar(100) NOT NULL,
   status varchar(100) NOT NULL,
   PRIMARY KEY (uuid)
 );
INSERT INTO `game` (`uuid`, `board`, `status`) VALUES
('5f538b8cb5ce8', 'X00X----X', 'RUNNING'),
('5f53ba8b53346', '---------', 'RUNNING'),
('5f5495b47ce80', 'X0X0XX0X0', 'DRAW'),
('5f54d8f8f28c2', '---------', 'RUNNING'),
('5f54d93da63f4', '---------', 'RUNNING'),
('5f54e5fcb9259', '0---X----', 'RUNNING'),
('5f54e71994121', '0---X----', 'RUNNING'),
('5f54e7ba7c323', '0----X---', 'RUNNING'),
('5f54e7ee9522f', '0---X----', 'RUNNING'),
('5f54e84e1e756', '000-X-XX-', 'O_WON'),
('5f54e8f8430ce', '000-X--XX', 'O_WON'),
('5f54ea49b5a4f', '0---X----', 'RUNNING'),
('5f54eace90d94', '000-X--XX', 'O_WON'),
('5f54fe412ff81', '00X-X-X--', 'X_WON'),
('5f550ae80155f', '000-X-X-X', 'O_WON');