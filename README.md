
### Web Sistema Lana
Gaizka Carmona, Eneko Martinez, Mikel Aranburu, Ibai Olaziregi eta Egoitz Yuste

### Proiektua Docker bidez hasteko instrukzioak
- **Build the Docker Image**: 
```bash
$ docker-compose build -t="web" .
```
- **Start the Containers**:
```bash
$ docker-compose up -d
```
- **Access the [Website]**:(http://localhost:81)

- **Access [phpMyAdmin]**: (http://localhost:8890)

- **Stopping the Containers**:
```bash
$ docker-compose down
```

This README provides clear instructions on how to set up and run the project via Docker for the first time.
