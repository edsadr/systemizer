Systemizer
==========

Systemizer is a code experiment on generating software systems/applications with a metalanguage or metadata.

Lets say that we would like to store users information in a database, be able to enter the data through a command-line interface, display th
e data on the web through a html table, and also have a Restful api to manipulate the data.

What is the minimum amount of information we need to create this? I will argue that the informational need is minimal, yet the amount of code that will be needed for this simple application is not small.

There are solutions out there to fill the gap between information and representation: frameworks and libraries. So why do we need a differen
t solution?

Even though not a perfect analogy, this might help differentiate the approaches: If we were gods creating creatures, a libraries/framework a
pproach might be similar to picking predefined parts (skeleton, organs, etc) and putting them together. The Systemizer approach would be similar to DNA being used as a language for the creatures to be created.

Systemizer will make an attempt at creating an expressive and simple meta-something to generate/produce a wide range of applications.

### Enough blah blah, show me what it does!!

As I am not sure of exactly what all is needed for a meta-thing to be expressive enough to create applications, I am developing the meta-thing, the application generators, and a sample application, all at the same time.

Here is how you can see Systemizer's capabilities, so far:
- Clone the repositiory: ``` git clone https://github.com/fmizzell/systemizer.git ```
- Get Systemizer's and the sample app's dependencies with composer by running ``` composer update ``` inside the root and app directories in the repo. __Note:__ If you do not have composer installed this [guide](https://getcomposer.org/doc/00-intro.md#globally) might be useful.
- From the root directory run the app generation script: ``` php generate_app.php ```
- Finally run the simple Tic Tac Toe application generated by Systemizer by running ``` php index.php ``` from the app directory.

### Contributing Guidelines

* Fork the project.
* Make your feature addition or bug fix.
* Commit just the modifications, do not mess with the composer.json unless required.
* Ensure your code is nicely formatted in the [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
  style and that all tests pass. The project includes a tool validating it you need NodeJS(http://nodejs.org/) and Gulp(http://gulpjs.com) installed to use it
* Send the pull request.