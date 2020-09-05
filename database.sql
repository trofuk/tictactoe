 CREATE TABLE game (
   uuid varchar(100) NOT NULL,
   board varchar(100) NOT NULL,
   status varchar(100) NOT NULL,
   PRIMARY KEY (uuid)
 );
 INSERT INTO game (uuid, board, status)
     VALUES  ('3fa85f64-5717-4562-b3fc-2c963f66afa6',  'XO--X--OX', 'RUNNING');
 INSERT INTO game (uuid, board, status)
     VALUES  ('3fa85f64-5717-4562-b3fc-2c963f77afa6',  '---------', 'DRAW');