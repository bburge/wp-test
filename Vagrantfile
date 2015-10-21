# -*- mode: ruby -*-
# vi: set ft=ruby :

# Variables
SERVER_IP                  = '192.168.33.10'

# All Vagrant configuration is done below.
Vagrant.configure(2) do |config|

  # Set up the Box
  config.vm.box = "centurylinkcloud/wp-developer"
  config.vm.network "private_network", ip: SERVER_IP
  config.vm.hostname = "wp.dev"
  config.vm.synced_folder ".", "/srv/www", :mount_options => ["dmode=777", "fmode=666"]
  
  # restart nginx
  config.vm.provision "shell",
    inline: "sudo service nginx restart",
    run: "always"

end
