## What is coregen

Coregen generates corelib files in a consistent way.


## Install

Install using composer

```
$composer install
```

Create a config.php in the includes dir see sampleConfig.php in that folder

## How to use it

To generate files you go through the  run 

```
$ ./corelibgen.php --name User --dir ~/projects/myapp/lib/MyApp/ --namespace "\MyApp\User" --table user

Created /home/someuser/projects/myapp/lib/MyApp/User/Model/UserCollection.php
Created /home/someuser/projects/myapp/lib/MyApp/User/Model/UserBO.php
Created /home/someuser/projects/myapp/lib/MyApp/User/UserFactory.php
Created /home/someuser/projects/myapp/lib/MyApp/User/Data/UserDAOInterface.php
Created /home/someuser/projects/myapp/lib/MyApp/User/Data/UserDAOMySQL.php
Created /home/someuser/projects/myapp/lib/MyApp/User/Data/UserDSOInterface.php
Created /home/someuser/projects/myapp/lib/MyApp/User/Data/UserDSOMySQL.php

 ```
