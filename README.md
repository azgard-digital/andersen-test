Task
==============================================
- Go to project directory
________________________________________________________

- Add mytest.local to hosts file 127.0.0.1 mytest.local
- Define env variables in .env file

Run in docker compose
--------------------------------------------
```
docker-compose up --build
```
___________________________________________________________________________

Run project in container
-----------------------------------------------
- Create network
```
docker network create paxfuly-net
```

- Run postgres image
```
docker run --name postgres --network paxfuly-net -p 5432:5432 \
 -e POSTGRES_DB=db_test -e POSTGRES_USER=postgres POSTGRES_PASSWORD=postgres -d postgres:12.4
```

- Check your DB configuration in .env file
- Build app image
```
docker build -t paxfuly_hw -f dockers/Dockerfile .
```

- Get postgres container ip for DB_HOST env
```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' postgres
```

- Add to DB_HOST postgres container name or ip
- Run app image
```
docker run -it -p 80:80 --network paxfuly-net --env-file=./.env paxfuly_hw
```
_______________________________________________________________

- Check mytest.local:80 url
________________________________

- Run Seeds

```
php artisan db:seed
```

_________________________________________________________________________________________

Request examples:
__________________________________________________________________________________________

- Create User
```
curl --location --request POST 'http://mytest.local/api/users' \
--header 'Content-Type: application/json' \
--header 'Cache-Control: no-cache' \
--header 'Accept: application/json; version=v1' \
--data-raw '{"name":"asd1", "email":"test1@mail.tes", "password":"123456"}'
```
- Create Wallet
```
curl --location --request POST 'http://mytest.local/api/wallets' \
--header 'Content-Type: application/json' \
--header 'Cache-Control: no-cache' \
--header 'Accept: application/json; version=v1' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXRlc3QubG9jYWxcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDAwNjk0MjcsImV4cCI6MTYwNTI1MzQyNywibmJmIjoxNjAwMDY5NDI3LCJqdGkiOiI3QWRQc2N1RmlFNFFOUlJqIiwic3ViIjo1LCJwcnYiOiIxZWFmOTI5NDc5M2QxMjVhYTBmZWU0ZWMzYWQ1OWQ5ZDVmYzE2MGMxIn0.RL0DcdNjsaBq67-Dg9VT8mzxf9Dxqu-IPAbvBzjJHv0' \
--data-raw ''
```
- Create Transaction
```
curl --location --request POST 'http://mytest.local/api/transactions' \
--header 'Content-Type: application/json' \
--header 'Cache-Control: no-cache' \
--header 'Accept: application/json; version=v1' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXRlc3QubG9jYWxcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE1OTk5ODY1NTcsImV4cCI6MjExNTk4NjU1NywibmJmIjoxNTk5OTg2NTU3LCJqdGkiOiJPb0liOHk1MkZSOGtwTXBlIiwic3ViIjoxLCJwcnYiOiIxZWFmOTI5NDc5M2QxMjVhYTBmZWU0ZWMzYWQ1OWQ5ZDVmYzE2MGMxIn0.sTjuDyPbBOm9hJLalBx71D6sYvB2CXkc26Al1UKawAE' \
--data-raw '{"wallets":{"from":"6c83136b0e990f17324ce3d692ccc636", "to":"36465260b1a729f11d745d0f091e4632"},"amount":"1000000"}'
```
- Get Wallet Info
```
curl --location --request GET 'http://mytest.local/api/wallets/6c83136b0e990f17324ce3d692ccc636' \
--header 'Content-Type: application/json' \
--header 'Cache-Control: no-cache' \
--header 'Accept: application/json; version=v1' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXRlc3QubG9jYWxcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE1OTk5ODY1NTcsImV4cCI6MjExNTk4NjU1NywibmJmIjoxNTk5OTg2NTU3LCJqdGkiOiJPb0liOHk1MkZSOGtwTXBlIiwic3ViIjoxLCJwcnYiOiIxZWFmOTI5NDc5M2QxMjVhYTBmZWU0ZWMzYWQ1OWQ5ZDVmYzE2MGMxIn0.sTjuDyPbBOm9hJLalBx71D6sYvB2CXkc26Al1UKawAE' \
--data-raw ''
```
- Get Wallet Transactions
```
curl --location --request GET 'http://mytest.local/api/wallets/6c83136b0e990f17324ce3d692ccc636/transactions' \
--header 'Content-Type: application/json' \
--header 'Cache-Control: no-cache' \
--header 'Accept: application/json; version=v1' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXRlc3QubG9jYWxcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDAwNjI0MTYsImV4cCI6MTYwNTI0NjQxNiwibmJmIjoxNjAwMDYyNDE2LCJqdGkiOiJBUUhFMGpjVGg5ME5oWHBBIiwic3ViIjoxLCJwcnYiOiIxZWFmOTI5NDc5M2QxMjVhYTBmZWU0ZWMzYWQ1OWQ5ZDVmYzE2MGMxIn0.1QWOY5vqdE1YotdMU9glQ5WQfSOP2ckEkvqUwSCtgL0' \
--data-raw ''
```
- Get Logged User Transactions
```
curl --location --request GET 'http://mytest.local/api/transactions' \
--header 'Content-Type: application/json' \
--header 'Cache-Control: no-cache' \
--header 'Accept: application/json; version=v1' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXRlc3QubG9jYWxcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDAwNjI0MTYsImV4cCI6MTYwNTI0NjQxNiwibmJmIjoxNjAwMDYyNDE2LCJqdGkiOiJBUUhFMGpjVGg5ME5oWHBBIiwic3ViIjoxLCJwcnYiOiIxZWFmOTI5NDc5M2QxMjVhYTBmZWU0ZWMzYWQ1OWQ5ZDVmYzE2MGMxIn0.1QWOY5vqdE1YotdMU9glQ5WQfSOP2ckEkvqUwSCtgL0' \
--data-raw ''
```
- Get User Token
```
curl --location --request POST 'http://mytest.local/oauth/token' \
--header 'Cache-Control: no-cache' \
--form 'grant_type=password' \
--form 'client_id=13' \
--form 'client_secret=YOBVzdHOKbAJAjpdUOzwEpqPDQa7B0XowsbFuyds' \
--form 'username=test1@mail.tes' \
--form 'password=123456' \
--form 'scope='
```
