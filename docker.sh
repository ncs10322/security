#!/bin/bash
printf "====================================\n"
printf "    도커 설치 단계를 시작합니다. \n"
printf "====================================\n"
printf "\n"
printf "① 'apt' 업데이트를 실시합니다.\n"
wget -q -O - https://archive.kali.org/archive-key.asc  | apt-key add
apt-get update
printf "\n"
sleep 3
printf "② 'containerd.io', 'docker-ce-cli', 'docker-ce'를 다운로드를 합니다.\n"
wget -q https://download.docker.com/linux/debian/dists/buster/pool/stable/amd64/containerd.io_1.6.9-1_amd64.deb
wget -q https://download.docker.com/linux/debian/dists/buster/pool/stable/amd64/docker-ce-cli_24.0.7-1~debian.10~buster_amd64.deb
wget -q https://download.docker.com/linux/debian/dists/buster/pool/stable/amd64/docker-ce_24.0.7-1~debian.10~buster_amd64.deb
printf "\n"
sleep 3
printf "③ 'containerd.io', 'docker-ce-cli', 'docker-ce'를 설치합니다.\n"
dpkg -i containerd.io_1.6.9-1_amd64.deb 
dpkg -i docker-ce-cli_24.0.7-1~debian.10~buster_amd64.deb
dpkg -i docker-ce_24.0.7-1~debian.10~buster_amd64.deb 
printf "\n"
sleep 3
printf "④ 'docker-compose'를 설치합니다.\n"
curl -SLs "https://github.com/docker/compose/releases/download/v2.4.1/\
docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod u+x /usr/local/bin/docker-compose
curl -SLs \
https://raw.githubusercontent.com/docker/compose/1.29.2/contrib/completion/bash/\
docker-compose -o /etc/bash_completion.d/docker-compose
printf "\n"
sleep 3
printf "⑤  도커를 활성화 및 시작합니다.\n"
systemctl enable --now docker
printf "\n"
sleep 3
printf "⑥  도커 테스트를 위해서 'hello-world' 컨테이너를 구동하고 삭제합니다.\n"
docker container run --rm hello-world
docker image rm hello-world
printf "\n"
sleep 3
printf "⑦ 'hello-world' 컨테이너가 구동되었다면, 도커 설치는 성공적으로 완료된 것입니다.\n"
rm -rf *deb

