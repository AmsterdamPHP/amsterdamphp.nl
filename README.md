amsterdamphp.nl
===============

This repository hosts the main AmsterdamPHP Website. The purpose of this website is to showcase the User Group, from the work that is done and its results to the sponsors who allow us to make this happen and the people who contribute to allow us to accomplish so much.

Its a community project, lead by the organizers and executed by the members. All communication for this project is done either in the issues page, or in the enablers mailing list.

## Want to contribute?

We want this to be a community project we all rally behind and keep up to date, but we also want it to be a **learning experience**. This means experimenting with new technologies and trying out things we may not have time to use in everyday life.

### How to contribute?

1. **look at the issues**, they are our guide, find something you are comfortable with and claim it
2. if details are lacking, **start a discussion** on the issue
3. **run the behat tests** for that feature (or write if needed)
4. **write your code** according to the guidelines below
5. **run behat tests** make sure everything is green
6. **open a PR** and wait for the review results by one of the project leads
7. ...
8. **Profit!**, no I'm kidding, but the learning process should indeed be a profit for you.

#### Booting up Vagrant Box

To make it easier for you to contribute and use all the new and shiny, we have setup a Vagrant box. It is provisioned with Ansible, so you need to have a few extra things, these are the steps

1. Install Ansible ([See Docs](http://www.ansibleworks.com/docs/intro_installation.html) or use HomeBrew on Mac OSX)
2. Install VirtualBox
3. Install Vagrant
4. Run `vagrant up`
5. Point `vagrant.amsterdamphp.dev` to `192.168.33.10`
6. Code!

### Guidelines

- use PSR-2 guidelines to write code
- tests! Please add them along with your contributions
- practice writing good clean code, this is for learning.
- use the vagrant box included if you have trouble with dependencies

### Technology sandbox

Like I said, learning experience, we want to pack some new stuff in here for us to experiment with, this may be familiar to some and new to others, that's where you come in to teach others.

- Symfony 2.3
- [Behat](http://behat.org/) and [PHPSpec](http://www.phpspec.net/)
- API's, lots of them.
- Vagrant + [Ansible](http://www.ansibleworks.com)
- Redis
- Less

Any more suggestions?
