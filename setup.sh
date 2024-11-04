#!/bin/bash
printf "======================================\n"
printf "  CentOS 기본 환경 구성을 시작합니다. \n"
printf "======================================\n"
printf "\n"
printf "①  SELinux 모드를 permissive 으로 변경합니다.\n"
setenforce 0
sed -i 's/SELINUX=enforcing/SELINUX=permissive/g' /etc/selinux/config
printf "\n"
sleep 3
printf "②  firewalld 방화벽 기능을 해지합니다.\n"
systemctl disable --now firewalld
printf "\n"
sleep 3
printf "③  'httpd', 'gnome-tweaks', 'gnome-extensions-app' 패키지를 설치하고 윈도우 환경을 설정합니다.\n"
yum -y -q install httpd gnome-tweaks gnome-extensions-app.x86_64 && sleep 5
gnome-extensions enable background-logo@fedorahosted.org
gnome-extensions enable places-menu@gnome-shell-extensions.gcampax.github.com
gnome-extensions enable apps-menu@gnome-shell-extensions.gcampax.github.com
gnome-extensions enable window-list@gnome-shell-extensions.gcampax.github.com
gnome-extensions enable desktop-icons@gnome-shell-extensions.gcampax.github.com
gnome-extensions enable launch-new-instance@gnome-shell-extensions.gcampax.github.com
printf "\n"
sleep 3
printf "④  절전 모드 및 화면 잠금 기능을 해지합니다.\n"
systemctl mask sleep.target suspend.target hibernate.target hybrid-sleep.target
gsettings set org.gnome.desktop.session idle-delay 0
gsettings set org.gnome.desktop.screensaver lock-enabled false
gsettings set org.gnome.desktop.screensaver lock-delay 0
printf "\n"
sleep 3
printf "⑤  '.vimrc' 파일을 생성합니다.\n"

cat << EOF > ~/.vimrc
set nu
set ts=4
set title
set paste
set bg=dark
syntax on
autocmd FileType yaml setlocal ai nu ts=2 sw=2 et paste
autocmd Filetype python setlocal ai nu ts=2 sw=2 et paste
EOF

printf "\n"
sleep 3
printf "⑥  '.bashrc' 파일에 alias와 PS1 환경 변수를 설정합니다.\n"

cat << EOF >> ~/.bashrc
alias vi='vim'
alias ls='ls --color=auto --time-style=long-iso'
export PS1='\[\e[31;1m\][\u@\h\[\e[31;1m\] \w]# \[\e[m\]'
EOF

printf "\n"
sleep 3
printf "⑦  바탕화면에 터미널 아이콘을 생성합니다.\n"
ln -s /usr/bin/gnome-terminal /root/바탕화면/터미널
printf "\n"
sleep 3
printf "======================================\n"
printf "CentOS 기본 환경 구성이 완료되었습니다.\n"
printf "======================================\n"
printf "\n"
sleep 3
cd
printf "⑧  sestatus 를 실시하여 SElinux 모드가 permissive 로 변경되었는지 확인하세요.\n"
printf "⑨  systemctl status firewalld 를 실시하여 방화벽 상태가 inactive 으로 변경되었는지 확인하세요.\n"
printf "⑩  cat .vimrc 를 실시하여 set 설정 내용이 저장되었는지 확인하세요.\n"
printf "⑪  cat .bashrc 를 실시하여 alias 설정과 PS1 환경 번수 내용이 저장되었는지 확인하세요.\n"
printf "⑫  확인이 완료되었다면, docker를 설치하여 모의해킹 환경을 구성하세요.\n"
printf "\n"


