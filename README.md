# Buzzel Challenge

Resolução de um teste prático de avaliação de competências para a vaga de Programador Full-Stack Júnior.

O teste consiste em desenvolver uma solução que retorne os hotéis mais próximos baseando-se na localização do usuário. Pode-se filtrar os resultados tanto por hotéis mais próximos quanto pelo menor preço por noite.

## Requisitos de Instalação

- Terminal
- Docker
- Docker-Compose
- Git

## Instruções de Instalação

- Abra seu terminal,
- Navegue até um diretório de sua preferência,
- Clone o projeto utilizando o comando abaixo:

```
git clone https://github.com/fabiojcsala/buzzvel-challenge
```

- Acesse o diretório do projeto utilizando o comando abaixo:

```
cd buzzvel-challenge
```


- Execute o ambiente utilizando o comando abaixo:

```
sudo docker-compose up -d
```

- Acesse o bash do ambiente utilizando o comando abaixo:

```
sudo docker exec -it bc-php /bin/bash
```

- Uma vez dentro do bash, execute os comandos abaixo:

```
composer install
```
```
php -r "copy('.env.example', '.env');"
```
```
php artisan key:generate
```
```
exit
```

- Talvez seja necessário dar permissão ao diretório do projeto para acessá-lo. Nisto utilize o comando:

```
sudo chmod -R 777 src/
```

**Lembrando que a permissão "777" é recomendada apenas em ambientes de testes ou desenvolvimento. Por questões de segurança esta não deve ser executada em ambientes de produção.**

- Abra seu navegador de preferência e acesse o endereço:

http://localhost/