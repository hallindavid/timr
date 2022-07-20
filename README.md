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

## License

timr is an open-source software licensed under the MIT license.
