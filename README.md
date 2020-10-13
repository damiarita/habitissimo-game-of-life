# Install Instructions
* Clone this repository into an empty folder
* With your command line interface, go to the folder where the repository has been cloned and execute "composer install". This will download all the necessary dependencies
* To run the sample code, execute: "php sample_usage.php"
* To run the unit testing suite, execute "composer test"

# Content of the repository
* **src/GameOfLife.php** the class GameOfLife as required by https://katalyst.codurance.com/conways-game-of-life
* **tests/GameOfLifeTest.php** the suite of unit tests
* **sample_usage.php** a file that uses the class GameOfLife to simulate a blinker and a glider
* **composer.json** & **composer.lock** definition of dependencies
