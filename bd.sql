create table reviews
(
	id int auto_increment,
	name varchar(255) not null,
	email varchar(255) not null,
	text text not null,
	date datetime default current_timestamp null,
	constraint reviews_pk
		primary key (id)
);

create index reviews_email_index
	on rewiews (email);