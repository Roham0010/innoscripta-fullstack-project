### About the project

- I used three apis:
  - NewsAPI, NyTimes API, Guardian API
- The process of getting articles run throught a sync system that we get data as much as we can and in case of rate limits hit or other problems, we will save the latest synced article published at and save it in a sync_statuses table to use that for the next time and start fetching from that date.
- For the keywords I used another table because keywords can be redundant too much.
- I pushed the .env file to so that there will be no need to create it from scrach.
- This is a [sample of the tables records](https://prnt.sc/X7dy61jMCcwN) on my local machine

## How to run

- Make sure `3000`, `8080`, `3306` ports are free.
- Build and run the docker images using `docker compose up --build` or `docker-compose up --build`.
- Log into the docker backend image to run commands.
  - Run the migrations in the docker backend image `php artisan migration`.
  - Run the jobs by `php artisan get:articles` to sync the articles from API sources. (open the localhost:8080 I have set a route to run the jobs from there to to make it more simpler)
  - Open the website through http://localhost:3000/

#### Shortcut

There is a Makefile in in case everything is configed as expected by running make start it should build, run images, and migrate the migrations.
