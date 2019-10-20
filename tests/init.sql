CREATE DATABASE project;

\c project;

CREATE VIEW test as SELECT generate_series(1, 100, 1) as id;
CREATE VIEW test2 as SELECT generate_series(1, 2, 1) as id;
CREATE VIEW test1 as SELECT generate_series(1, 1, 1) as id;
CREATE VIEW test0 as SELECT * FROM (SELECT generate_series(1, 1, 1) as id) t WHERE id =2;