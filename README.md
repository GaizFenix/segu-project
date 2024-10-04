
## Web Sistema Lana
### Egileak:
Gaizka Carmona, Eneko Martinez, Mikel Aranburu, Ibai Olaziregi eta Egoitz Yuste

### Hobetsitako bertsioak:
1. **Sistema eragilea**: Ubuntu 22.04.4 LTS
2. **Docker**: 24.0.7
3. **Docker Compose**: 3.8
4. **Apache**: 2.4.57
5. **PHP**: 7.2.2
6. **MariaDB**: 10.8.2
7. **phpMyAdmin**: 5.2.1

### Proiektua Docker bidez hasteko instrukzioak:
- **Docker irudia eraiki**: 
```bash
$ docker-compose build -t="web" .
```
- **_Container_-a hasi**:
```bash
$ docker-compose up -d
```
- **[Web orrialde](http://localhost:81)-ra nabigatu**

- **[PhpMyAdmin](http://localhost:8890) orrialdea ireki**:
  1. Usuario: admin
  2. Contraseña: test

- **[PhpMyAdmin](http://localhost:8890)-ean datu basea gehitu**:
  1. Aukeratu "database" ezkerreko zutabean
  2. "Importar" atalera joan
  3. "Archivo a importar > Examinar > database.sql"
  4. Orrialdearen beheko zatian "Importar" sakatu

- **_Container_-a gelditu**:
```bash
$ docker-compose down
```

README honen bidez proiektua Docker bidez lehenengo aldiz hedatzeko insturkzio zehatzak aurkezten dira.
