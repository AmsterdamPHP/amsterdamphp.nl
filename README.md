amsterdamphp.nl
===============

The website fo the AmsterdamPHP User Group

Installation
------------

Getting a development environment up and running is relatively straightforward.

### Dependencies

Before you start, you need to have the following installed. These are not dependencies for the application itself, but they are dependencies for getting the development environment to work.

* [Ruby](http://www.ruby-lang.org/en/downloads/)
* [Vagrant](http://downloads.vagrantup.com/)
* [Oracle VirtualBox](https://www.virtualbox.org/wiki/Downloads)

### Clone the repository

Clone the repository on your local machine.

    git clone git@github.com:AmsterdamPHP/amsterdamphp.nl.git amsterdamphp.nl

### Initialize the vagrant instance

Change into the directory of the wwcts repository, and bring up the vagrant instance.

    cd amsterdamphp.nl
    vagrant up

Running this for the first time can take a while to complete. It might ask for your Administrator password if you run on Mac to enable NFS mounting to speed up file syncing. Once the command completes you should have a development server running on your local machine.

### Accessing the instance

The site is accessible by visiting `http://wwcts.dev` in your browser (make sure this hostname points to 33.33.33.10 in your hosts file). You can SSH into the instance by running the following from the directory where you cloned the repository.

    vagrant ssh

