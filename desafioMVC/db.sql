create table users (
    id int auto_increment primary key, 
    name varchar(245) not null, 
    email varchar(245) not null unique, 
    password varchar(245) not null
); 

create table posts ( 
    id int auto_increment primary key, 
    title varchar(245) not null, 
    description varchar(245) not null, 
    img_url varchar(245), 
    created_at timestamp default current_timestamp, 
    author int, 
    foreign key (author) references users(id) 
);
