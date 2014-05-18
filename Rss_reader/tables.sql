create table users(
    username varchar (50) primary key,
    password varchar(50)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

create table user_feed(
    username varchar (50),
    url varchar (300),
    feedname varchar (300),
    PRIMARY KEY(username,url),
    CONSTRAINT uf_username FOREIGN KEY (username) REFERENCES users (username)  
)ENGINE=InnoDB DEFAULT CHARSET=latin1;
