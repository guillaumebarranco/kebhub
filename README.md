Kebhub
======

Kebhub is a free Twitter and Instagram hub.

  - Set your social network username 
  - Save your favorites posts
  - Load these on your website or your application from our API

### Step 1

Clone the project.
```sh
$ git clone https://github.com/Kebhub/kebhub.git
```


### Step 2

Install all necessary packages for Kebhub project.

```sh
$ composer install
```
After this, Composer must ask you to insert some informations like database name, login, password... On your terminal.

### Step 3

Create your database.

```sh
$ php app/console doctrine:database:create
```

### Step 4 (Only for demo)

Create admin & client login that we have created for the demo.

```sh
$ php app/console doctrine:load
```

### Step 5 (Only for demo)

Go on login page http://your-application-path/login and sign-in with client or admin role.
- login : admin ; password : admin
- login : client ; password : client

### Step 6

Discover and enjoy Kebhub.

#### View more

- For more informations, visit [http://kebhub.com/doc](http://kebhub.com/doc).