# Установка XDebug

```
sudo apt-get install php7.4-xdebug
```

# Конфигурационный файл /etc/php/7.4/mods-available/xdebug.ini или php.ini

```
[xdebug]
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=127.0.0.1
xdebug.client_port=9003
xdebug.log=/var/log/xdebug.log
```

# VSCode Extension

https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug

# Настройка в проекте

1. Открыть проект в VSCode.
2. Перейти к Run and Debug panel (Ctrl+Shift+D).
3. Клик на иконку settings рядом с зеленым треугольником или создать launch.json файл:

```
{
   "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for XDebug",
            "type": "php",
            "request": "launch",
            "port": 9003
        },
        {
            "name": "Launch currently open script",
            "type": "php",
            "request": "launch",
            "program": "${file}",
            "cwd": "${fileDirname}",
            "port": 9003
        }
    ]
}
```
4. Нажать start debugging (тот самый зеленый треугольник)
5. Запускаем проект, как обычно

# Процесс и возможности отладки

## Брейкпоинты

Устанавливаются нажатием перед номером строки, на которой нужно задержать выполнение: http://shots.m18.ru/iwsxt8m8l0/

Как только все breakpoints установлены, можно запускать выполнение скрипта или проект целиком на localhost, при выполнении дебаггер будет останавливаться на указанных точках и выделять их в коде: http://shots.m18.ru/tcbvb7upr8/.

Слева в самом низу есть настройка брейкпоинтов http://shots.m18.ru/i2efp46xj2/: 
- Помимо добавленных пользователем точек, XDebug также останавливается на ошибках, предупреждениях и т.д. (как раз на каких событиях останавливаться, здесь и настраивается)
- В правом верхнем углу этого окошка есть возможность добавить свою функцию для брейкпоинтов, удалить все добавленные ранее, запустить отладку.

(тут дописать подробнее)

## Что и где смотреть для отладки

1. На любой объект в коде можно навестись и увидеть его структуру данных http://shots.m18.ru/ns7u3b4s10/, для вызова функции - результат.
2. Слева сверхе можно просматривать значения всех переменных - как глобальных, так и определенных в отлаживаемом коде: http://shots.m18.ru/gdwoe570at/.
3. Чуть ниже можно увидеть последовательность вызовов и перемещаться по ней: http://shots.m18.ru/2pvd004xlb/.

## Управление процессом исполнения кода

Здесь про Step Over, Into, Out
