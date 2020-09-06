 CREATE TABLE game (
   uuid varchar(100) NOT NULL,
   board varchar(100) NOT NULL,
   status varchar(100) NOT NULL,
   PRIMARY KEY (uuid)
 );
 INSERT INTO game (uuid, board, status)
     VALUES  ('5f538b8cb5ce8',  'X0-X----X', 'RUNNING');
 INSERT INTO game (uuid, board, status)
     VALUES  ('5f53ba8b53346',  '---------', 'RUNNING');
 INSERT INTO game (uuid, board, status)
     VALUES  ('5f5495b47ce80',  'X0X0XX0X0', 'DRAW');