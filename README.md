# Mysql 2 telegram

Send a result of a query as a telegram message

## Usage
php mysql2telegram.php [hst=host] [prt=port] dbn=name usr=user pwd=password bot=token dst=destination qry=query [fln=filename] [tlt=reporttitle]

where 
* hst = connection host (default 3306)
* prt = connection port (default localhost)
* dbn = database name
* usr = username
* pwd = password

* bot = token of the telegram bot
* dst = telegram chat_id or group_id
* qry = query to execute
* fln = file where query is stored
* tlt = report title

```
php mysql2telegram.php dbn=xxxxxxxxx usr=username pwd=password bot="telegrambot" dst=chatt_id qry="select 1 as result" tlt="Report ABC"
php mysql2telegram.php dbn=xxxxxxxxx usr=username pwd=password bot="telegrambot" dst=chatt_id tlt="Report ABC" fln=test.sql 
```
