# INF1005-Web-Systems-Team-P6-11

_to re-execute init.sql_
docker compose down -v
docker compose up -d

_setup notes from cane_

hi so you will want to learn about the beautiful land of docker. getting the server setup shouldnt take more than just running `docker compose up` in ur terminal. u'll need to scan the output for the IP of the webserver, it should be immediately apparent once you see it

i have set it up w/ a bind volume so that if you edit files in the php folder, the changes will get reflected in the container also. so you dont need to constantly take it down and put it back up again

HOPEFULLY THIS IS THE EASIEST WAY its just one command you need to run and you dont need to actually set up any mysql server on your local machine
