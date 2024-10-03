
## Web Sistema Lana
### Egileak:
Gaizka Carmona, Eneko Martinez, Mikel Aranburu, Ibai Olaziregi eta Egoitz Yuste

### Proiektua Docker bidez hasteko instrukzioak:
- **Build the Docker Image**: 
```bash
$ docker-compose build -t="web" .
```
- **Start the Containers**:
```bash
$ docker-compose up -d
```
- **Access the [Website](http://localhost:81)**

- **Access [phpMyAdmin](http://localhost:8890)**:
  Usuario: admin
  Contraseña: test

- **Add database to [phpMyAdmin]**:
  1. Aukeratu "database" ezkerreko zutabean
  2. "Importar" atalera joan
  3. "Archivo a importar > Examinar > database.sql"
  4. Orrialdearen beheko zatian "Importar" sakatu

- **Stopping the Containers**:
```bash
$ docker-compose down
```

This README provides clear instructions on how to set up and run the project via Docker for the first time.
