# Recranet assignment solution
This repository contains a solution (Symfony application) for the programming assignment provided by [Recranet](https://recranet.com/en/).
# Getting started
## Prerequisites
This project is based on a [DDEV](https://ddev.readthedocs.io/en/stable/#__tabbed_1_1) environment. In order to install it, [WSL2](https://learn.microsoft.com/en-us/windows/wsl/install) with [Ubuntu](https://ubuntu.com/) (or any other) distribution or native Linux as well as [Docker Desktop](https://www.docker.com/products/docker-desktop/) should be integrated in your system. Additionally, [Composer](https://getcomposer.org/) must be installed for the dependency management.
## Setting up
__After__ all the necessary software was installed and configured, following instructions can be executed:
1. Clone the repo on your local environment (preferably on WSL2)
2. Open the project in the IDE of choice (e.g. PHPStorm)
3. Execute `ddev start` in the terminal and wait until the server starts
4. Run `ddev ssh`
5. Run `composer install` to install all the necessary dependencies for Symfony

To stop the server, execute `ddev stop`.
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