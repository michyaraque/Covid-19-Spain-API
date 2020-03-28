[![](https://i.imgur.com/ceoW1fr.jpg)](#endpoints)

[![CODE-LANG](https://img.shields.io/badge/Version-2.0.0-red)](https://github.com/michydev/Covid-19-Spain-API)    [![CODE-LANG](https://img.shields.io/badge/PHP-7.4%2B-yellow)](https://www.php.net/releases/7_4_0.php)    [![OMS](https://img.shields.io/badge/Covid--19-Espa%C3%B1a/Comunidades_Autonomas-orange)](https://www.mscbs.gob.es/profesionales/saludPublica/ccayes/alertasActual/nCov-China/situacionActual.htm)

**Bitcoin Wallet**: 1JH7bRt1zs5VKFRpe2QeYLq42XXQKtFVpH

Covid-19 Spain API es una api no oficial que toma los datos del Ministerio de Sanidad y lo convierte en API Rest

  - Api Restful
  - Baja latencia

Probar API con todas sus funcionalidades: [CoronAPI](https://api.chollx.es/coronavirus/coronapi)
# Endpoints 

| Petición GET                                            | Salida                                                       |
|---------------------------------------------------------|--------------------------------------------------------------|
| https://api.chollx.es/coronavirus/ca                    | Retorna todos los valores de todas las comunidades autonomas |
| https://api.chollx.es/coronavirus/ca/{Nombre_Comunidad} | Retorna los valores especificos de una comunidad autonoma, [nombre de comunidades autonomas](https://www.ine.es/daco/daco42/codmun/cod_ccaa.htm)    |
| https://api.chollx.es/coronavirus/all                   | Retorna los datos oficiales de toda España                   |
| https://api.chollx.es/coronavirus/hint                  | Retorna una guia en formato JSON de la API                   |

### Instalación

Esta API requiere [PHP 7.4+](https://www.php.net/releases/7_4_0.php) para funcionar.

Se deben instalar las dependencias via Composer.

```sh
$ composer install
$ composer update smalot/pdfparser
$ composer update
```

### Cronjob descarga de archivo
Programado para actualizarse cada dos horas
```sh
*       */2       *       *       *       TZ=Europe/Madrid php /relative_path/download_cron.php
```

### Desarrolladores

 - [@Michyaraque](https://t.me/michyaraque)

License
----

GNU Public License
