#!/usr/bin/env bash

# size of swapfile in megabytes
swapsize=1024

dd if=/dev/zero of=/swapfile bs=1M count=${swapsize}
sudo dd if=/dev/zero of=/swapfile bs=1M count=${swapsize}
mkswap /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo "/swapfile  none  swap  defaults  0  0" >> /etc/fstab | sudo sh
free -m
echo Please restart the server
