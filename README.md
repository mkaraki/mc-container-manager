# mc-container-manager
Minecraft Docker Container Manager

## Install
1. Install Docker Engine ([docs](https://docs.docker.com/engine/install/))
2. Install Docker Compose ([docs](https://docs.docker.com/compose/install/))
3. Pull [itzg/minecraft-server](https://hub.docker.com/r/itzg/minecraft-server)
```
# docker pull itzg/minecraft-server
```
4. Download [docker-compose-ghcr.yaml](docker-compose-ghcr.yaml) as `docker-compose.yml`
```
$ wget https://github.com/mkaraki/mc-container-manager/raw/master/docker-compose-ghcr.yaml -O docker-compose.yml
```
5. Run
```
# docker-compose up -d
```

## Update Minecraft Image
Pull [itzg/minecraft-server](https://hub.docker.com/r/itzg/minecraft-server)
```
# docker pull itzg/minecraft-server
```

## Install Custom Configuration
1. Download [Sample Config](_config.sample.php) as `config.php`
```
$ wget https://github.com/mkaraki/mc-container-manager/raw/master/_config.sample.php -O config.php
```
2. Edit `config.php`
3. Uncomment `docker-compose.yml`'s bind `config.php` configuration.

## How it works
Bind `/var/run/docker.sock` and execute `docker` command from php to control Docker container, 
[itzg/docker-minecraft-server](https://github.com/itzg/docker-minecraft-server).
