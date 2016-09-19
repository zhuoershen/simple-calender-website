* database server 

	- `54.68.72.28`
	
* database tables
	
	- `cynic_cal`
	<pre>
	CREATE TABLE events (
    	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    	event_name VARCHAR(40) NOT NULL,
    	created_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    	last_modified timestamp NULL,
    	event_time timestamp not null,
    	PRIMARY KEY (id),
    	user_id int REFERENCES users (id)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;
	</pre> 
	- `users`
	<pre>
	create table users(
    	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    	username VARCHAR(30) NOT NULL,
    	password Text not null,
    	created_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    	activated TINYINT unsigned NULL DEFAULT 1,
    	primary key(id, username)
)engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;
	</pre>
	
	
项目约定

* naming
	- all variable should look like: `black_board`, use underscore to connect to words.
	- try to avoid capital character in all the namings
	- function name should look like: `myFunction`, use capital to connect words
* string (to be determined)
	- use double quote for variable name and sentence
	- use single quote for char
	
	
known bugs:

- ~~刷新页面时候不能主动验证是否已经登录，会直接变为未登录的页面~~