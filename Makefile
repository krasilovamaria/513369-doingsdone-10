up:
	docker run -d\
		--name mysql_database_krasilova\
		-e MYSQL_ADMIN_PASSWORD=rootpass\
		-p 3366:3306\
		ravensys/mysql:5.7-centos7

stop:
	docker stop mysql_database_krasilova
