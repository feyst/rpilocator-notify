## RPilocator notify
Receive WhatsApp notifications when the Raspberry Pi is for sale somewhere.
First register on [CallMeBot for WhatsApp](https://www.callmebot.com/blog/free-api-whatsapp-messages/).
Next copy the `.env.example` to `.env` and make sure to fill in all the parameters.
Finally run `docker compose up -d` That's it.
```shell
git clone git@github.com:feyst/rpilocator-notify.git
cd rpilocator-notify
cp .env.example .env
vim .env
docker compose up -d
```
The script will check availability on [rpilocator.com](https://rpilocator.com/) every 5 minutes.