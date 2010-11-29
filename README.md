# Simple PHP URL shortener

## Installation

1) Download the source code as located within this repository, and upload it to your web server.
2) Use `database.sql` to create the `redirect` table in a database of choice.
3) Edit `config.php` and enter your database credentials.

## Features

* Redirect to Twitter when given a numerical slug, e.g. `http://mths.be/8065633451249664` → `http://twitter.com/mathias/status/8065633451249664`.
* Redirect to your main website when no slug is entered, e.g. `http://mths.be/` → `http://mathiasbynens.be/`.
* Redirect to a specific page on your main website when an unknown slug (not in the database) is used, e.g. `http://mths.be/demo/jquery-size` → `http://mathiasbynens.be/demo/jquery-size`.
* Ignores weird trailing characters (`!`, `"`, `#`, `$`, `%`, `&`, `'`, `(`, `)`, `*`, `+`, `,`, `-`, `.`, `/`, `@`, `:`, `;`, `<`, `=`, `>`, `[`, `\`, `]`, `^`, `_`, `{`, `|`, `}`, `~`) in slugs — useful when your short URL is run through a crappy link parser, e.g. `http://mths.be/aaa)` → same effect as visiting `http://mths.be/aaa`.
* Generates short, easy-to-type URLs using only `[a-z]` characters.
* DRY, minimal code.
* Correct, semantic use of the available HTTP status codes.

## Favelets / Bookmarklets

### Prompt

    javascript:(function(){var%20q=prompt('URL:');if(q){document.location='http://foo.bar/shorten-url.php?'+encodeURIComponent(q)}})();

### Shorten this URL

    javascript:(function(){document.location='http://foo.bar/shorten-url.php?'+encodeURIComponent(location.href)})();

_— [Mathias](http://mathiasbynens.be/)_