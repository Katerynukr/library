# The project is a CRUD that can write into database a books and authors where one author can have many books and a book can have many authors.

**The database should have three tables.**
| Authors| |                            
| :---: | :---: | 
| id | int(11)| 
| name | varcahr(64)| 
| surname | varcahr(64)|   

id: int(11)
title: varchar(255)
isbn: varchar(20)
pages: tinyint(4) unsigned
about: text
author_id : int(11)


| Books| |                            
| :---: | :---: | 
| id | int(11)| 
| title| varcahr(255)| 
| isbn| varcahr(20)|   
| pages| tinyint(4)|   
| about| text|   
| author_id | int(11)| 
***The connection between tables*** | **books.author_id *------> authors.id***

| Users| |                            
| :---: | :---: | 
| id | int(11)| 
| email| varcahr(64)| 
| pass| password(128)|   

**The project has several specifications:**
- add, deleate and modify data from database. 
- books have a field (drop down) from which it is possible to select a author
- book cannot be created without author
- author cannot be deleated if book is create with this author
- validation of inserted data
- filtration from created books by author
- sorting authors by name and surname
- sign up, sign in and sign out funtionality
- description field for book with WYSIWYG type redactor 
- responsive design of project
- protection agains SQL injection


### project was made with Symfony framework
