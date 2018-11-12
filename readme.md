## Running the application
[Install docker and docker-compose](https://docs.docker.com/compose/install/)

Then run
```bash
./setup
```
This will take a few minutes. It will create the containers and setup them for use.

When finished, run the application with:
```bash
./up
```

And the application will be running in [http://localhost:8080](http://localhost:8080).

**Note:** If the port 8080 is already in use, change the configuration in the docker-compose.yml file. For example, to use the port 9090 instead:
```yaml
ports:
  - 9090:80
``` 

You can run the tests of phpunit:
```bash
phpunit
```

You can also tests the application with the command:
```bash
php artisan tictactoe:play
```
![](https://image.ibb.co/ctFmDV/Screen-Shot-2018-11-12-at-3-24-41-PM.png)

![](https://image.ibb.co/mRrF7q/Screen-Shot-2018-11-12-at-3-28-56-PM.png)

![](https://image.ibb.co/dihNnq/Screen-Shot-2018-11-12-at-3-29-01-PM.png)

