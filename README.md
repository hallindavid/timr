# timr

an open source project created by [David Hallin](https://davidhallin.com),
using [laravel-zero](https://laravel-zero.com/) to track the time you spend on projects, all within the terminal.

## why?

I wanted to track the time I was spending on some of my projects. I looked at services like [Toggl](https://toggl.com),
but I never remember to open the app.

I figured if I had something accessible from command line, it'd be easier because I basically always have at least a
terminal window open.

## commands / usage

*new project*

```bash
# Create a new project using the prompts
php timr project:new   

# Create a new project with name _Acme Corp Project_
php timr project:new "Acme Corp Project"

# Create a new project with the name _Acme Corp Project_ and a short code of acp
php timr project:new "Acme Corp Project" acp
```

*list projects*

```bash
# Output a list of projects in your database
php timr project:list
```

*delete project*

```bash
# Delete project using prompts
php timr project:delete

# Delete project with ID of 7
php timr project:delete --id=7

# Delete project with short code of "acp"
php timr project:delete --code=acp
```

*begin working on project*

```bash
# Begin time tracking on project (using prompts)
php timr project:start

# Begin time tracking on project with short code of acp
php timr project:start acp 

# Begin time tracking on project with id of 7
php timr project:start --id=7
```

*stop working on project*

```bash
# End project time tracking
php timr project:stop 
```

## installation / getting started

1. clone the repo
2. run `touch database.sqlite` to create your database file where ever you'd like to save
   it. [read note](https://laravel-zero.com/docs/database#note-on-phar-builds) on where to put your database.sqlite file
   if you're planning on building the PHAR, (so you don't need to do php infront of every command)
3. run `php timr migrate` to set up your database
4. run `php timr list` to check out the commands you can run
5. have fun!

## license

timr is an open-source software licensed under the MIT license.
