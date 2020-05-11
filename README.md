# Tiny Social Network

## Welcome

It's a *tiny social network* where **USER**s can **POST** messages and **COMMENT** these messages.

### Goal

This project was done for my PHP courses.
The goal is to review the **PHP syntax** and use **PDO** (PHP Data Objects)  for accessing databases.



![screenshot](screenshot.png)

### Database

The structure is really simple:

![dbschema](dbschema.png)

### MVC

It's a simple **MVC** implementation **without OOP** (Object-Oriented Programming) in **PHP** .

```
+---config/
|       data.sql
|       structure.sql
|       
+---controllers/
|       controller.php
|       
+---models/
|       PDO.php
|       PostManager.php
|       CommentManager.php
|       UserManager.php
|       
+---views/
|       DisplayPosts.php
|       
\---web/
    |   index.php
    |   
    +---css/
    +---img/       
    \---js/
```

## Getting Started

These instructions will get you a copy of the project up and running on your local machine.

### Prerequisites

You need to have a **L.A.M.P.** architecture working on your computer.

Else you need to install the missing parts.

**MySQL server**:

Installation

```sh
sudo apt-get install mysql-server
```

Start MySQL server

```sh
sudo systemctl start mysql
```

Create a new  MySQL user.
Of course, you can change the queries to replace **dbuser** & **1234Soleil!** with your own **username** & **password**, just don't forget what you enter because you will need them after.

```sh
sudo mysql
```
```sql
CREATE USER 'dbuser'@'localhost' IDENTIFIED BY '1234Soleil!';
GRANT ALL PRIVILEGES ON * . * TO 'dbuser'@'localhost';
EXIT;
```

**Apache2 server**:

```sh
sudo apt-get install apache2
```

Start the server:

```sh
sudo systemctl start apache2
```

### Installing

- **Fork** this repo and **clone** it on your computer inside the **DocumentRoot** of apache (by default it should be inside /var/www/html).

  ```sh
  cd /var/www/html
  git clone https://github.com/YOUR-USERNAME/socialnetwork
  ```

- Create an populate the database:

  Go into the **config directory** and run the <u>2 SQL scripts</u> (replace **dbuser** by the user you entered before)

  ```sh
  cd socialnetwork/config
  mysql -u dbuser -p < structure.sql
  mysql -u dbuser -p < data.sql
  ```

  - You can connect to the database **tsn** to see if the database and the table were well created

    ```sh
    sudo mysql
    ```
    ```sql
    use tsn;
    show tables;
    select * from user;
    exit;
    ```

- Go into the **models directory** and modify **PDO.php** to set the right credentials (**$db_user** & **$db_passwd**).

```php
<?php
  $db_user = "dbuser";
  $db_passwd = "1234Soleil!";
  $db_host = "localhost";
  $db_port = "3306";
  $db_name = "tsn";
  $db_dataSourceName = "mysql:host=$db_host;port=$db_port;dbname=$db_name";

  $PDO = new PDO($db_dataSourceName, $db_user, $db_passwd);
  $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

```

- Now if you go on your web browser you should see something:

  http://localhost/socialnetwork/web/



## Exercise 1

The posts are dynamically retrieved from the database, nonetheless, the comments are hardcoded.

**<u>Final Goal</u>: You have to retrieve these comments dynamically for each post.**

### 1 - Show me the code

Have a look inside the project to **understand each line of code**.
Start with the entry point, the *index.php* inside the **web directory**.
And follow the execution to understand the whole project.

```php
<?php
  include "../controllers/controller.php";
?>
```

### 2 - Use Git in the right way

Never work on the **master** branch, so start to create a **develop** branch from master and push it to GitHub.

```sh
git branch develop
git checkout develop
git push origin develop
```

As you will work on the feature to retrieve comments, create a new branch **feature/retrieveComments** from develop and push it to GitHub.

```sh
git branch feature/retrieveComments
git checkout feature/retrieveComments
git push origin feature/retrieveComments
```

Now you are ready to code.

### 3 - Do the job

If you have a look to *controllers/**controller.php*** you'll see some fake data about comments:

```php
case 'display':
    include "../models/PostManager.php";
    $posts = GetAllPosts();

    include "../models/CommentManager.php";
    $comments = array();

    // ===================HARDCODED PART===========================
    $comments[1] = array(
        array("nickname" => "FakeUser1", "created_at" => "1970-01-01 00:00:00", "content" => "Fake comment 01."),
        array("nickname" => "FakeUser2", "created_at" => "1970-01-02 00:00:00", "content" => "Fake comment 02."),
        array("nickname" => "FakeUser1", "created_at" => "1970-01-03 00:00:00", "content" => "Fake comment 03.")
    );
    $comments[3] = array(
        array("nickname" => "FakeUser1", "created_at" => "1970-01-01 00:00:00", "content" => "Fake comment 04."),
    );
    // =============================================================

    include "../views/DisplayPosts.php";
    break;
```
<u>You have to:</u>

1.  Go into *models/**CommentManager.php*** to create a new function **GetAllCommentsFromPostId**. This new function should return **all comments** from a **post id**. <u>Don't forget you need the nickname of the user who commented</u>. To do that, have a look to the **GetAllCommentsFromUserId**. Your new function do almost the same thing but instead of "WHERE comment.**user_id = $userId** " you should have "WHERE comment.**post_id = $postId** ".

```php
   function GetAllCommentsFromPostId($postId) {
	// Code here...
   }
```

2. Inside *controllers/**controller.php*** remove the *hardcoded part* but keep **$comments = array()**.

3. Instead, loop over **$posts** and call your new function **GetAllCommentsFromPostId** <u>for each post</u> giving the **post id**.

4. Inside the loop, fill the **$comments** array with the results. **$comments** is an associative array, the "key" should be the **post id** and the "value" the result of your new function **GetAllCommentsFromPostId**.

### 4 - Save on GitHub

When all is ok, **commit your changes** and **push** your work to GitHub.

You can **merge your feature branch into develop** and **push** develop too !!!

```sh
git commit -a -m "Remove the hardcoded comments"
git push origin feature/retrieveComments
git checkout develop
git merge feature/retrieveComments
git push origin develop
```



## Exercise 2

**<u>Final Goal:</u> Made a simple search feature.**

### 1 - New feature = new branch

You should be on the **develop** branch else enter this line.

```sh
git checkout develop
```

Create a new branch **feature/search** from develop and push it to GitHub.

```sh
git branch feature/search
git checkout feature/search
git push origin feature/search
```

Now you are ready to code.

### 2 - Add the search form into the navbar.

Modify the file: views/**DisplayPosts.php**

Before the "Login" and "Sign Up" links, add the **search form** like this:

![screenshot_search](screenshot_search.png)

```html
<ul class="navbar-nav">
    <li class="nav-item">
        <form class="nav-link" method="get">
            <input name="search" type="text"></input>
        </form>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?action=login" role="button">Login</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?action=register" role="button">Sign Up</a>
    </li>
</ul>
```

### 3 - Modify the source code to search

1. Modify the file: controllers/**controller.php**

   If the **search** parameter is well defined into **$_GET** we will use it to retrieve the right posts.

   Else the posts will continue to come from the **GetAllPosts** function.

```php
default:
    include "../models/PostManager.php";
    if (isset($_GET['search'])) {
      $posts = SearchInPosts($_GET['search']);
    } else {
      $posts = GetAllPosts();
    }
    
    include "../models/CommentManager.php";
```

2. You have to write the **SearchInPosts** function into models/**PostManager.php**. 

   To do that copy/paste the **GetAllPosts** function, rename it into **SearchInPosts**.

3. Add the argument **$search** to this function.

4. Add a *where* clause into the sql query using this argument, it should look like this:

```php
 "SELECT post.*, user.nickname "
      . "FROM post LEFT JOIN user on (post.user_id = user.id) "
      . "WHERE content like '%$search%' "
      . "ORDER BY post.created_at DESC"
```

### 4 - Save on GitHub

When your search works well, **commit your changes** and **push** your work to GitHub.

You can **merge your feature branch into develop** and **push** develop too !!!

```sh
git commit -a -m "Simple search using the sql like"
git push origin feature/search
git checkout develop
git merge feature/search
git push origin develop
```



## Exercise 3

**<u>Final Goal:</u> Made a simple login & logout feature.**

### 1 - New feature = new branch

You should be on the **develop** branch else enter this line.

```sh
git checkout develop
```

Create a new branch **feature/login** from develop and push it to GitHub.

```sh
git branch feature/login
git checkout feature/login
git push origin feature/login
```

Now you are ready to code.

### 2 - Modify the navbar to display a logout when it needs

When the user will be logged, **$_SESSION['userId']** will contain his user id.

Modify the file: views/**DisplayPosts.php**

The navbar-nav section depends if the variable **$_SESSION['userId']** is set or not.

```php+HTML
      <ul class="navbar-nav">
        <li class="nav-item">
          <form class="nav-link" method="get">
            <input name="search" type="text"></input>
          </form>
        </li>
    <?php
      if (isset($_SESSION['userId'])) {
    ?>
        <li class="nav-item">
          <a class="nav-link" href="?action=logout" role="button">Logout</a>
        </li>
    <?php
      } else {
    ?>
        <li class="nav-item">
          <a class="nav-link" href="?action=login" role="button">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=register" role="button">Sign Up</a>
        </li>
    <?php
      }
    ?>
      </ul>
```

### 3 - Modify the source code to be able to login & logout.

1. Modify the file: controllers/**controller.php**

   The **logout** action unset the variable **$_SESSION['userId']** and redirect the visitor to the default action.

   The **login** action verify the username & password and set the variable **$_SESSION['userId']** and redirect the visitor to the default action.

   If the <u>credentials are wrongs or not present in $_POST it displays the login form</u>.

```php
  case 'logout':
    if (isset($_SESSION['userId'])) {
      unset($_SESSION['userId']);
    }
    header('Location: ?action=display');
    break;

  case 'login':
    include "../models/UserManager.php";
    if (isset($_POST['username']) && isset($_POST['password'])) {
      $userId = GetUserIdFromUserAndPassword($_POST['username'], $_POST['password']);
      if ($userId > 0) {
        $_SESSION['userId'] = $userId;
        header('Location: ?action=display');
      } else {
        $errorMsg = "Wrong login and/or password.";
        include "../views/LoginForm.php";
      }
    } else {
      include "../views/LoginForm.php";
    }
    break;
```

2. You have to write the **GetUserIdFromUserAndPassword** function into models/**UserManager.php**. This function have to return the user id if the user well exists and his password is the same. Else this function has to return -1.

### 4 - Save on GitHub

When your login/logout works well, **commit your changes** and **push** your work to GitHub.

You can **merge your feature branch into develop** and **push** develop too !!!

```sh
git commit -a -m "Simple login/logout system"
git push origin feature/login
git checkout develop
git merge feature/login
git push origin develop
```



## Exercise 4

**<u>Final Goal:</u> Be able to post a new message on the tiny social network.**

### 1 - New feature = new branch

You should be on the **develop** branch else enter this line.

```sh
git checkout develop
```

Create a new branch **feature/login** from develop and push it to GitHub.

```sh
git branch feature/newmsg
git checkout feature/newmsg
git push origin feature/newmsg
```

Now you are ready to code.

### 2 - Modify the main page to display a form to post a message

![screenshot_newMsg](screenshot_newMsg.png)

Modify the file: views/**DisplayPosts.php**

When the user is logged (when the variable **$_SESSION['userId']** is set) display the form.

```php+HTML
<?php
if (isset($_SESSION['userId'])) {
?>
  <div class="row newMsg">
    <div class="col">
      <form class="input-group" method="POST" action="?action=newMsg">
        <input name="msg" class="form-control" placeholder="Add a message" type="text">
        <button type="submit" class="btn btn-primary">Submit</button>    
      </form>
    </div>
  </div>
<?php
}
?>
```

### 3 - Modify the source code to create a new post.

1. Modify the file: controllers/**controller.php**

   If the user is logged and want to post a new message (value read from **$_POST['msg']**) so we will create a **new insert** into the **Post** table with the function **CreateNewPost**.


```php
  case 'newMsg':
    include "../models/PostManager.php";
    if (isset($_SESSION['userId']) && isset($_POST['msg'])) {
      CreateNewPost($_SESSION['userId'], $_POST['msg']);
    }
    header('Location: ?action=display');
    break;
```

2. You have to write the **CreateNewPost** function into models/**PostManager.php**. 

   This function takes 2 arguments **$userId** and **$msg**. 
   It has to execute this query: 

```php
"INSERT INTO post(user_id, content) values ($userId, '$msg')"
```

### 4 - Save on GitHub

When you are to able to post a new message, **commit your changes** and **push** your work to GitHub.

You can **merge your feature branch into develop** and **push** develop too !!!

```sh
git commit -a -m "Simple post system"
git push origin feature/newmsg
git checkout develop
git merge feature/newmsg
git push origin develop
```
