
CREATE TABLE news (
  newsId INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  userId INTEGER NOT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  description TEXT NOT NULL,
  keywords TEXT,
  layout VARCHAR(255) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  lead TEXT,
  pageHits INTEGER(10) NOT NULL,
  dateCreated TEXT(128) NOT NULL,
  dateModified TEXT(128) NOT NULL,
  FOREIGN KEY (userId) REFERENCES user (userId) ON DELETE RESTRICT
);
CREATE UNIQUE INDEX newsId ON news (newsId ASC);
