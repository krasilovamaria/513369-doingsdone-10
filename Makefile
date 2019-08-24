build:
	docker run -d\
		--name mysql_database_krasilova\
		-e MYSQL_ADMIN_PASSWORD=rootpass\
		-p 3366:3306\
		ravensys/mysql:5.7-centos7

up:
	docker start mysql_database_krasilova

stop:
	docker stop mysql_database_krasilova

