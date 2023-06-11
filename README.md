# BitKasparas

BitKasparas is a cryptocurrency listing and showing single coin information application. It takes data from the CoinMarketCap API and displays it in a table on the main page. Users can click on a cryptocurrency to navigate to its single coin page, where they can view the coin's price history line chart, candlestick chart, and other essential information.

## Features

- View a table of cryptocurrency listings with essential information.
- Click on a cryptocurrency to navigate to its individual coin page.
- On the coin page, users can see the coin's price history line chart and candlestick chart.
- Detailed information about the coin, such as market cap, circulating supply, and more, is displayed on the coin page.
- Dark/Light theme

## Getting Started

To start the BitKasparas project, follow these steps:

1. Clone the repository:
```bash
   git clone https://github.com/your-username/bit-kasparas.git
```
2. Install the PHP dependencies using Composer:
```bash
composer install
```
3. Install the JavaScript dependencies using npm:
```bash
npm install
```
4. Generate the .env file by copying the example:
```bash
cp .env.example .env
```
5. Generate a new application key:
```bash
php artisan key:generate
```
6. Create a MySQL database named bit_degree_app.
7. Update the .env file with your database credentials:
```makefile
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bit_degree_app
DB_USERNAME=root
DB_PASSWORD=
```
8. Migrate the database:
```bash
php artisan migrate
```
9. Fetch API base data to the database:
```bash
php artisan crypto:fetch 1 all
```
10. Build the assets:
```bash
npm run dev
```
11. Start the development server:
```bash
php artisan serve
```
12. Open your web browser and visit http://localhost:8000 to access the BitKasparas application.
Feel free to explore the cryptocurrency listings and single coin pages to view detailed information and charts.

## Technologies Used

- **Laravel:** PHP framework for the backend.
- **Vue.js:** JavaScript framework for building the user interface.
- **Tailwind:** CSS framework for styling.
- **CoinMarketCap API:** External API for retrieving cryptocurrency data.
- **CoinGecko API:** External API for retrieving cryptocurrency price historical data (used for charts, because it is free).

## Contributing

If you'd like to contribute to BitKasparas, please follow these steps:

1. Fork the repository.
2. Create a new branch.
3. Make your changes.
4. Submit a pull request.

We appreciate any contributions and feedback!

## License

This project is open-source and available under the MIT License. Feel free to use, modify, and distribute it as per the license terms.

   
   
