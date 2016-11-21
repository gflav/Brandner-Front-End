# README #

Wordpress website that will make you jealous.

### Directory Structure #

assets
- Any design & doc files

build
- The source directory for js/css files

dist
- The HTML buildouts

htdocs
- The web accessible website

sql
- Database backups

private
- Anything that should be private

### Installation Instructions:

You need [Gulp] installed globally:
```sh
$ npm install -g gulp-cli
```
Once [Gulp] has been installed, we need to clone the repo and download the npm modules.
```sh
$ cd into local repo
$ npm install
```

Once all the npm modules have been installed, we have two [Gulp] build tasks set up.

Use this while developing.
```sh
$ gulp
```

Use this before pushing to the server or committing to the repo. It does everything the default task does, with some extra minification and console removing.

```sh
$ gulp production
```

Use this to watch for changes

```sh
$ gulp watch
```
### PWD ###

``` brandner1!