<h1 align="center">Urbaneye</h1> <br>

<p align="center">
  Discover how well-liked are cities across the world. Developed with TailwindCSS, jQuery, JavaScript and PHP.
</p>

<p align="center">
  <img src = "https://i.imgur.com/4Nbmde6.png" width=1080>
</p>

## Table of Contents

- [Features](#features)
- [Build Process](#build-process)
- [Rework](#rework)
- [Feedback](#feedback)

## Features

The things you can do with Urbaneye:

* Things you can do:
* Add and remove from a list the cities you like the most;
* Write reviews (to be changed);
* See the latest reviews;
* See the scores of a city;
* Search cities by country or name;
* See all the reviews of a city;

<p align="center">
  <img src = "https://i.imgur.com/jLMGMw5.png" width=700>
</p>

## Build Process

**NPM is required only if you wish to build the CSS.**
- Build the CSS with `npx tailwindcss-cli@latest build static/css/tailwind.css -o static/css/output.css`;
- In API, modify [dbConn.php](https://github.com/c2oc/urbaneye/blob/master/api/dbConn.php) as you need;
- Check out the ER diagram in the [docs/Database](https://github.com/c2oc/urbaneye/tree/master/docs/Database) folder. It also has an export of all the queries;
- As of now, the data dump of the countries and cities is not provided;

## Rework

- Remove jQuery;
- The city page needs to show all the reviews and scores;
- Simplify the "add/remove to favorites" process (which means, add a button on the city wildcard). Also partially rewrite to be in line with the rest of the site;
- Rewrite the "infinite scroller" to have improve performances and memory usage. (As of now I plan to use `intersectionObserver` to monitor the scroll and won't clone the single card but substitute the data within the first ~28 cards. This shouldn't waste as much memory as it does now and it should be way more performant too);
- Modify user settings (wip);
- Add Trending cities based on users interaction;
- API integration with OpenWeather (wip);
- Rewrite the "write review" backend to use REST API to be in line with the rest of the site;

## Feedback

Feel free to [file an issue](https://github.com/c2oc/urbaneye/issues/new). Feature requests and bug reports are always welcome.
