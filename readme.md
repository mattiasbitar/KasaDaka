#How to install the kasadaka on raspbian


install raspi-config

upsize sd card

run all updates, upgrade, dist-upgrade


##SWIPL COMPILE
sudo apt-get install \
        build-essential autoconf curl chrpath pkg-config \
        ncurses-dev libreadline-dev \
        libgmp-dev \
        libssl-dev \
        unixodbc-dev \
        zlib1g-dev libarchive-dev \
        libossp-uuid-dev \
        libxext-dev libice-dev libjpeg-dev libxinerama-dev libxft-dev \
        libxpm-dev libxt-dev \
        libdb-dev \
        openjdk-7-jdk junit git tmux libxml2 htop libsqlite3-dev

git clone https://github.com/SWI-Prolog/swipl-devel.git

cd swipl-devel

sudo ./prepare

cp -p build.templ build

sudo nano build >> verander path /usr/local

su

./build

en nu hoe starten?? /root/bin/swipl, nog uitzoeken hoe dit voor een gewone user kan


##CLIOPATRIA
cd ~

git clone https://github.com/ClioPatria/ClioPatria.git

cd ClioPatria

./configure

git submodule update --init web/yasqe web/yasr

./run.pl

http://localhost:3020/

user:kasadaka pass:kasadaka

##ASTERISK + VXML
cd ~

wget http://downloads.asterisk.org/pub/telephony/certified-asterisk/certified-asterisk-11.6-current.tar.gz

tar -xvf certified-asterisk-11.6-current.tar.gz

~~#dahdi compilen nog niet werkend...

wget http://downloads.asterisk.org/pub/telephony/dahdi-linux-complete/dahdi-linux-complete-current.tar.gz

tar -xvf dahdi-linux-complete-current.tar.gz

cd  dahdi-linux-complete-2.10.2+2.10.2

sudo make

sudo make install

sudo make config~~

cd ../certified-asterisk-11.6-cert11

sudo  ./configure --disable-xmldoc

cd contrib/scripts

sudo ./install_prereq install

~~sudo ./install_prereq install-unpackaged (svn not found??)~~

cd ../..

sudo make

sudo make install

sudo make config

sudo make install-logrotate


sudo /etc/init.d/dadhi start

sudo /etc/init.d/asterisk start

cd ~

wget http://downloads.i6net.com/vxi/raspbian/vxml_V11.0_2014-12-20_dev_armv6_debian-7.0.tar.gz

tar -xvf vxml_V11.0_2014-12-20_dev_armv6_debian-7.0.tar.gz

cd vxml_V11.0_2014-12-20_dev_armv6_debian-7.0

sudo ./install.sh


sudo /etc/init.d/asterisk restart

sudo /etc/init.d/openvxi start


##asterisk should be working now. Proceed to link config files to kasadaka folder

add asterisk user to pi group so it can read the git files without chmod

sudo nano /etc/group

and add asterisk to the pi group: pi:x:1000:asterisk

remove old config files

sudo rm -rf /etc/asterisk/

create symlink to config files in git

sudo ln -s /home/pi/KasaDaka/etc/asterisk/ /etc/asterisk




