# Canonical Url Analytics Service

Canonical Url Analytics jest micro-serwisem napisanym w micro-frameworku Lumen.

## Oficjalna dokumentacja

Dokumentacja frameworku jest dostępna tutaj: [Dokumentacja Lumena](https://lumen.laravel.com/docs).

## Pierwsze uruchomienie

Canonical Url Analytics wymaga do uruchomienia PHP 7.1 z zainstalowanym rozszerzeniami mongodb i json.
Poza tym wymaga również bazy dokumentowej MongoDB. 

Instrukcję instalacji MongoDB na najpopularniejszych systemach operacyjnych można znaleźć [TUTAJ](https://docs.mongodb.com/manual/administration/install-community/).
W przypadku **homesteada** by zainstalować MongoDB wystarczy dopisać do pliku `homestead.yaml` poniższą linię:
```$yaml
mongodb: true
```

Aktualnie aplikacja nie posiada żadnych migracji więc nie jest wymagane uruchomienie żadnej migracji.

**WAŻNE**: należy pamiętać o skopiowaniu pliku `.env.example` do `.env` i wprowadzeniu do nowopowstałego pliku odpowiedniej konfiguracji.

## Dodawanie użytkownika do bazy MongoDB

1. Należy utworzyć nową bazę w MongoDB (można to zrobić poleceniem use i stworzeniem nowej kolekcji z przynajmniej jednym elementem albo przez np. aplikację **robo3t**)
2. W konsoli/terminalu należy zalogować się jako *root* do mongo, najczęściej wystarczy wywołanie polecenia `mongo`
3. Należy przejść na swoją nową bazę korzystając z polecenia `use <nazwa bazy danych>`
4. Należy dodać użytkownika poleceniem:
```
db.createUser({
    user: "<nazwa uzytkownika>",
    pwd: "<haslo uzytkownika>",
    roles: [ { role: "dbAdmin", db: "<nazwa bazy danych>" } ]
})
```
i voila - użytkownik utworzony.
