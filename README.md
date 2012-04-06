# Simple PHP URL shortener

## Installation

1. Download the source code as located within this repository, and upload it to your web server.
2. Use `database.sql` to create the `redirect` table in a database of choice.
3. Edit `config.php` and enter your database credentials.

## Features

* Redirect to Twitter when given a numerical slug, e.g. `http://crgp.tk/179882100672049152` → `http://twitter.com/craigpatik/status/179882100672049152`.
* Redirect to your Twitter account when `@` is used as a slug, e.g. `http://crgp.tk/@` → `http://twitter.com/craigpatik`.
* Redirect to your Google Plus account when `+` is used as a slug, e.g. `http://crgp.tk/+` → `https://plus.google.com/u/0/116553353277057965424/posts`.
* Redirect to a specific page on your main website when an unknown slug (not in the database) is used, e.g. `http://crgp.tk/html5/` → `http://patik.com/html5/`.
* Ignores weird trailing characters (`!`, `"`, `#`, `$`, `%`, `&`, `'`, `(`, `)`, `*`, `+`, `,`, `-`, `.`, `/`, `@`, `:`, `;`, `<`, `=`, `>`, `[`, `\`, `]`, `^`, `_`, `{`, `|`, `}`, `~`) in slugs — useful when your short URL is run through a crappy link parser, e.g. `http://crgp.tk/aaa)` → same effect as visiting `http://crgp.tk/aaa`.
* Generates short, easy-to-type URLs using only `[a-z]` characters.
* Doesn’t create multiple short URLs when you try to shorten the same URL. In this case, the script will simply return the existing short URL for that long URL.
* DRY, minimal code.
* Correct, semantic use of the available HTTP status codes.
* Can be used with Twitter for iPhone. Just go to _Settings_ › _Services_ › _URL Shortening_ › _Custom…_ and enter `http://crgp.tk/shorten?url=%@`.

## Favelets / Bookmarklets

### Prompt

``` js
javascript:(function(){var%20q=prompt('URL:');if(q){document.location='http://crgp.tk/shorten?url='+encodeURIComponent(q)+'&bm=1'}}());
```

### Shorten this URL

``` js
javascript:(function(){document.location='http://crgp.tk/shorten?url='+encodeURIComponent(location.href)+'&bm=1'}());
````

## User interface by

* [Craig Patik](http://patik.com/)

## Original Author

* [Mathias Bynens](http://mathiasbynens.be/)

## Contributors

* [Peter Beverloo](http://peter.sh/)
