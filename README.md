## About the project

In order to understand the solution you have to understand the problem I had to solve:

You will need to write a program that downloads all the items in this ENDPOINT and cache images within each asset. To
make it efficient, it is desired to only call the URLs in the JSON file only once. Demonstrate, by using a framework of
your choice, your software architectural skills. How you use the framework will be highly important in the evaluation.

How you display the feed and how many layers/pages you use is up to you, but please ensure that we can see the complete
list and the details of every item. You will likely hit some road blocks and errors along the way, please use your own
initiative to deal with these issues, it’s part of the test.

Please ensure all code is tested before sending it back, it would be good to also see unit tests too. Ideally, alongside
supplying the code base and all packages/libraries required to deploy, you will also have to supply deployment
instructions too.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Run the application

First step: open the website and import the information from provider by pressing "Import" button from the interface.

Second step is to import and cache images by running the command: php artisan actors:cache-images

## Approach

I've tried as much as possible to use the best practices I currently know:

- SOLID
- Strategy Pattern for enforcing Open-Closed Principle
- Cache and eager loading for less database usage;

In my implementation I've tried to design a mechanism witch interacts with Http service in multiple ways: with stream,
with no stream and so on. In project I've implemented the infrastructure for a concrete implementation where Http
interaction is made using streaming.

Also the ENDPOINT from this project is treated as one of many possible providers for the data. My implementation allows multiple sources for the import.

## UML for Import and HTTP interaction

<img src="https://github.com/alexandruu/mindgeek/blob/master/UML.png" alt="UML">

## Tests

Yes, I've wrote some tests.

<img src="https://github.com/alexandruu/mindgeek/blob/master/UnitTests.png" alt="UML">
