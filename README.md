# Recranet assignment solution
This repository contains a solution (Symfony application) for the programming assignment provided by [Recranet](https://recranet.com/en/).
# Getting started
## Prerequisites
This project is based on a [DDEV](https://ddev.readthedocs.io/en/stable/#__tabbed_1_1) environment. In order to install it, [WSL2](https://learn.microsoft.com/en-us/windows/wsl/install) with [Ubuntu](https://ubuntu.com/) (or any other) distribution or native Linux as well as [Docker Desktop](https://www.docker.com/products/docker-desktop/) should be integrated in your system. Additionally, [Composer](https://getcomposer.org/) must be installed for the dependency management.
## Setting up
__After__ all the necessary software was installed and configured, following instructions can be executed:
1. Clone the repo on your local environment (on WSL2)
2. Open the project in the IDE of choice (e.g. PHPStorm)
3. Execute `ddev start` in the terminal and wait until the server starts
4. Run `ddev ssh`
5. Run `composer install` to install all the necessary dependencies for Symfony
6. Run `php bin/console doctrine:migrations:migrate` to execute migrations
7. Run `npm install` to install Tailwind and other dependencies
8. Run `npm run watch` to track CSS changes

To stop the server, execute `ddev stop`.
## Seeding the database
In order to seed the database, some handy commands were created. If you want to view Eredivisie data from the current season (2023), you can execute: `php bin/console teams-info` and after that `php bin/console matches-info`. Additionally, if you want to have data from previous seasons as well, just add a year after the command, like so: `php bin/console teams-info 2022` - it retrieves teams from season 2022 and updates the database (insert teams that did not participate in season 2023 and adds statistic for all the teams).
The same feature exists in `matches-info`.

Moreover, after you seeded the desired teams and matches, you can set a cron job that updates data for the current season (2023) every day: `php bin/console messenger:consume -v scheduler_default`.
## Possible difficulties
If you try to install DDEV using WSL2, Ubuntu and Docker Desktop, you might encounter some errors that could be described in this section.
### __`root` User__  
After executing [this](https://raw.githubusercontent.com/ddev/ddev/master/scripts/install_ddev_wsl2_docker_desktop.ps1) script (number 6 in DDEV [instructions](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/#wsl2-docker-desktop-install-script)), you may get the following error:
> The default user in your distro seems to be root. Please configure an ordinary default user
    
This happens, because you are trying to download DDEV with the `root` user, which is not permitted by this software. To fix this issue, create a new user in Ubuntu terminal and then set it as a default in `Ubuntu/etc/wsl.conf` file with:
```php
[user]
default=<your-username>
```
Relaunch Ubuntu and run the script again, everything should work as expected.

### __Permissions__  
When you open the project in PHPStorm (or any other IDE) and try to run `ddev start`, you might get permission errors similar to these:
> populateExamplesAndCommands() failed: mkdir /home/recranet-assignment-solution/.ddev/commands: permission denied  
> Adding custom/shell commands failed: mkdir /home/recranet-assignment-solution/.ddev/.global_commands: permission denied
    
This happens, because you do not have rights to modify (mkdir in this case) the folder of the project. To fix this, grant these permissions using a couple of commands:  
1. `sudo chown -R <your-username>: recranet-assignment-solution`  
Gives you the ownership of the project folder and all of its subfolders
2. `sudo chmod -R u+w recranet-assignment-solution`  
Gives you the permission to modify the folder and its subfolders

After that, `ddev start` should work perfectly.

### __400 Bad Request__ 
When you access the website and try to register/log in, you might encounter a 400 Bad Request error on redirection to home page. The custom configuration of DDEV fixes it in `.ddev/nginx/redirect.conf`, but it might not catch this from the first server start. In order to fix it, simply run `ddev restart` and this config will be executed and 400 error will disappear.
# Database Design
![erd.jpg](/assets/img/erd.jpg)
# References
## API
[Football-data](https://www.football-data.org/) was used as an external API to retrieve information about Eredivisie teams and matches.
