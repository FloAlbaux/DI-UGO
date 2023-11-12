## DI-UGO-TEST-FULL-STACK-1-0

# Backend Setup

```bash
cd backend
composer install
php bin/console doctrine:migrations:migrate
php bin/console ugo:orders:import
symfony server:start --daemon
cd ..
```

# Frontend Setup
```bash
cd frontend
npm install
npm start
cd ..
```

# Backend Testing
```bash
cd backend
php bin/phpunit
```